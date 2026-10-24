<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\Tests\PHPStan\Rules;

use Morvy\RectorWooCommerce\PHPStan\FeatureGate;
use Morvy\RectorWooCommerce\PHPStan\Rules\LegacyApiRule;
use Morvy\RectorWooCommerce\Tests\PHPStan\RuleTestCase;
use PHPStan\Rules\Rule;

/**
 * @internal
 *
 * @extends RuleTestCase<LegacyApiRule>
 */
final class LegacyApiRuleTest extends RuleTestCase
{
    public function test_findings(): void
    {
        $this->assertFindings([__DIR__ . '/../data/legacy-api.php'], [[3, 'woocommerce.legacyApi'], [7, 'woocommerce.legacyApi'], [8, 'woocommerce.legacyApi'], [9, 'woocommerce.legacyApi'], [10, 'woocommerce.legacyApi'], [11, 'woocommerce.legacyApi'], [12, 'woocommerce.legacyApi'], [13, 'woocommerce.legacyApi']]);
    }

    public function test_nothing_before_the_feature_is_default(): void
    {
        $this->featureGate = new FeatureGate('8.9');

        $this->assertFindings([__DIR__ . '/../data/legacy-api.php'], []);
    }
    private FeatureGate $featureGate;

    protected function setUp(): void
    {
        parent::setUp();

        $this->featureGate = new FeatureGate;
    }

    protected function getRule(): Rule
    {
        return new LegacyApiRule($this->featureGate);
    }
}
