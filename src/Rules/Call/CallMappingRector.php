<?php

namespace Morvy\RectorWooCommerce\Rules\Call;

use Morvy\RectorWooCommerce\Rules\MethodCall\MatchesDeclaringClass;
use Morvy\RectorWooCommerce\ValueObject\CallMapping;
use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Replaces a deprecated call with its replacement when the deprecated body only re-orders, drops or adds arguments.
 *
 * Skipped when an argument or receiver that would be dropped has side effects, when arguments are named or unpacked,
 * and when a picked argument is missing while a later one is present (the new call would shift them).
 */
final class CallMappingRector extends AbstractRector implements ConfigurableRectorInterface
{
    use MatchesDeclaringClass;

    /**
     * @var array<CallMapping>
     */
    private array $configuration = [];

    public function __construct(
        private readonly SideEffectNodeDetector $sideEffectNodeDetector
    ) {}

    public function getNodeTypes(): array
    {
        return [FuncCall::class, MethodCall::class, StaticCall::class];
    }

    /**
     * @param FuncCall|MethodCall|StaticCall $node
     */
    public function refactor(Node $node): null|FuncCall|StaticCall
    {
        if ($node->isFirstClassCallable()) {
            return null;
        }

        foreach ($this->configuration as $config) {
            if (!$this->matches($node, $config)) {
                continue;
            }

            if ($node instanceof MethodCall && !$this->isSafeToDrop($node->var, $this->sideEffectNodeDetector)) {
                return null;
            }

            $args = $this->mapArgs($node->getArgs(), $config->getArguments());

            if ($args === null) {
                return null;
            }

            return $config->getNewClass() === null
                ? new FuncCall(new FullyQualified($config->getNewName()), $args)
                : new StaticCall(new FullyQualified($config->getNewClass()), $config->getNewName(), $args);
        }

        return null;
    }

    /**
     * @param array<mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, CallMapping::class);

        /** @var array<CallMapping> $configuration */
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace a deprecated call with its replacement, mapping the arguments',
            [
                new ConfiguredCodeSample(
                    '$items = woocommerce_nav_menu_items($items, $args);',
                    '$items = \wc_nav_menu_items($items);',
                    [new CallMapping(null, 'woocommerce_nav_menu_items', null, 'wc_nav_menu_items', [0])]
                ),
            ]
        );
    }

    private function matches(FuncCall|MethodCall|StaticCall $node, CallMapping $callMapping): bool
    {
        $class = $callMapping->getClass();

        if ($node instanceof FuncCall) {
            return $class === null && $node->name instanceof Name && $this->isName($node->name, $callMapping->getName());
        }

        if ($class === null || !$this->isName($node->name, $callMapping->getName())) {
            return false;
        }

        if ($node instanceof StaticCall) {
            return $node->class instanceof Name && $this->isName($node->class, $class);
        }

        return $this->callsDeclaredMethod($node->var, $class, $callMapping->getName());
    }

    /**
     * @param array<Arg>                                                        $oldArgs
     * @param list<array{literal: null|array<mixed>|bool|float|int|string}|int> $mapping
     *
     * @return null|array<Arg>
     */
    private function mapArgs(array $oldArgs, array $mapping): ?array
    {
        foreach ($oldArgs as $oldArg) {
            if ($oldArg->unpack || $oldArg->name !== null) {
                return null;
            }
        }

        $picked = array_filter($mapping, is_int(...));

        foreach ($oldArgs as $position => $oldArg) {
            if (!in_array($position, $picked, true) && $this->sideEffectNodeDetector->detect($oldArg->value)) {
                return null;
            }
        }

        $args    = [];
        $missing = false;

        foreach ($mapping as $item) {
            if (is_int($item) && !isset($oldArgs[$item])) {
                // Trailing optional arguments may be left out; the replacement's defaults match the old ones.
                $missing = true;

                continue;
            }

            if ($missing) {
                return null;
            }

            $args[] = is_int($item) ? $oldArgs[$item] : new Arg(BuilderHelpers::normalizeValue($item['literal']));
        }

        return $args;
    }
}
