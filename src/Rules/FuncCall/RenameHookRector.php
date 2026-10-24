<?php

namespace Morvy\RectorWooCommerce\Rules\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Moves callbacks from a deprecated hook to its replacement.
 *
 * - add_action/add_filter: renamed.
 * - has_action/has_filter with a callback: renamed. Without one it is left alone: WooCommerce always hooks
 *   the new name itself, so has_filter('new') would be true where has_filter('old') was false.
 * - remove_action/remove_filter as a statement: removed from both hooks, because other plugins (or WooCommerce)
 *   may still add the callback on the old one. Used as an expression it is left alone.
 * - Firing a hook (do_action/apply_filters) and remove_all_*() are left alone: they would also reach
 *   WooCommerce's own callbacks on the new hook.
 */
final class RenameHookRector extends AbstractRector implements ConfigurableRectorInterface
{
    private const DONE = 'rector_woocommerce_hook_renamed';

    /**
     * @var array<string, string>
     */
    private array $configuration = [];

    public function getNodeTypes(): array
    {
        return [Expression::class, FuncCall::class];
    }

    /**
     * @param Expression|FuncCall $node
     *
     * @return null|array<Expression>|FuncCall
     */
    public function refactor(Node $node): null|array|FuncCall
    {
        if ($node instanceof Expression) {
            // The kept old-hook removal must not be split again when Rector re-runs the file.
            if ($node->getAttribute(self::DONE) === true || !$node->expr instanceof FuncCall || !$this->isNames($node->expr->name, ['remove_action', 'remove_filter'])) {
                return null;
            }

            $new = $this->newHook($node->expr);

            if ($new === null) {
                return null;
            }

            $renamed                         = clone $node->expr;
            $renamed->args                   = array_map(static fn (Node $arg): Node => clone $arg, $renamed->args);
            $renamed->getArgs()[0]->value    = new String_($new);

            $node->setAttribute(self::DONE, true);

            return [$node, new Expression($renamed)];
        }

        $isAdd = $this->isNames($node->name, ['add_action', 'add_filter']);
        $isHas = $this->isNames($node->name, ['has_action', 'has_filter']) && count($node->getArgs()) >= 2;

        if (!$isAdd && !$isHas) {
            return null;
        }

        $new = $this->newHook($node);

        if ($new === null) {
            return null;
        }

        $node->getArgs()[0]->value = new String_($new);

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
            'Move callbacks from a deprecated hook to its replacement',
            [
                new ConfiguredCodeSample(
                    "add_filter('woocommerce_get_price', 'my_price', 10, 2);",
                    "add_filter('woocommerce_product_get_price', 'my_price', 10, 2);",
                    ['woocommerce_get_price' => 'woocommerce_product_get_price']
                ),
            ]
        );
    }

    private function newHook(FuncCall $funcCall): ?string
    {
        if ($funcCall->isFirstClassCallable()) {
            return null;
        }

        $hook = $funcCall->getArgs()[0] ?? null;

        if ($hook === null || $hook->name !== null || $hook->unpack || !$hook->value instanceof String_) {
            return null;
        }

        return $this->configuration[$hook->value->value] ?? null;
    }
}
