<?php

declare(strict_types=1);

namespace Morvy\RectorWooCommerce\ValueObject;

/**
 * Maps a deprecated function or method call onto its replacement with a different argument list.
 *
 * $arguments lists the new call's arguments in order. Each item is one of:
 * - int: the old call's argument at that position (may be left out at the end when the caller omitted it)
 * - ['param' => int, 'default' => value]: that argument, or the default when the caller omitted it
 * - ['literal' => value]: a constant
 * - ['receiver' => true] / ['receiverCall' => 'get_id']: the old method call's object, or a method call on it
 * - ['array' => ['key' => item, ...]]: an array literal built from items
 * Old arguments not used are dropped.
 */
final readonly class CallMapping
{
    /**
     * @param list<array<string, mixed>|int> $arguments
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
     * @return list<array<string, mixed>|int>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}
