<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Tests\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Rules\FeatureMiscRule;
use Morvy\RectorWooCommerce\Tests\PHPStan\RuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends RuleTestCase<FeatureMiscRule>
 */
final class FeatureMiscRuleTest extends RuleTestCase
{
    public function test_findings(): void
    {
        $this->assertFindings([__DIR__ . '/../data/feature-misc.php'], [[5, 'woocommerce.brands'], [11, 'woocommerce.brands'], [15, 'woocommerce.blocks.shortcode'], [16, 'woocommerce.blocks.shortcode'], [17, 'woocommerce.productBlockEditor'], [18, 'woocommerce.productBlockEditor']]);
    }

    public function test_nothing_before_the_feature_is_default(): void
    {
        $this->featureGate = new FeatureGate('8.2');

        $this->assertFindings([__DIR__ . '/../data/feature-misc.php'], []);
    }
    private FeatureGate $featureGate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->featureGate = new FeatureGate;
    }

    protected function getRule(): Rule
    {
        return new FeatureMiscRule($this->featureGate);
    }
}
