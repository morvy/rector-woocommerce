<?php

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/../config.php');

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'Automattic\WooCommerce\Blocks\Domain\Services\ExtendRestApi'                => 'Automattic\WooCommerce\StoreApi\Schemas\ExtendSchema',
        'Automattic\WooCommerce\Blocks\Payments\PaymentContext'                      => 'Automattic\WooCommerce\StoreApi\Payments\PaymentContext',
        'Automattic\WooCommerce\Blocks\Payments\PaymentResult'                       => 'Automattic\WooCommerce\StoreApi\Payments\PaymentResult',
        'Automattic\WooCommerce\Blocks\StoreApi\Formatters'                          => 'Automattic\WooCommerce\StoreApi\Formatters',
        'Automattic\WooCommerce\Blocks\StoreApi\RoutesController'                    => 'Automattic\WooCommerce\StoreApi\RoutesController',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\AbstractCartRoute'            => 'Automattic\WooCommerce\StoreApi\Routes\V1\AbstractCartRoute',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\AbstractRoute'                => 'Automattic\WooCommerce\StoreApi\Routes\V1\AbstractRoute',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\AbstractTermsRoute'           => 'Automattic\WooCommerce\StoreApi\Routes\V1\AbstractTermsRoute',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\Batch'                        => 'Automattic\WooCommerce\StoreApi\Routes\V1\Batch',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\Cart'                         => 'Automattic\WooCommerce\StoreApi\Routes\V1\Cart',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartAddItem'                  => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartAddItem',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartApplyCoupon'              => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartApplyCoupon',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartCoupons'                  => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartCoupons',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartCouponsByCode'            => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartCouponsByCode',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartExtensions'               => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartExtensions',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartItems'                    => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartItems',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartItemsByKey'               => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartItemsByKey',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartRemoveCoupon'             => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartRemoveCoupon',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartRemoveItem'               => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartRemoveItem',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartSelectShippingRate'       => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartSelectShippingRate',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartUpdateCustomer'           => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartUpdateCustomer',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\CartUpdateItem'               => 'Automattic\WooCommerce\StoreApi\Routes\V1\CartUpdateItem',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\Checkout'                     => 'Automattic\WooCommerce\StoreApi\Routes\V1\Checkout',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductAttributeTerms'        => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductAttributeTerms',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductAttributes'            => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductAttributes',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductAttributesById'        => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductAttributesById',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductCategories'            => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductCategories',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductCategoriesById'        => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductCategoriesById',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductCollectionData'        => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductCollectionData',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductReviews'               => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductReviews',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductTags'                  => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductTags',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\Products'                     => 'Automattic\WooCommerce\StoreApi\Routes\V1\Products',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\ProductsById'                 => 'Automattic\WooCommerce\StoreApi\Routes\V1\ProductsById',
        'Automattic\WooCommerce\Blocks\StoreApi\Routes\RouteException'               => 'Automattic\WooCommerce\StoreApi\Exceptions\RouteException',
        'Automattic\WooCommerce\Blocks\StoreApi\SchemaController'                    => 'Automattic\WooCommerce\StoreApi\SchemaController',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\AbstractAddressSchema'       => 'Automattic\WooCommerce\StoreApi\Schemas\V1\AbstractAddressSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\AbstractSchema'              => 'Automattic\WooCommerce\StoreApi\Schemas\V1\AbstractSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\BillingAddressSchema'        => 'Automattic\WooCommerce\StoreApi\Schemas\V1\BillingAddressSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartCouponSchema'            => 'Automattic\WooCommerce\StoreApi\Schemas\V1\CartCouponSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartExtensionsSchema'        => 'Automattic\WooCommerce\StoreApi\Schemas\V1\CartExtensionsSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartFeeSchema'               => 'Automattic\WooCommerce\StoreApi\Schemas\V1\CartFeeSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartItemSchema'              => 'Automattic\WooCommerce\StoreApi\Schemas\V1\CartItemSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartSchema'                  => 'Automattic\WooCommerce\StoreApi\Schemas\V1\CartSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\CartShippingRateSchema'      => 'Automattic\WooCommerce\StoreApi\Schemas\V1\CartShippingRateSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\CheckoutSchema'              => 'Automattic\WooCommerce\StoreApi\Schemas\V1\CheckoutSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\ErrorSchema'                 => 'Automattic\WooCommerce\StoreApi\Schemas\V1\ErrorSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\ImageAttachmentSchema'       => 'Automattic\WooCommerce\StoreApi\Schemas\V1\ImageAttachmentSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\OrderCouponSchema'           => 'Automattic\WooCommerce\StoreApi\Schemas\V1\OrderCouponSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\ProductAttributeSchema'      => 'Automattic\WooCommerce\StoreApi\Schemas\V1\ProductAttributeSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\ProductCategorySchema'       => 'Automattic\WooCommerce\StoreApi\Schemas\V1\ProductCategorySchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\ProductCollectionDataSchema' => 'Automattic\WooCommerce\StoreApi\Schemas\V1\ProductCollectionDataSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\ProductReviewSchema'         => 'Automattic\WooCommerce\StoreApi\Schemas\V1\ProductReviewSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\ProductSchema'               => 'Automattic\WooCommerce\StoreApi\Schemas\V1\ProductSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\ShippingAddressSchema'       => 'Automattic\WooCommerce\StoreApi\Schemas\V1\ShippingAddressSchema',
        'Automattic\WooCommerce\Blocks\StoreApi\Schemas\TermSchema'                  => 'Automattic\WooCommerce\StoreApi\Schemas\V1\TermSchema',
    ]);

    /*
     * TODO: these are not handled currently
     *
     * ACTIONS
     * - __experimental_woocommerce_blocks_checkout_order_processed → woocommerce_store_api_checkout_order_processed
     * - __experimental_woocommerce_blocks_checkout_update_order_from_request → woocommerce_store_api_checkout_update_order_from_request
     * - __experimental_woocommerce_blocks_checkout_update_order_meta → woocommerce_store_api_checkout_update_order_meta
     * - woocommerce_blocks_cart_update_customer_from_request → woocommerce_store_api_cart_update_customer_from_request
     * - woocommerce_blocks_cart_update_order_from_request → woocommerce_store_api_cart_update_order_from_request
     * - woocommerce_blocks_checkout_order_processed → woocommerce_store_api_checkout_order_processed
     * - woocommerce_blocks_checkout_update_order_from_request → woocommerce_store_api_checkout_update_order_from_request
     * - woocommerce_blocks_checkout_update_order_meta → woocommerce_store_api_checkout_update_order_meta
     * - wooocommerce_store_api_validate_add_to_cart → woocommerce_store_api_validate_add_to_cart
     * - wooocommerce_store_api_validate_cart_item → woocommerce_store_api_validate_cart_item
     *
     * FUNCTIONS
     * - Automattic\WooCommerce\Admin\FeaturePlugin
     * - Automattic\WooCommerce\Admin\Features\ActivityPanels
     * - Automattic\WooCommerce\Admin\Features\Analytics
     * - Automattic\WooCommerce\Admin\Features\Coupons
     * - Automattic\WooCommerce\Admin\Features\CouponsMovedTrait
     * - Automattic\WooCommerce\Admin\Features\CustomerEffortScoreTracks
     * - Automattic\WooCommerce\Admin\Features\Homescreen
     * - Automattic\WooCommerce\Admin\Features\Marketing
     * - Automattic\WooCommerce\Admin\Features\MobileAppBanner
     * - Automattic\WooCommerce\Admin\Features\RemoteFreeExtensions\Init
     * - Automattic\WooCommerce\Admin\Features\RemoteInboxNotifications
     * - Automattic\WooCommerce\Admin\Features\ShippingLabelBanner
     * - Automattic\WooCommerce\Admin\Features\ShippingLabelBannerDisplayRules
     * - Automattic\WooCommerce\Admin\Features\WcPayPromotion\Init
     * - Automattic\WooCommerce\Admin\Features\WcPayWelcomePage
     *
     * METHODS
     * - Automattic\WooCommerce\Admin\FeaturePlugin::init
     * - WC_Gateway_Paypal_PDT_Handler::check_response
     */
};
