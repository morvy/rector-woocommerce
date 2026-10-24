<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\PHPStan\Rules;

use PhpParser\Node\Expr;
use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Features;
use Morvy\RectorWooCommerce\PHPStan\NodeVisitor\CompatibilityGuardVisitor;
use Morvy\RectorWooCommerce\PHPStan\OrderContext;
use PhpParser\Node;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports array arguments like `[ 'post_type' => 'shop_order', … ]`: a WP_Query/get_posts() for orders finds nothing
 * and wp_insert_post() creates a row HPOS never reads. Arrays passed straight to wc_get_orders()/WC_Order_Query are
 * fine (they accept the legacy keys).
 *
 * @implements Rule<Array_>
 */
final readonly class HposOrderPostTypeRule implements Rule
{
    public function __construct(
        private FeatureGate $featureGate
    ) {}

    public function getNodeType(): string
    {
        return Array_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->featureGate->isActive(Features::HPOS) || OrderContext::isGuarded($node) || $node->getAttribute(CompatibilityGuardVisitor::ORDER_QUERY_ARG) === true) {
            return [];
        }

        foreach ($node->items as $item) {
            if (!$item instanceof ArrayItem || !$item->key instanceof String_ || $item->key->value !== 'post_type' || !$this->isOrderPostType($item->value)) {
                continue;
            }

            return [
                RuleErrorBuilder::message('Querying or creating posts of an order post type: under HPOS (default for new stores since WooCommerce 8.2) orders are not in wp_posts.')
                    ->identifier('woocommerce.hpos.postType')
                    ->tip('Query with wc_get_orders( [ \'limit\' => …, \'status\' => …, \'return\' => \'ids\' ] ) and create with wc_create_order().')
                    ->build(),
            ];
        }

        return [];
    }

    private function isOrderPostType(Expr $expr): bool
    {
        if ($expr instanceof String_) {
            return in_array($expr->value, Features::ORDER_POST_TYPES, true);
        }

        if ($expr instanceof Array_) {
            foreach ($expr->items as $item) {
                if ($item instanceof ArrayItem && $this->isOrderPostType($item->value)) {
                    return true;
                }
            }
        }

        return false;
    }
}
