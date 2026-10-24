<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Tests\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\Collectors\CallFactCollector;
use Morvy\RectorWooCommerce\PHPStan\Collectors\ClassFactCollector;
use Morvy\RectorWooCommerce\PHPStan\Collectors\PluginFileCollector;
use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Rules\ProjectCompatibilityRule;
use Morvy\RectorWooCommerce\Tests\PHPStan\RuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends RuleTestCase<ProjectCompatibilityRule>
 */
final class ProjectCompatibilityRuleTest extends RuleTestCase
{
    private FeatureGate $featureGate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->featureGate = new FeatureGate;
    }

    public function test_dirty_plugin(): void
    {
        $this->assertFindings([__DIR__ . '/../data/project-dirty/plugin.php'], [
            [1, 'woocommerce.blocks.declaration'],
            [1, 'woocommerce.hpos.declaration'],
            [7, 'woocommerce.hpos.hook'],
            [8, 'woocommerce.blocks.classicHook'],
            [9, 'woocommerce.legacyApi.hook'],
            [10, 'woocommerce.hpos.hook'],
            [12, 'woocommerce.blocks.gateway'],
        ]);
    }

    public function test_plugin_covering_both_paths(): void
    {
        $this->assertFindings([__DIR__ . '/../data/project-clean/plugin.php'], []);
    }

    public function test_declaration_through_a_wrapper_counts(): void
    {
        $this->assertFindings([__DIR__ . '/../data/project-wrapper/plugin.php'], [
            [14, 'woocommerce.blocks.classicHook'],
        ]);
    }

    public function test_declarations_in_a_loop_count(): void
    {
        $this->assertFindings([__DIR__ . '/../data/project-loop/plugin.php'], []);
    }

    public function test_versions_gate_each_feature(): void
    {
        $this->featureGate = new FeatureGate('8.2');

        $this->assertFindings([__DIR__ . '/../data/project-dirty/plugin.php'], [
            [1, 'woocommerce.hpos.declaration'],
            [7, 'woocommerce.hpos.hook'],
            [10, 'woocommerce.hpos.hook'],
        ]);
    }

    protected function getRule(): Rule
    {
        return new ProjectCompatibilityRule($this->featureGate);
    }

    protected function getCollectors(): array
    {
        return [new PluginFileCollector, new ClassFactCollector, new CallFactCollector];
    }
}
