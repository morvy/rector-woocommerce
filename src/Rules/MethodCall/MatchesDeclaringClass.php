<?php

namespace Morvy\RectorWooCommerce\Rules\MethodCall;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PHPStan\Type\ObjectType;
use Rector\DeadCode\SideEffect\SideEffectNodeDetector;

/**
 * For rules that rewrite calls to a deprecated method declared on a WooCommerce class.
 *
 * The call only matches when the method resolves to the configured class's own declaration.
 * A subclass that overrides the method (e.g. a gateway overriding WC_Settings_API::format_settings) keeps its call,
 * because rewriting it would bypass the override.
 */
trait MatchesDeclaringClass
{
    /**
     * Whether dropping the expression loses nothing. `WC()->cart` and `$this->order` are plain lookups.
     */
    private function isSafeToDrop(Expr $expr, SideEffectNodeDetector $sideEffectNodeDetector): bool
    {
        $root = $expr;

        while ($root instanceof PropertyFetch || $root instanceof NullsafePropertyFetch) {
            $root = $root->var;
        }

        if ($root instanceof Variable || ($root instanceof FuncCall && $this->isName($root, 'WC') && $root->args === [])) {
            return true;
        }

        return !$sideEffectNodeDetector->detect($expr);
    }

    private function callsDeclaredMethod(Expr $expr, string $class, string $method): bool
    {
        if (!$this->isObjectType($expr, new ObjectType($class))) {
            return false;
        }

        foreach ($this->getType($expr)->getObjectClassReflections() as $objectClassReflection) {
            // ponytail: a method missing from the class tree (removed upstream, partial stubs) is trusted to be the deprecated one.
            if (!$objectClassReflection->hasNativeMethod($method)) {
                continue;
            }

            $declaringClass = $objectClassReflection->getNativeMethod($method)->getDeclaringClass()->getName();

            if (strcasecmp($declaringClass, $class) !== 0) {
                return false;
            }
        }

        return true;
    }
}
