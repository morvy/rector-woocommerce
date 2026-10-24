<?php

namespace Morvy\RectorWooCommerce\Rules\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Moves callbacks from a deprecated hook to its replacement.
 *
 * Only hook registrations and lookups are renamed. Firing a hook (do_action/apply_filters) and remove_all_*()
 * are left alone: firing the new hook or clearing it would also reach WooCommerce's own callbacks.
 */
final class RenameHookRector extends AbstractRector implements ConfigurableRectorInterface
{
    private const FUNCTIONS = ['add_action', 'add_filter', 'remove_action', 'remove_filter', 'has_action', 'has_filter'];

    /**
     * @var array<string, string>
     */
    private array $configuration = [];

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$this->isNames($node->name, self::FUNCTIONS) || $node->isFirstClassCallable()) {
            return null;
        }

        $hook = $node->getArgs()[0] ?? null;

        if ($hook === null || $hook->name !== null || !$hook->value instanceof String_) {
            return null;
        }

        $new = $this->configuration[$hook->value->value] ?? null;

        if ($new === null) {
            return null;
        }

        $hook->value = new String_($new);

        return $node;
    }

    /**
     * @param array<mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString(array_keys($configuration));
        Assert::allString($configuration);

        /** @var array<string, string> $configuration */
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename a deprecated hook in add_action/add_filter and related calls',
            [
                new ConfiguredCodeSample(
                    "add_filter('woocommerce_get_price', 'my_price', 10, 2);",
                    "add_filter('woocommerce_product_get_price', 'my_price', 10, 2);",
                    ['woocommerce_get_price' => 'woocommerce_product_get_price']
                ),
            ]
        );
    }
}
