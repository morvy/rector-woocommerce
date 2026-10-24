<?php

namespace Morvy\RectorWooCommerce\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Features;
use Morvy\RectorWooCommerce\PHPStan\NodeVisitor\CompatibilityGuardVisitor;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * Reports use of the Legacy REST API, removed from core in WooCommerce 9.0:
 * its classes, WC()->api (a stub that only keeps get_endpoint_data()), wc-api/v1–v3 URLs and get_woocommerce_api_url().
 * Gateway callbacks (`?wc-api=My_Gateway`, woocommerce_api_{name}) still work and are not reported.
 *
 * @implements Rule<Node>
 */
final readonly class LegacyApiRule implements Rule
{
    private const TIP = 'Move to the WC REST API (/wp-json/wc/v3/, rest_do_request()) or the CRUD functions, or require the woocommerce-legacy-rest-api plugin.';

    public function __construct(
        private FeatureGate $featureGate
    ) {}

    public function getNodeType(): string
    {
        return Node::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->featureGate->isActive(Features::LEGACY_API) || $node->getAttribute(CompatibilityGuardVisitor::LEGACY_API_GUARDED) === true) {
            return [];
        }

        $message = match (true) {
            $node instanceof New_, $node instanceof StaticCall, $node instanceof ClassConstFetch => $this->classUse($node),
            $node instanceof Class_                                                              => $this->classExtends($node),
            $node instanceof MethodCall, $node instanceof PropertyFetch                          => $this->wcApi($node),
            $node instanceof FuncCall                                                            => $this->function($node),
            $node instanceof String_                                                             => preg_match('~/wc-api/v[1-3]\b~', $node->value) === 1 ? 'Legacy REST API URL (wc-api/v1–v3) answers 404 since WooCommerce 9.0.' : null,
            default                                                                              => null,
        };

        if ($message === null) {
            return [];
        }

        return [RuleErrorBuilder::message($message)->identifier('woocommerce.legacyApi')->tip(self::TIP)->build()];
    }

    private function classUse(ClassConstFetch|New_|StaticCall $node): ?string
    {
        if ($node instanceof ClassConstFetch && $node->name instanceof Identifier && $node->name->toLowerString() === 'class') {
            return null;
        }

        return $node->class instanceof Name ? $this->removedClass($node->class) : null;
    }

    private function classExtends(Class_ $class): ?string
    {
        foreach ([$class->extends, ...$class->implements] as $name) {
            if ($name instanceof Name && ($message = $this->removedClass($name)) !== null) {
                return $message;
            }
        }

        return null;
    }

    private function removedClass(Name $name): ?string
    {
        $class = $name->toString();

        foreach (Features::LEGACY_API_CLASSES as $removed) {
            if (strcasecmp($class, $removed) === 0) {
                return sprintf('Class %s was removed from WooCommerce core in 9.0 with the Legacy REST API.', $removed);
            }
        }

        return null;
    }

    /**
     * Anything on WC()->api except get_endpoint_data().
     */
    private function wcApi(MethodCall|PropertyFetch $node): ?string
    {
        $api = $node->var;

        if (!$api instanceof PropertyFetch || !$api->name instanceof Identifier || $api->name->toLowerString() !== 'api') {
            return null;
        }

        $isWc = ($api->var instanceof FuncCall && $api->var->name instanceof Name && $api->var->name->toLowerString() === 'wc') ||
            ($api->var instanceof Variable && $api->var->name === 'woocommerce');

        if (!$isWc || ($node instanceof MethodCall && $node->name instanceof Identifier && $node->name->toLowerString() === 'get_endpoint_data')) {
            return null;
        }

        return 'WC()->api is a stub since WooCommerce 9.0; only get_endpoint_data() is left.';
    }

    private function function(FuncCall $funcCall): ?string
    {
        if (!$funcCall->name instanceof Name) {
            return null;
        }

        $name = $funcCall->name->toLowerString();
        $arg  = $funcCall->getArgs()[0]->value ?? null;

        if ($name === 'get_woocommerce_api_url') {
            return 'get_woocommerce_api_url() builds a Legacy REST API (wc-api/v3) URL, which answers 404 since WooCommerce 9.0.';
        }

        if (in_array($name, ['update_option', 'get_option'], true) && $arg instanceof String_ && $arg->value === 'woocommerce_api_enabled') {
            return "Option 'woocommerce_api_enabled' only reflects whether the Legacy REST API plugin is active since WooCommerce 9.0; writing it has no effect.";
        }

        return null;
    }
}
