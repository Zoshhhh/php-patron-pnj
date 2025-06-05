<?php

namespace App\Interface;

interface ItemInterface
{
    public function getNom(): string;
    public function getDescription(): string;
    public function getType(): string;
    public function getRarete(): string;
    public function getPoids(): float;
    public function getValeur(): int;
    public function getEffets(): array;
} 