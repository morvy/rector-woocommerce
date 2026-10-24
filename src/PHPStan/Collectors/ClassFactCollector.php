<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\PHPStan\Collectors;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\InClassNode;

/**
 * Collects payment gateways and Cart/Checkout blocks integrations as ['gateway'|'blockPayment'|'blockSupport', class, line, false].
 *
 * @implements Collector<InClassNode, array{0: string, 1: string, 2: int, 3: bool}>
 */
final class ClassFactCollector implements Collector
{
    public function getNodeType(): string
    {
        return InClassNode::class;
    }

    public function processNode(Node $node, Scope $scope): ?array
    {
        $classReflection = $node->getClassReflection();

        if ($classReflection->isAbstract() || $classReflection->isInterface()) {
            return null;
        }

        if ($classReflection->isSubclassOf('WC_Payment_Gateway')) {
            return ['gateway', $classReflection->getName(), $node->getStartLine(), false];
        }

        if ($classReflection->isSubclassOf('Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType') || $classReflection->implementsInterface('Automattic\WooCommerce\Blocks\Payments\PaymentMethodTypeInterface')) {
            return ['blockPayment', $classReflection->getName(), $node->getStartLine(), false];
        }

        if ($classReflection->implementsInterface('Automattic\WooCommerce\Blocks\Integrations\IntegrationInterface')) {
            return ['blockSupport', $classReflection->getName(), $node->getStartLine(), false];
        }

        return null;
    }
}
