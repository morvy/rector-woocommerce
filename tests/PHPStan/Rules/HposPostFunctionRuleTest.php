<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Tests\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Rules\HposPostFunctionRule;
use Morvy\RectorWooCommerce\Tests\PHPStan\RuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends RuleTestCase<HposPostFunctionRule>
 */
final class HposPostFunctionRuleTest extends RuleTestCase
{
    public function test_findings(): void
    {
        $this->assertFindings([__DIR__ . '/../data/hpos-post-functions.php'], [[7, 'woocommerce.hpos.post'], [8, 'woocommerce.hpos.post'], [9, 'woocommerce.hpos.post'], [10, 'woocommerce.hpos.post'], [11, 'woocommerce.hpos.post'], [12, 'woocommerce.hpos.post'], [13, 'woocommerce.hpos.post'], [14, 'woocommerce.hpos.post'], [81, 'woocommerce.hpos.post'], [86, 'woocommerce.hpos.post']]);
    }

    public function test_nothing_before_the_feature_is_default(): void
    {
        $this->featureGate = new FeatureGate('8.1');

        $this->assertFindings([__DIR__ . '/../data/hpos-post-functions.php'], []);
    }
    private FeatureGate $featureGate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->featureGate = new FeatureGate;
    }

    protected function getRule(): Rule
    {
        return new HposPostFunctionRule($this->featureGate);
    }
}
