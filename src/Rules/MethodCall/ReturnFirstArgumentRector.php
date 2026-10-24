<?php

namespace Morvy\RectorWooCommerce\Rules\MethodCall;

use PhpParser\Node\Identifier;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ReturnFirstArgumentRector extends AbstractRector implements ConfigurableRectorInterface
{
    use MatchesDeclaringClass;

    /**
     * @var array<string, string>
     */
    private array $configuration;

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
        if ($node->isFirstClassCallable() || count($node->args) === 0) {
            return null;
        }

        foreach ($this->configuration as $method => $class) {
            if (!$this->isName($node->name, $method)) {
                continue;
            }

            if (!$this->callsDeclaredMethod($node->var, $class, $method)) {
                continue;
            }

            $firstArg = $node->args[0];

            if (!$firstArg instanceof Arg || $firstArg->unpack || $firstArg->name instanceof Identifier) {
                return null;
            }

            // The receiver and the other arguments are dropped, so they must not do anything.
            foreach ([$node->var, ...array_map(static fn (Arg $arg) => $arg->value, array_slice($node->getArgs(), 1))] as $dropped) {
                if (!$this->isSafeToDrop($dropped, $this->sideEffectNodeDetector)) {
                    return null;
                }
            }

            return $firstArg->value;
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
            'Replace method call directly with its first argument',
            [
                new ConfiguredCodeSample(
                    <<<'CODE_SAMPLE'
                        $someObject = new SomeExampleClass;
                        $foo = $someObject->someMethod('foo');
                        CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
                        $someObject = new SomeExampleClass;
                        $foo = 'foo';
                        CODE_SAMPLE,
                    ['someMethod' => 'SomeExampleClass']
                ),
            ]
        );
    }
}
