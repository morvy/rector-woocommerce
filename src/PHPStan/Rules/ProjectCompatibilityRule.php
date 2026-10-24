<?php

namespace Morvy\RectorWooCommerce\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\Collectors\CallFactCollector;
use Morvy\RectorWooCommerce\PHPStan\Collectors\ClassFactCollector;
use Morvy\RectorWooCommerce\PHPStan\Collectors\PluginFileCollector;
use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Features;
use Morvy\RectorWooCommerce\PHPStan\Hooks;
use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Node\CollectedDataNode;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Project-wide checks: missing feature compatibility declarations and payment gateways without a block integration.
 *
 * @implements Rule<CollectedDataNode>
 */
final readonly class ProjectCompatibilityRule implements Rule
{
    public function __construct(
        private FeatureGate $featureGate
    ) {}

    public function getNodeType(): string
    {
        return CollectedDataNode::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        $facts = [];

        foreach ([PluginFileCollector::class, ClassFactCollector::class, CallFactCollector::class] as $collector) {
            foreach ($node->get($collector) as $file => $entries) {
                foreach ($entries as [$kind, $value, $line, $guarded]) {
                    $facts[$kind][] = [$file, $value, $line, $guarded];
                }
            }
        }

        $declared = array_column($facts['declares'] ?? [], 1);

        if (in_array('*', $declared, true)) {
            $declared = ['custom_order_tables', 'cart_checkout_blocks'];
        }
        $errors   = [];

        foreach ($facts['plugin'] ?? [] as [$file, $headers]) {
            // WooCommerce only evaluates feature compatibility for plugins with the WC headers.
            if ($headers === 'wc' && $this->featureGate->isActive(Features::HPOS) && !in_array('custom_order_tables', $declared, true)) {
                $errors[] = $this->error(
                    'Plugin does not declare HPOS compatibility; WooCommerce treats it as incompatible, warns about it and does not enable HPOS on new stores (default since 8.2).',
                    'woocommerce.hpos.declaration',
                    "Once the woocommerce.hpos.* findings are fixed, call FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true ) on before_woocommerce_init.",
                    $file,
                    1
                );
            }

            $classicHooks    = array_filter($facts['hook'] ?? [], static fn (array $fact): bool => (Hooks::HOOKS[$fact[1]][1] ?? null) === 'woocommerce.blocks.classicHook');
            $touchesCheckout = $classicHooks !== [] || ($facts['gateway'] ?? []) !== [] || ($facts['blockSupport'] ?? []) !== [] || ($facts['blockPayment'] ?? []) !== [];

            if ($headers === 'wc' && $touchesCheckout && $this->featureGate->isActive(Features::BLOCKS) && !in_array('cart_checkout_blocks', $declared, true)) {
                $errors[] = $this->error(
                    'Plugin extends the cart/checkout but does not declare Cart and Checkout blocks compatibility (blocks are the default for new stores since WooCommerce 8.3).',
                    'woocommerce.blocks.declaration',
                    "Call FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true|false ) on before_woocommerce_init, so merchants see the right notice.",
                    $file,
                    1
                );
            }
        }

        if (($facts['blockPayment'] ?? []) === [] && $this->featureGate->isActive(Features::BLOCKS)) {
            foreach ($facts['gateway'] ?? [] as [$file, $class, $line]) {
                $errors[] = $this->error(
                    sprintf('Payment gateway %s has no Cart/Checkout blocks integration; it is not offered on the Checkout block, the default for new stores since WooCommerce 8.3.', $class),
                    'woocommerce.blocks.gateway',
                    'Register an AbstractPaymentMethodType subclass on woocommerce_blocks_payment_method_type_registration and a JS registerPaymentMethod().',
                    $file,
                    $line
                );
            }
        }

        return [...$errors, ...$this->hooks($facts)];
    }

    /**
     * Callbacks on hooks that stop firing with a feature on, unless the project also covers the feature:
     * legacy order-screen hooks are fine next to their HPOS counterpart, classic checkout hooks next to a blocks integration.
     *
     * @param array<string, list<array{0: string, 1: string, 2: int, 3: bool}>> $facts
     *
     * @return list<IdentifierRuleError>
     */
    private function hooks(array $facts): array
    {
        $registered   = array_flip(array_column($facts['hook'] ?? [], 1));
        $blockSupport = ($facts['blockSupport'] ?? []) !== [] || ($facts['blockPayment'] ?? []) !== [];
        $errors       = [];

        foreach ($facts['hook'] ?? [] as [$file, $hook, $line, $guarded]) {
            if ($guarded || !isset(Hooks::HOOKS[$hook])) {
                continue;
            }

            [$feature, $identifier, $message, $tip] = Hooks::HOOKS[$hook];

            if (!$this->featureGate->isActive($feature)) {
                continue;
            }

            if ($identifier === 'woocommerce.blocks.classicHook' && $blockSupport) {
                continue;
            }

            foreach (Hooks::HPOS_COUNTERPARTS[$hook] ?? [] as $counterpart) {
                if (isset($registered[$counterpart])) {
                    continue 2;
                }
            }

            $errors[] = $this->error(sprintf($message, $hook), $identifier, $tip, $file, $line);
        }

        return $errors;
    }

    private function error(string $message, string $identifier, string $tip, string $file, int $line): IdentifierRuleError
    {
        return RuleErrorBuilder::message($message)->identifier($identifier)->tip($tip)->file($file)->line($line)->build();
    }
}
