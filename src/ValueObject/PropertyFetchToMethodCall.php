<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\ValueObject;

final readonly class PropertyFetchToMethodCall
{
    public function __construct(
        private string $class,
        private string $property,
        private string $method
    ) {}

    public function getClass(): string
    {
        return $this->class;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
