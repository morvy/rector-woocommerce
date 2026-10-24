<?php

namespace Morvy\RectorWooCommerce\Rules\Call;

use Morvy\RectorWooCommerce\Rules\MethodCall\MatchesDeclaringClass;
use Morvy\RectorWooCommerce\ValueObject\CallMapping;
use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\ArrayItem;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Replaces a deprecated call with its replacement when the deprecated body only re-orders, drops or adds arguments.
 *
 * Skipped when an argument or receiver that would be dropped (or re-ordered) has side effects, when arguments are
 * named or unpacked, and when a picked argument is missing while a later one is present (the new call would shift them).
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

            $args = $this->buildArgs($node, $config->getArguments());

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
     * @param list<array<string, mixed>|int> $mapping
     *
     * @return null|array<Arg>
     */
    private function buildArgs(FuncCall|MethodCall|StaticCall $node, array $mapping): ?array
    {
        $oldArgs = $node->getArgs();

        foreach ($oldArgs as $oldArg) {
            if ($oldArg->unpack || $oldArg->name !== null) {
                return null;
            }
        }

        $receiver = $node instanceof MethodCall ? $node->var : null;
        // Old positions in the order the new call evaluates them; -1 is the receiver.
        $order   = [];
        $args    = [];
        $missing = false;

        foreach ($mapping as $item) {
            $expr = $this->buildExpr($item, $oldArgs, $receiver, $order);

            if ($expr === null) {
                return null;
            }

            if ($expr === false) {
                // Trailing optional arguments may be left out; the replacement's defaults match the old ones.
                $missing = true;

                continue;
            }

            if ($missing) {
                return null;
            }

            $args[] = new Arg($expr);
        }

        // Every old expression is used at most once, so no node is shared or evaluated twice.
        if (count($order) !== count(array_unique($order))) {
            return null;
        }

        foreach ($oldArgs as $position => $oldArg) {
            if (!in_array($position, $order, true) && $this->sideEffectNodeDetector->detect($oldArg->value)) {
                return null;
            }
        }

        if ($receiver instanceof Expr && !in_array(-1, $order, true) && !$this->isSafeToDrop($receiver, $this->sideEffectNodeDetector)) {
            return null;
        }

        $sorted = $order;
        sort($sorted);

        // Re-ordered expressions must not depend on evaluation order.
        if ($sorted !== $order) {
            foreach ($order as $position) {
                $expr = $position === -1 ? $receiver : $oldArgs[$position]->value;

                if ($expr instanceof Expr && !$this->isSafeToDrop($expr, $this->sideEffectNodeDetector)) {
                    return null;
                }
            }
        }

        return $args;
    }

    /**
     * @param array<mixed>|int $item
     * @param array<Arg>       $oldArgs
     * @param list<int>        $order
     *
     * @return null|Expr|false false when an optional old argument is missing, null when the call cannot be mapped
     */
    private function buildExpr(array|int $item, array $oldArgs, ?Expr $receiver, array &$order): null|Expr|false
    {
        if (is_int($item) || isset($item['param'])) {
            $position = is_int($item) ? $item : $item['param'];

            if (!is_int($position)) {
                return null;
            }

            if (isset($oldArgs[$position])) {
                $order[] = $position;

                return $oldArgs[$position]->value;
            }

            if (is_int($item)) {
                return false;
            }

            return $this->literal($item['default'] ?? null);
        }

        if (array_key_exists('literal', $item)) {
            return $this->literal($item['literal']);
        }

        if (isset($item['receiver']) || isset($item['receiverCall'])) {
            if (!$receiver instanceof Expr) {
                return null;
            }

            $order[] = -1;

            return is_string($item['receiverCall'] ?? null) ? new MethodCall($receiver, $item['receiverCall']) : $receiver;
        }

        if (isset($item['array']) && is_array($item['array'])) {
            $items = [];

            foreach ($item['array'] as $key => $value) {
                $expr = is_int($value) || is_array($value) ? $this->buildExpr($value, $oldArgs, $receiver, $order) : null;

                if (!$expr instanceof Expr) {
                    return null;
                }

                $items[] = new ArrayItem($expr, is_string($key) ? new String_($key) : null);
            }

            return new Array_($items);
        }

        return null;
    }

    private function literal(mixed $value): ?Expr
    {
        return $value === null || is_scalar($value) || is_array($value) ? BuilderHelpers::normalizeValue($value) : null;
    }
}
