<?php

namespace Morvy\RectorWooCommerce\PHPStan\Rules;

use PhpParser\Node\Expr\StaticCall;
use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Features;
use Morvy\RectorWooCommerce\PHPStan\OrderContext;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\InterpolatedStringPart;
use PhpParser\Node\Scalar\String_;
use PhpParser\NodeFinder;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use PHPStan\Type\ObjectType;

/**
 * Reports $wpdb queries that read orders from wp_posts / wp_postmeta.
 *
 * @implements Rule<MethodCall>
 */
final readonly class HposSqlRule implements Rule
{
    private const METHODS   = ['query', 'get_results', 'get_var', 'get_col', 'get_row', 'prepare'];
    private const ORDER_SQL = '/shop_order|[\'"]wc-(pending|processing|on-hold|completed|cancelled|refunded|failed)[\'"]/';

    public function __construct(
        private FeatureGate $featureGate
    ) {}

    public function getNodeType(): string
    {
        return MethodCall::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->name instanceof Identifier || !in_array($node->name->toLowerString(), self::METHODS, true)) {
            return [];
        }

        // `global $wpdb` is mixed without a WordPress PHPStan extension, so the variable name counts too.
        $isWpdb = ($node->var instanceof Variable && $node->var->name === 'wpdb') || (new ObjectType('wpdb'))->isSuperTypeOf($scope->getType($node->var))->yes();

        if (!$isWpdb || !$this->featureGate->isActive(Features::HPOS) || OrderContext::isGuarded($node)) {
            return [];
        }

        // `$wpdb->get_var( $wpdb->prepare( … ) )`: the inner prepare() is reported.
        foreach ($node->getArgs() as $arg) {
            if ($arg->value instanceof MethodCall && $arg->value->name instanceof Identifier && $arg->value->name->toLowerString() === 'prepare') {
                return [];
            }
        }

        // Literal SQL text, plus table properties such as {$wpdb->postmeta}.
        $sql = implode(' ', array_map(
            static fn (Node $part): string => match (true) {
                $part instanceof String_, $part instanceof InterpolatedStringPart   => $part->value,
                $part instanceof PropertyFetch && $part->name instanceof Identifier => $part->name->toString(),
                default                                                             => '',
            },
            (new NodeFinder)->find($node->getArgs(), static fn (Node $part): bool => $part instanceof String_ || $part instanceof InterpolatedStringPart || $part instanceof PropertyFetch)
        ));

        // Already querying the HPOS / analytics tables, or the table OrderUtil picks.
        if (preg_match('/wc_orders|wc_order_(stats|addresses|operational_data|product_lookup)/', $sql) === 1 ||
            (new NodeFinder)->findFirst($node->getArgs(), static fn (Node $part): bool => ($part instanceof MethodCall || $part instanceof StaticCall) && $part->name instanceof Identifier && str_starts_with($part->name->toLowerString(), 'get_table_for_order')) instanceof Node) {
            return [];
        }

        $isOrderSql = preg_match(self::ORDER_SQL, $sql) === 1;

        if (!$isOrderSql && str_contains($sql, 'postmeta')) {
            foreach (Features::INTERNAL_ORDER_META_KEYS as $key) {
                if (str_contains($sql, $key)) {
                    $isOrderSql = true;

                    break;
                }
            }
        }

        if (!$isOrderSql) {
            return [];
        }

        return [
            RuleErrorBuilder::message('SQL reads orders from wp_posts/wp_postmeta; under HPOS (default for new stores since WooCommerce 8.2) they are in wc_orders, wc_orders_meta, wc_order_addresses and wc_order_operational_data.')
                ->identifier('woocommerce.hpos.sql')
                ->tip('Use wc_get_orders() / WC_Order_Query, or branch on OrderUtil::custom_orders_table_usage_is_enabled() and use OrderUtil::get_table_for_orders() / get_table_for_order_meta().')
                ->build(),
        ];
    }
}
