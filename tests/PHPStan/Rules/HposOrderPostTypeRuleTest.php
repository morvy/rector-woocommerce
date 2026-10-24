<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Tests\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Rules\HposOrderPostTypeRule;
use Morvy\RectorWooCommerce\Tests\PHPStan\RuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends RuleTestCase<HposOrderPostTypeRule>
 */
final class HposOrderPostTypeRuleTest extends RuleTestCase
{
    public function test_findings(): void
    {
        $this->assertFindings([__DIR__ . '/../data/hpos-queries.php'], [[7, 'woocommerce.hpos.postType'], [8, 'woocommerce.hpos.postType'], [9, 'woocommerce.hpos.postType']]);
    }

    public function test_nothing_before_the_feature_is_default(): void
    {
        $this->featureGate = new FeatureGate('8.1');

        $this->assertFindings([__DIR__ . '/../data/hpos-queries.php'], []);
    }
    private FeatureGate $featureGate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->featureGate = new FeatureGate;
    }

    protected function getRule(): Rule
    {
        return new HposOrderPostTypeRule($this->featureGate);
    }
}
