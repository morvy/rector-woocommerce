<?php

namespace Morvy\RectorWooCommerce\PHPStan\Collectors;

use Morvy\RectorWooCommerce\PHPStan\OrderContext;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\CallLike;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\InterpolatedStringPart;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\InterpolatedString;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;

/**
 * Collects call facts for ProjectCompatibilityRule, as [kind, value, line, guarded]:
 * - `declares`: FeaturesUtil::declare_compatibility( '<feature>', … ),
 * - `hook`: an add_action/add_filter hook name, computed parts written as `{screen}`,
 * - `blockPayment`: the blocks payment method registration hook,
 * - `blockSupport`: Store API hooks or functions, block registration hooks, additional checkout fields.
 *
 * @implements Collector<CallLike, array{0: string, 1: string, 2: int, 3: bool}>
 */
final class CallFactCollector implements Collector
{
    private const BLOCK_FUNCTIONS = [
        'woocommerce_store_api_register_endpoint_data', 'woocommerce_store_api_register_update_callback',
        'woocommerce_store_api_register_payment_requirements', 'woocommerce_register_additional_checkout_field',
        '__experimental_woocommerce_blocks_register_checkout_field',
    ];

    public function getNodeType(): string
    {
        return CallLike::class;
    }

    public function processNode(Node $node, Scope $scope): ?array
    {
        return match (true) {
            $node instanceof StaticCall => $this->declaration($node),
            $node instanceof FuncCall   => $this->function($node),
            default                     => null,
        };
    }

    /**
     * @return null|array{0: string, 1: string, 2: int, 3: bool}
     */
    private function declaration(StaticCall $staticCall): ?array
    {
        if (!$staticCall->name instanceof Identifier || $staticCall->name->toLowerString() !== 'declare_compatibility' || !$staticCall->class instanceof Name || !str_ends_with(mb_strtolower($staticCall->class->toString()), 'featuresutil')) {
            return null;
        }

        $feature = $staticCall->getArgs()[0]->value ?? null;

        // A computed feature id (a plugin's own wrapper around declare_compatibility()) may declare anything: '*'.
        return ['declares', $feature instanceof String_ ? $feature->value : '*', $staticCall->getStartLine(), false];
    }

    /**
     * @return null|array{0: string, 1: string, 2: int, 3: bool}
     */
    private function function(FuncCall $funcCall): ?array
    {
        if (!$funcCall->name instanceof Name) {
            return null;
        }

        $function = $funcCall->name->toLowerString();

        if (in_array($function, self::BLOCK_FUNCTIONS, true)) {
            return ['blockSupport', $function, $funcCall->getStartLine(), false];
        }

        if (!in_array($function, ['add_action', 'add_filter'], true) || !isset($funcCall->getArgs()[0])) {
            return null;
        }

        $hook = $this->hookName($funcCall->getArgs()[0]->value);

        if ($hook === 'woocommerce_blocks_payment_method_type_registration') {
            return ['blockPayment', $hook, $funcCall->getStartLine(), false];
        }

        if (str_starts_with($hook, 'woocommerce_store_api_') || preg_match('/^woocommerce_blocks_\w+_block_registration$/', $hook) === 1) {
            return ['blockSupport', $hook, $funcCall->getStartLine(), false];
        }

        return ['hook', $hook, $funcCall->getStartLine(), OrderContext::isGuarded($funcCall)];
    }

    /**
     * 'manage_' . wc_get_page_screen_id( 'shop-order' ) . '_columns' → 'manage_{screen}_columns'.
     */
    private function hookName(Expr $expr): string
    {
        $name = match (true) {
            $expr instanceof String_            => $expr->value,
            $expr instanceof Concat             => $this->hookName($expr->left) . $this->hookName($expr->right),
            $expr instanceof InterpolatedString => implode('', array_map(static fn (Node $part): string => $part instanceof InterpolatedStringPart ? $part->value : '{screen}', $expr->parts)),
            default                             => '{screen}',
        };

        return str_replace('woocommerce_page_wc-orders', '{screen}', $name);
    }
}
