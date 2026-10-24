<?php

namespace Morvy\RectorWooCommerce\Rules\PropertyFetch;

use Morvy\RectorWooCommerce\ValueObject\PropertyFetchToMethodCall;
use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\AssignRef;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Unset_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\ObjectType;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PhpParser\Node\FileNode;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Replaces plain reads of legacy magic properties with their getter.
 *
 * Left untouched, because the getter would behave differently or not compile:
 * - writes, including nested targets (`$o->p[] = 1`, `[$o->p] = $x`, `$o->p = &$x`, `foreach ($a as $o->p)`)
 * - isset(), empty() and `??`, which go through the legacy __isset() and can give a different answer
 * - `$var->prop` read in a function or method (closures included), or top-level code, that also writes `$var->prop` (the write creates a dynamic property the getter ignores)
 * - `$this->prop`, and properties a subclass declares as public
 */
final class PropertyFetchToMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    public const SKIP = 'rector_woocommerce_skip_property_fetch';

    /**
     * @var array<PropertyFetchToMethodCall>
     */
    private array $configuration = [];

    public function __construct(
        private readonly ReflectionProvider $reflectionProvider
    ) {}

    public function getNodeTypes(): array
    {
        return [FileNode::class, PropertyFetch::class, NullsafePropertyFetch::class];
    }

    /**
     * @param FileNode|NullsafePropertyFetch|PropertyFetch $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof FileNode) {
            $this->markUnsafeFetches($node);

            return null;
        }

        if ($node->getAttribute(self::SKIP) === true || $node->getAttribute(AttributeKey::IS_USED_AS_ARG_BY_REF_VALUE) === true) {
            return null;
        }

        if ($node->var instanceof Variable && $this->isName($node->var, 'this')) {
            return null;
        }

        foreach ($this->configuration as $config) {
            if (!$this->isName($node->name, $config->getProperty())) {
                continue;
            }

            if (!$this->isObjectType($node->var, new ObjectType($config->getClass()))) {
                continue;
            }

            if ($this->hasPublicNativeProperty($node->var, $config->getProperty())) {
                return null;
            }

            return $node instanceof NullsafePropertyFetch
                ? new NullsafeMethodCall($node->var, $config->getMethod())
                : new MethodCall($node->var, $config->getMethod());
        }

        return null;
    }

    /**
     * @param array<mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, PropertyFetchToMethodCall::class);

        /** @var array<PropertyFetchToMethodCall> $configuration */
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace reading a legacy magic property with its getter method',
            [
                new ConfiguredCodeSample(
                    '$id = $order->id;',
                    '$id = $order->get_id();',
                    [new PropertyFetchToMethodCall('WC_Order', 'id', 'get_id')]
                ),
            ]
        );
    }

    /**
     * Runs on the file before its children are visited, so the marks are in place when each fetch is refactored.
     */
    private function markUnsafeFetches(FileNode $fileNode): void
    {
        $visitor = new class ($this) extends NodeVisitorAbstract {
            /**
             * Written "$var->prop" keys and all fetches, per scope (function, method, closure, or top-level code).
             *
             * @var array<int, array{keys: array<string, true>, fetches: list<NullsafePropertyFetch|PropertyFetch>}>
             */
            public array $scopes = [];

            /**
             * @var list<int>
             */
            private array $stack = [0];

            public function __construct(
                private readonly PropertyFetchToMethodCallRector $propertyFetchToMethodCallRector
            ) {}

            public function enterNode(Node $node): null
            {
                // Closures and arrow functions share the enclosing scope: they capture the same objects.
                if ($node instanceof Function_ || $node instanceof ClassMethod) {
                    $this->stack[] = spl_object_id($node);
                }

                $scope = $this->stack[count($this->stack) - 1];
                $this->scopes[$scope] ??= ['keys' => [], 'fetches' => []];

                if ($node instanceof PropertyFetch || $node instanceof NullsafePropertyFetch) {
                    $this->scopes[$scope]['fetches'][] = $node;
                }

                [$targets, $isWrite] = $this->propertyFetchToMethodCallRector->unsafeTargets($node);

                foreach ($targets as $target) {
                    foreach ($this->propertyFetchToMethodCallRector->fetchChain($target) as $fetch) {
                        $fetch->setAttribute(PropertyFetchToMethodCallRector::SKIP, true);

                        if ($isWrite && ($key = $this->propertyFetchToMethodCallRector->fetchKey($fetch)) !== null) {
                            $this->scopes[$scope]['keys'][$key] = true;
                        }
                    }
                }

                return null;
            }

            public function leaveNode(Node $node): null
            {
                if ($node instanceof Function_ || $node instanceof ClassMethod) {
                    array_pop($this->stack);
                }

                return null;
            }
        };

        (new NodeTraverser($visitor))->traverse($fileNode->stmts);

        foreach ($visitor->scopes as $scope) {
            foreach ($scope['fetches'] as $fetch) {
                if (isset($scope['keys'][$this->fetchKey($fetch) ?? ''])) {
                    $fetch->setAttribute(self::SKIP, true);
                }
            }
        }
    }

    /**
     * Expressions the node writes to (or, for isset/empty/??, checks through __isset), and whether it is a write.
     *
     * @internal
     *
     * @return array{0: array<Expr>, 1: bool}
     */
    public function unsafeTargets(Node $node): array
    {
        return match (true) {
            $node instanceof Assign, $node instanceof AssignOp => [[$node->var], true],
            $node instanceof AssignRef                         => [[$node->var, $node->expr], true],
            $node instanceof Unset_                            => [$node->vars, true],
            $node instanceof PreInc, $node instanceof PreDec,
            $node instanceof PostInc, $node instanceof PostDec => [[$node->var], true],
            $node instanceof Foreach_                          => [array_filter([$node->keyVar, $node->valueVar]), true],
            $node instanceof FuncCall                          => [$this->byRefArgs($node), true],
            $node instanceof Isset_                            => [$node->vars, false],
            $node instanceof Empty_                            => [[$node->expr], false],
            $node instanceof Coalesce                          => [[$node->left], false],
            default                                            => [[], false],
        };
    }

    /**
     * Property fetches that a write target or isset-like check reaches through, e.g. both fetches in `$o->a->b[0]`,
     * and every element of a destructuring list.
     *
     * @internal
     *
     * @return array<NullsafePropertyFetch|PropertyFetch>
     */
    public function fetchChain(Expr $expr): array
    {
        if ($expr instanceof List_ || $expr instanceof Array_) {
            $fetches = [];

            foreach ($expr->items as $item) {
                if ($item instanceof ArrayItem) {
                    $fetches = [...$fetches, ...$this->fetchChain($item->value)];
                }
            }

            return $fetches;
        }

        $fetches = [];

        while ($expr instanceof PropertyFetch || $expr instanceof NullsafePropertyFetch || $expr instanceof ArrayDimFetch) {
            if (!$expr instanceof ArrayDimFetch) {
                $fetches[] = $expr;
            }

            $expr = $expr->var;
        }

        return $fetches;
    }

    /**
     * `$this->order->billing_email` → "this->order->billing_email"; null unless it is a plain property chain on a variable.
     *
     * @internal
     */
    public function fetchKey(Expr $expr): ?string
    {
        if ($expr instanceof Variable) {
            return is_string($expr->name) ? $expr->name : null;
        }

        if (($expr instanceof PropertyFetch || $expr instanceof NullsafePropertyFetch) && $expr->name instanceof Identifier) {
            $var = $this->fetchKey($expr->var);

            return $var === null ? null : $var . '->' . $expr->name->toString();
        }

        return null;
    }

    /**
     * Arguments passed by reference, e.g. the first one of sort($c->product_ids).
     *
     * ponytail: functions only; by-ref method parameters are rare in code touching legacy WC properties.
     *
     * @return array<Expr>
     */
    private function byRefArgs(FuncCall $funcCall): array
    {
        if (!$funcCall->name instanceof Name || $funcCall->isFirstClassCallable() || !$this->reflectionProvider->hasFunction($funcCall->name, null)) {
            return [];
        }

        $parameters = $this->reflectionProvider->getFunction($funcCall->name, null)->getVariants()[0]->getParameters();
        $byRef      = [];

        foreach ($funcCall->getArgs() as $position => $arg) {
            $parameter = $parameters[$position] ?? null;

            if ($parameter !== null && $parameter->passedByReference()->yes()) {
                $byRef[] = $arg->value;
            }
        }

        return $byRef;
    }

    private function hasPublicNativeProperty(Expr $expr, string $property): bool
    {
        foreach ($this->getType($expr)->getObjectClassReflections() as $classReflection) {
            if ($classReflection->hasNativeProperty($property) && $classReflection->getNativeProperty($property)->isPublic()) {
                return true;
            }
        }

        return false;
    }
}
