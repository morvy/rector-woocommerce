<?php

namespace Morvy\RectorWooCommerce\PHPStan\NodeVisitor;

use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\While_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Case_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\MatchArm;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Break_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Exit_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Instanceof_;
use PhpParser\Node\Expr\Match_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\StaticPropertyFetch;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\FunctionLike;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeFinder;
use PhpParser\NodeVisitorAbstract;

/**
 * Marks code the feature rules must not report, because it already handles both the old and the new mechanism:
 * - HPOS_GUARDED: inside an if/elseif chain, match, ternary or boolean expression that also looks at HPOS
 *   (`if ( OrderUtil::custom_orders_table_usage_is_enabled() ) { … } else { legacy … }`,
 *   `$screen === 'shop_order' || $screen === wc_get_page_screen_id( 'shop-order' )`, `if ( $hpos_enabled )`),
 *   after `if ( hpos ) { …; return; }`, and meta boxes of a function that also registers them for the HPOS screen,
 * - BLOCKS_GUARDED: next to has_block() / is_checkout() checks,
 * - LEGACY_API_GUARDED: behind class_exists( 'WC_API' ) and similar,
 * - EXISTS_GUARDED: class/function declarations inside `! class_exists()` / `! function_exists()`,
 * - ORDER_QUERY_ARG: arrays that reach wc_get_orders() / WC_Order_Query (which accept legacy post_type keys).
 */
final class CompatibilityGuardVisitor extends NodeVisitorAbstract
{
    public const HPOS_GUARDED       = 'woocommerce_hpos_guarded';
    public const BLOCKS_GUARDED     = 'woocommerce_blocks_guarded';
    public const LEGACY_API_GUARDED = 'woocommerce_legacy_api_guarded';
    public const EXISTS_GUARDED     = 'woocommerce_exists_guarded';
    public const ORDER_QUERY_ARG    = 'woocommerce_order_query_arg';
    private const SCOPE             = '_scope';
    private const HPOS_CHECKS       = ['custom_orders_table_usage_is_enabled', 'custom_orders_table_datastore_cache_enabled', 'get_table_for_orders', 'get_table_for_order_meta', 'wc_get_page_screen_id'];
    private const HPOS_NAME         = '/hpos|custom_orders?_tables?|(^|_)cot(_|$)/i';

    /**
     * @var array<string, int>
     */
    private array $depth = [];

    public function beforeTraverse(array $nodes): null
    {
        $this->depth = [];

        return null;
    }

    public function enterNode(Node $node): null
    {
        $condition = $this->condition($node);

        if ($condition instanceof Expr) {
            if ($this->mentionsHpos($condition)) {
                $node->setAttribute(self::HPOS_GUARDED . self::SCOPE, true);
            }

            if ($this->mentions($condition, static fn (Node $part): bool => ($part instanceof FuncCall && $part->name instanceof Name && in_array($part->name->toLowerString(), ['has_block', 'is_checkout', 'is_cart'], true)) ||
                ($part instanceof String_ && in_array($part->value, ['woocommerce/checkout', 'woocommerce/cart'], true)))) {
                $node->setAttribute(self::BLOCKS_GUARDED . self::SCOPE, true);
            }

            if ($this->mentions($condition, static fn (Node $part): bool => $part instanceof String_ && in_array($part->value, ['WC_API', 'WC_Legacy_API', 'WC_Legacy_REST_API_Plugin'], true))) {
                $node->setAttribute(self::LEGACY_API_GUARDED . self::SCOPE, true);
            }
        }

        if ($node instanceof If_ && $node->cond instanceof BooleanNot && $node->cond->expr instanceof FuncCall && $node->cond->expr->name instanceof Name &&
            in_array($node->cond->expr->name->toLowerString(), ['class_exists', 'function_exists'], true)) {
            $node->setAttribute(self::EXISTS_GUARDED . self::SCOPE, true);
        }

        $stmts = match (true) {
            $node instanceof FunctionLike => $node->getStmts(),
            $node instanceof If_, $node instanceof ElseIf_, $node instanceof Else_, $node instanceof Foreach_,
            $node instanceof For_, $node instanceof While_, $node instanceof Do_,
            $node instanceof Case_, $node instanceof TryCatch, $node instanceof Namespace_ => $node->stmts,
            default                                                                        => null,
        };

        if (is_array($stmts)) {
            $this->markAfterHposReturn($stmts);
        }

        if ($node instanceof FunctionLike) {
            $this->markFunction($node->getStmts() ?? []);
        }

        foreach ([self::HPOS_GUARDED, self::BLOCKS_GUARDED, self::LEGACY_API_GUARDED, self::EXISTS_GUARDED] as $attribute) {
            if ($node->getAttribute($attribute . self::SCOPE) === true) {
                $this->depth[$attribute] = ($this->depth[$attribute] ?? 0) + 1;
            }

            if (($this->depth[$attribute] ?? 0) > 0) {
                $node->setAttribute($attribute, true);
            }
        }

        return null;
    }

    public function leaveNode(Node $node): null
    {
        foreach ([self::HPOS_GUARDED, self::BLOCKS_GUARDED, self::LEGACY_API_GUARDED, self::EXISTS_GUARDED] as $attribute) {
            if ($node->getAttribute($attribute . self::SCOPE) === true) {
                $this->depth[$attribute]--;
            }
        }

        return null;
    }

    /**
     * The condition(s) a branching node decides on; for an if/elseif chain all of them, since an HPOS check anywhere
     * in the chain makes every branch of it dual-path code.
     */
    private function condition(Node $node): ?Expr
    {
        $conditions = match (true) {
            $node instanceof If_                                                                                             => [$node->cond, ...array_map(static fn (ElseIf_ $elseIf): Expr => $elseIf->cond, $node->elseifs)],
            $node instanceof Match_                                                                                          => [$node->cond, ...array_merge(...array_map(static fn (MatchArm $matchArm): array => $matchArm->conds ?? [], $node->arms))],
            $node instanceof Ternary                                                                                         => [$node->cond],
            $node instanceof BooleanOr, $node instanceof BooleanAnd, $node instanceof LogicalOr, $node instanceof LogicalAnd => [$node],
            default                                                                                                          => [],
        };

        return $conditions === [] ? null : new Array_(array_map(static fn (Expr $expr): ArrayItem => new ArrayItem($expr), $conditions));
    }

    /**
     * Strong markers: OrderUtil / the orders table controller, helpers and flags named like `is_hpos_enabled()`,
     * `$hpos_enabled`, `$this->use_hpos`, `$cot_enabled`, the 'woocommerce_custom_orders_table_enabled' option,
     * wc_get_page_screen_id() and the HPOS screen ('wc-orders').
     * Weak markers, only inside a branch (for an early return they are mere guards): `$post_or_order instanceof
     * WP_Post` / `WC_Order` (the documented way to accept both callback arguments) and a 'woocommerce…' screen prefix.
     */
    private function mentionsHpos(Expr $expr, bool $weakMarkersCount = true): bool
    {
        return $this->mentions($expr, static function (Node $node) use ($weakMarkersCount): bool {
            if ($node instanceof String_) {
                return str_contains($node->value, 'wc-orders') || preg_match(self::HPOS_NAME, $node->value) === 1 ||
                    ($weakMarkersCount && ($node->value === 'woocommerce' || str_starts_with($node->value, 'woocommerce_page')));
            }

            if ($node instanceof Instanceof_) {
                return $weakMarkersCount && $node->class instanceof Name && in_array($node->class->toLowerString(), ['wp_post', 'wc_order', 'wc_abstract_order'], true);
            }

            if ($node instanceof Variable) {
                return is_string($node->name) && preg_match(self::HPOS_NAME, $node->name) === 1;
            }

            if ($node instanceof PropertyFetch || $node instanceof NullsafePropertyFetch || $node instanceof StaticPropertyFetch) {
                return $node->name instanceof Identifier && preg_match(self::HPOS_NAME, $node->name->toString()) === 1;
            }

            if (($node instanceof StaticCall || $node instanceof MethodCall || $node instanceof FuncCall) && ($node->name instanceof Identifier || $node->name instanceof Name)) {
                $name = mb_strtolower($node->name->toString());

                if (in_array($name, self::HPOS_CHECKS, true) || preg_match(self::HPOS_NAME, $name) === 1) {
                    return true;
                }
            }

            return $node instanceof StaticCall && $node->class instanceof Name && str_ends_with($node->class->toLowerString(), 'orderutil');
        });
    }

    /**
     * @param callable(Node): bool $matches
     */
    private function mentions(Expr $expr, callable $matches): bool
    {
        return (new NodeFinder)->findFirst($expr, $matches) instanceof Node;
    }

    /**
     * `if ( hpos ) { …; return …; }` followed by legacy code: everything after the if is the legacy branch.
     *
     * @param array<Node> $stmts
     */
    private function markAfterHposReturn(array $stmts): void
    {
        $guarded = false;

        foreach ($stmts as $stmt) {
            if ($guarded) {
                $stmt->setAttribute(self::HPOS_GUARDED . self::SCOPE, true);

                continue;
            }

            $guarded = $stmt instanceof If_ && !$stmt->else instanceof Else_ && $stmt->elseifs === [] && $this->mentionsHpos($stmt->cond, false) && $this->exits($stmt->stmts);
        }
    }

    /**
     * @param array<Node> $stmts
     */
    private function exits(array $stmts): bool
    {
        $last = end($stmts);

        return $last instanceof Return_ || $last instanceof Continue_ || $last instanceof Break_ ||
            ($last instanceof Expression && ($last->expr instanceof Throw_ || $last->expr instanceof Exit_));
    }

    /**
     * Within one function:
     * - meta boxes and `… === 'shop_order'` screen checks are covered when the function also registers a meta box for
     *   the HPOS screen,
     * - arrays reaching wc_get_orders() / WC_Order_Query directly, inside array_merge() & co., or through a variable
     *   assigned in the function are order queries.
     *
     * @param array<Node> $stmts
     */
    private function markFunction(array $stmts): void
    {
        $nodeFinder = new NodeFinder;

        /** @var list<FuncCall|New_> $calls */
        $calls = $nodeFinder->find($stmts, static fn (Node $node): bool => $node instanceof FuncCall || $node instanceof New_);

        $metaBoxes = array_filter($calls, static fn (FuncCall|New_ $call): bool => $call instanceof FuncCall && $call->name instanceof Name && in_array($call->name->toLowerString(), ['add_meta_box', 'remove_meta_box'], true));

        // An add_meta_boxes callback that also registers for the HPOS screen: its legacy screen checks are the other half.
        if (array_filter($metaBoxes, $this->registersHposScreen(...)) !== []) {
            $screenChecks = $nodeFinder->find($stmts, static fn (Node $node): bool => ($node instanceof Identical || $node instanceof Equal) &&
                (($node->left instanceof String_ && in_array($node->left->value, ['shop_order', 'edit-shop_order'], true)) || ($node->right instanceof String_ && in_array($node->right->value, ['shop_order', 'edit-shop_order'], true))));

            foreach ([...$metaBoxes, ...$screenChecks] as $covered) {
                $covered->setAttribute(self::HPOS_GUARDED, true);
            }
        }

        $queryVariables = [];

        foreach ($calls as $call) {
            if (!$this->isOrderQuery($call)) {
                continue;
            }

            foreach ($call->getArgs() as $arg) {
                foreach ($nodeFinder->find($arg->value, static fn (Node $node): bool => $node instanceof Array_ || $node instanceof Variable) as $part) {
                    if ($part instanceof Array_) {
                        $part->setAttribute(self::ORDER_QUERY_ARG, true);
                    } elseif ($part instanceof Variable && is_string($part->name)) {
                        $queryVariables[$part->name] = true;
                    }
                }
            }
        }

        if ($queryVariables === []) {
            return;
        }

        foreach ($nodeFinder->findInstanceOf($stmts, Assign::class) as $assign) {
            if ($assign->var instanceof Variable && is_string($assign->var->name) && isset($queryVariables[$assign->var->name])) {
                foreach ($nodeFinder->findInstanceOf($assign->expr, Array_::class) as $array) {
                    $array->setAttribute(self::ORDER_QUERY_ARG, true);
                }
            }
        }
    }

    private function registersHposScreen(FuncCall|New_ $call): bool
    {
        if (!$call instanceof FuncCall) {
            return false;
        }

        $position = $call->name instanceof Name && $call->name->toLowerString() === 'add_meta_box' ? 3 : 1;
        $screen   = $call->getArgs()[$position]->value ?? null;

        return $screen instanceof Expr && $this->mentions($screen, static fn (Node $node): bool => ($node instanceof String_ && str_contains($node->value, 'wc-orders')) ||
            (($node instanceof FuncCall || $node instanceof StaticCall) && ($node->name instanceof Name || $node->name instanceof Identifier) &&
                in_array(mb_strtolower($node->name->toString()), ['wc_get_page_screen_id', 'get_order_admin_screen'], true)));
    }

    private function isOrderQuery(FuncCall|New_ $call): bool
    {
        if ($call instanceof FuncCall) {
            return $call->name instanceof Name && in_array($call->name->toLowerString(), ['wc_get_orders', 'wc_get_order_types'], true);
        }

        return $call->class instanceof Name && in_array($call->class->toLowerString(), ['wc_order_query', 'automattic\woocommerce\internal\datastores\orders\orderstablequery'], true);
    }
}
