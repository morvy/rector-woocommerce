<?php

namespace Morvy\RectorWooCommerce\Rules\FuncCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Expression;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * For deprecated wrappers that return nothing while their replacement returns a value:
 * only a call whose result is discarded is renamed, so `if (woocommerce_mail(...))` keeps its meaning.
 */
final class RenameFunctionAsStatementRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<string, string>
     */
    private array $configuration = [];

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?Node
    {
        if (!$node->expr instanceof FuncCall) {
            return null;
        }

        foreach ($this->configuration as $old => $new) {
            if (!$this->isName($node->expr->name, $old)) {
                continue;
            }

            $node->expr->name = new FullyQualified($new);

            return $node;
        }

        return null;
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
            'Rename a function call only when its return value is not used',
            [
                new ConfiguredCodeSample(
                    'woocommerce_mail($to, $subject, $message);',
                    '\wc_mail($to, $subject, $message);',
                    ['woocommerce_mail' => 'wc_mail']
                ),
            ]
        );
    }
}
