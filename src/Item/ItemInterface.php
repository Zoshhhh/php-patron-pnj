<?php

namespace App\Item;

interface ItemInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function getType(): string;
    public function getValue(): int;
} 