<?php

namespace Morvy\RectorWooCommerce\Rules\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\NullsafeMethodCall;
use PhpParser\Node\Identifier;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Renames calls only. Unlike Rector's RenameMethodRector, method declarations in subclasses are left alone,
 * so a user override of the deprecated method never turns into an override of a live WooCommerce method.
 */
final class RenameMethodRector extends AbstractRector implements ConfigurableRectorInterface
{
    use MatchesDeclaringClass;

    /**
     * @var array<MethodCallRename>
     */
    private array $configuration = [];

    public function getNodeTypes(): array
    {
        return [MethodCall::class, NullsafeMethodCall::class];
    }

    /**
     * @param MethodCall|NullsafeMethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        foreach ($this->configuration as $config) {
            if (!$this->isName($node->name, $config->getOldMethod())) {
                continue;
            }

            if (!$this->callsDeclaredMethod($node->var, $config->getClass(), $config->getOldMethod())) {
                continue;
            }

            $node->name = new Identifier($config->getNewMethod());

            return $node;
        }

        return null;
    }

    /**
     * @param array<mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, MethodCallRename::class);

        /** @var array<MethodCallRename> $configuration */
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename a method call, unless the receiver overrides the method',
            [
                new ConfiguredCodeSample(
                    '$product->get_variation_id();',
                    '$product->get_id();',
                    [new MethodCallRename('WC_Abstract_Legacy_Product', 'get_variation_id', 'get_id')]
                ),
            ]
        );
    }
}
