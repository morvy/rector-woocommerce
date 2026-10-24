<?php

namespace Morvy\RectorWooCommerce\Rules\MethodCall;

use PhpParser\Node\Stmt\Expression;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\NodeVisitor;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class RemoveMethodCallRector extends AbstractRector implements ConfigurableRectorInterface
{
    use MatchesDeclaringClass;

    /**
     * @var array<string, string>
     */
    private array $configuration = [];

    public function __construct(
        private readonly SideEffectNodeDetector $sideEffectNodeDetector
    ) {}

    public function getNodeTypes(): array
    {
        return [Expression::class];
    }

    /**
     * @param Expression $node
     */
    public function refactor(Node $node): ?int
    {
        $expr = $node->expr;

        if (!$expr instanceof MethodCall) {
            return null;
        }

        if ($expr->isFirstClassCallable()) {
            return null;
        }

        foreach ($this->configuration as $method => $class) {
            if (!$this->isName($expr->name, $method)) {
                continue;
            }

            if (!$this->callsDeclaredMethod($expr->var, $class, $method)) {
                continue;
            }

            foreach ([$expr->var, ...array_map(static fn (Arg $arg) => $arg->value, $expr->getArgs())] as $removed) {
                if (!$this->isSafeToDrop($removed, $this->sideEffectNodeDetector)) {
                    return null;
                }
            }

            return NodeVisitor::REMOVE_NODE;
        }

        return null;
    }

    /**
     * @param array<mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allString(array_values($configuration));
        Assert::allString($configuration);

        /** @var array<string, string> $configuration */
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Remove method call from the specified class',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
                        $someObject = new SomeExampleClass;
                        $someObject->someMethod('foo');
                        CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
                        $someObject = new SomeExampleClass;
                        CODE_SAMPLE,
                    ['someMethod' => 'SomeExampleClass']
                ),
            ]
        );
    }
}
