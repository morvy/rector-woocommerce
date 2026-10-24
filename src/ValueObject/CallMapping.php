<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\ValueObject;

/**
 * Maps a deprecated function or method call onto its replacement with a different argument list.
 *
 * $arguments lists the new call's arguments in order: an int picks the old call's argument at that position,
 * ['literal' => value] inserts a constant. Old arguments not picked are dropped.
 */
final readonly class CallMapping
{
    /**
     * @param list<array{literal: null|array<mixed>|bool|float|int|string}|int> $arguments
     */
    public function __construct(
        private ?string $class,
        private string $name,
        private ?string $newClass,
        private string $newName,
        private array $arguments
    ) {}

    /**
     * The declaring class of a deprecated method, or null for a function.
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * The class of the replacement static method, or null for a function.
     */
    public function getNewClass(): ?string
    {
        return $this->newClass;
    }

    public function getNewName(): string
    {
        return $this->newName;
    }

    /**
     * @return list<array{literal: null|array<mixed>|bool|float|int|string}|int>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
