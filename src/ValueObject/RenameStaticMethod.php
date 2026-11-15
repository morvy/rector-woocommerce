<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\ValueObject;

final readonly class RenameStaticMethod
{
    public function __construct(
        private string $class,
        private string $method,
        private string $newClass,
        private string $newMethod
    ) {}

    public function getClass(): string
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getNewClass(): string
    {
        return $this->newClass;
    }

    public function getNewMethod(): string
    {
        return $this->newMethod;
    }
}
