<?php

namespace App\Item;

abstract class AbstractItem implements ItemInterface
{
    protected string $name;
    protected string $description;
    protected int $value;
    protected string $type;

    public function __construct(string $name, string $description, int $value)
    {
        $this->name = $name;
        $this->description = $description;
        $this->value = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getType(): string
    {
        return $this->type;
    }
} 