<?php

namespace Morvy\RectorWooCommerce\PHPStan\Collectors;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Collectors\Collector;
use PHPStan\Node\FileNode;

/**
 * Collects files with a plugin header as ['plugin', 'wc'|'plain', 1, false]; 'wc' when the header has the WC headers
 * WooCommerce evaluates feature compatibility for.
 *
 * @implements Collector<FileNode, array{0: string, 1: string, 2: int, 3: bool}>
 */
final class PluginFileCollector implements Collector
{
    public function getNodeType(): string
    {
        return FileNode::class;
    }

    public function processNode(Node $node, Scope $scope): ?array
    {
        $head = (string) @file_get_contents($scope->getFile(), false, null, 0, 8192);

        if (preg_match('/^[ \t\/*#@]*Plugin Name:/mi', $head) !== 1) {
            return null;
        }

        return ['plugin', preg_match('/^[ \t\/*#@]*WC (tested up to|requires at least):/mi', $head) === 1 ? 'wc' : 'plain', 1, false];
    }
}
