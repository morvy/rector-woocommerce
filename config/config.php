<?php

declare(strict_types=1);

use Morvy\RectorWooCommerce\Rules\FuncCall\RenameFunctionWithArgumentsRector;
use Morvy\RectorWooCommerce\Rules\FuncCall\ReturnFirstArgumentRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\RemoveMethodCallRector;
use Morvy\RectorWooCommerce\Rules\MethodCall\ReturnFirstArgumentRector as MethodReturnFirstArgumentRector;
use Morvy\RectorWooCommerce\Rules\PropertyFetch\PropertyFetchToMethodCallRector;
use Morvy\RectorWooCommerce\ValueObject\FunctionRenameWithArguments;
use Morvy\RectorWooCommerce\ValueObject\PropertyFetchToMethodCall;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    /*
     * Register the package's own rules before any set adds Rector's generic renaming rules.
     *
     * Rector runs rules in registration order, so a set that renames a function or method early (e.g. WC 3.0)
     * would otherwise hide the original name from a rule registered by a later set.
     */
    $rectorConfig->ruleWithConfiguration(PropertyFetchToMethodCallRector::class, [
        new PropertyFetchToMethodCall('_imaginary_class', '_imaginary_property_that_should_not_exists', '_imaginary_method'),
    ]);

    $rectorConfig->ruleWithConfiguration(RemoveMethodCallRector::class, [
        '_imaginary_method_that_should_not_exists' => '_imaginary_class',
    ]);

    $rectorConfig->ruleWithConfiguration(MethodReturnFirstArgumentRector::class, [
        '_imaginary_method_that_should_not_exists' => '_imaginary_class',
    ]);

    $rectorConfig->ruleWithConfiguration(ReturnFirstArgumentRector::class, [
        '_imaginary_function_that_should_not_exists',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameFunctionWithArgumentsRector::class, [
        new FunctionRenameWithArguments('_imaginary_function_that_should_not_exists', '_new_imaginary_function_that_should_not_exists', []),
    ]);
};
