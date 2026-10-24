<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Tests\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Rules\HposScreenRule;
use Morvy\RectorWooCommerce\Tests\PHPStan\RuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends RuleTestCase<HposScreenRule>
 */
final class HposScreenRuleTest extends RuleTestCase
{
    public function test_findings(): void
    {
        $this->assertFindings([__DIR__ . '/../data/hpos-screens.php'], [[9, 'woocommerce.hpos.screen'], [12, 'woocommerce.hpos.screen'], [15, 'woocommerce.hpos.postType'], [18, 'woocommerce.hpos.screen'], [21, 'woocommerce.hpos.metaBox'], [22, 'woocommerce.hpos.metaBox'], [54, 'woocommerce.hpos.postType']]);
    }

    public function test_nothing_before_the_feature_is_default(): void
    {
        $this->featureGate = new FeatureGate('8.1');

        $this->assertFindings([__DIR__ . '/../data/hpos-screens.php'], []);
    }
    private FeatureGate $featureGate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->featureGate = new FeatureGate;
    }

    protected function getRule(): Rule
    {
        return new HposScreenRule($this->featureGate);
    }
}
