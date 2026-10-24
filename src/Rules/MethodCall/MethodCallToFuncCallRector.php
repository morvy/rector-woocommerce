<?php

namespace Morvy\RectorWooCommerce\Rules\MethodCall;

use PhpParser\Node;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Name\FullyQualified;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Rector\Rector\AbstractRector;
use Rector\Transform\ValueObject\MethodCallToFuncCall;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Like Rector's MethodCallToFuncCallRector, but skips overridden methods and receivers with side effects,
 * since the receiver expression is dropped.
 */
final class MethodCallToFuncCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    use MatchesDeclaringClass;

    /**
     * @var array<MethodCallToFuncCall>
     */
    private array $configuration = [];

    public function __construct(
        private readonly SideEffectNodeDetector $sideEffectNodeDetector
    ) {}

    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        foreach ($this->configuration as $config) {
            if (!$this->isName($node->name, $config->getMethodName())) {
                continue;
            }

            if (!$this->callsDeclaredMethod($node->var, $config->getObjectType(), $config->getMethodName())) {
                continue;
            }

            if (!$this->isSafeToDrop($node->var, $this->sideEffectNodeDetector)) {
                return null;
            }

            return new FuncCall(new FullyQualified($config->getFunctionName()), $node->args);
        }

        return null;
    }

    /**
     * @param array<mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, MethodCallToFuncCall::class);

        /** @var array<MethodCallToFuncCall> $configuration */
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace a method call with a function call, unless the receiver overrides the method',
            [
                new ConfiguredCodeSample(
                    '$url = $cart->get_cart_url();',
                    '$url = \wc_get_cart_url();',
                    [new MethodCallToFuncCall('WC_Legacy_Cart', 'get_cart_url', 'wc_get_cart_url')]
                ),
            ]
        );
    }
}
