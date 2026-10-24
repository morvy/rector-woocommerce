<?php

namespace Morvy\RectorWooCommerce\Rules\FuncCall;

use PhpParser\Node\Identifier;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

final class ReturnFirstArgumentRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
     * @var array<string>
     */
    private array $configuration;

    public function __construct(
        private readonly SideEffectNodeDetector $sideEffectNodeDetector
    ) {}

    public function getNodeTypes(): array
    {
        return [FuncCall::class];
    }

    /**
     * @param FuncCall $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node->isFirstClassCallable() || count($node->args) === 0) {
            return null;
        }

        foreach ($this->configuration as $function) {
            if (!$this->isName($node->name, $function)) {
                continue;
            }

            $firstArg = $node->args[0];

            if (!$firstArg instanceof Arg || $firstArg->unpack || $firstArg->name instanceof Identifier) {
                return null;
            }

            // The other arguments are dropped, so they must not do anything.
            foreach (array_slice($node->getArgs(), 1) as $droppedArg) {
                if ($this->sideEffectNodeDetector->detect($droppedArg->value)) {
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
        Assert::allString($configuration);

        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace function call directly with its first argument',
            [
                new ConfiguredCodeSample(
                    '$test = my_func($foo);',
                    '$test = $foo;',
                    ['my_func']
                ),
            ]
        );
    }
}
