<?php

namespace Morvy\RectorWooCommerce\Rules\StaticCall;

use Morvy\RectorWooCommerce\Rules\MethodCall\MatchesDeclaringClass;
use Morvy\RectorWooCommerce\ValueObject\RenameStaticMethod;
use PhpParser\Node;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use Webmozart\Assert\Assert;

/**
 * Renames static calls, moving them to the replacement class when it differs.
 * Replaces Rector's RenameStaticMethodRector, which Rector 2.8 deprecated in favour of project rules.
 * Calls through a subclass that overrides the method are left alone.
 */
final class RenameStaticMethodRector extends AbstractRector implements ConfigurableRectorInterface
{
    use MatchesDeclaringClass;

    /**
     * @var array<RenameStaticMethod>
     */
    private array $configuration = [];

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    /**
     * @param StaticCall $node
     */
    public function refactor(Node $node): ?StaticCall
    {
        // static:: may resolve to a subclass further down that overrides the method; only a final class rules that out.
        if ($node->class instanceof Name && $this->isName($node->class, 'static') && !$this->isFinal($node->class)) {
            return null;
        }

        foreach ($this->configuration as $config) {
            if (!$this->isName($node->name, $config->getMethod())) {
                continue;
            }

            if (!$this->callsDeclaredMethod($node->class, $config->getClass(), $config->getMethod())) {
                continue;
            }

            $moves = strcasecmp($config->getClass(), $config->getNewClass()) !== 0;

            // $object::method() keeps its receiver, so it can only be renamed in place.
            if (!$node->class instanceof Name && $moves) {
                return null;
            }

            if ($moves) {
                $node->class = new FullyQualified($config->getNewClass());
            }

            $node->name = new Identifier($config->getNewMethod());

            return $node;
        }

        return null;
    }

    private function isFinal(Name $name): bool
    {
        $classReflections = $this->getType($name)->getObjectClassReflections();

        foreach ($classReflections as $classReflection) {
            if (!$classReflection->isFinal()) {
                return false;
            }
        }

        return $classReflections !== [];
    }

    /**
     * @param array<mixed> $configuration
     */
    public function configure(array $configuration): void
    {
        Assert::allIsInstanceOf($configuration, RenameStaticMethod::class);

        /** @var array<RenameStaticMethod> $configuration */
        $this->configuration = $configuration;
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename a static method call, moving it to the replacement class',
            [
                new ConfiguredCodeSample(
                    'WC_Gateway_Paypal_Helper::redact_data($data);',
                    '\Automattic\WooCommerce\Gateways\PayPal\Helper::redact_data($data);',
                    [new RenameStaticMethod('WC_Gateway_Paypal_Helper', 'redact_data', 'Automattic\WooCommerce\Gateways\PayPal\Helper', 'redact_data')]
                ),
            ]
        );
    }
}
