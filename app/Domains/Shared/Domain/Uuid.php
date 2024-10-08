<?php

namespace App\Domains\Shared\Domain;

use Illuminate\Support\Str;

abstract class Uuid
{
    use Assertions;

    protected string $uuid;

    public function __construct(string $uuid)
    {
        $this->setUuid($uuid);
    }

    public static function random(): static
    {
        return new static(Str::uuid());
    }

    protected function setUuid(string $uuid): void
    {
        $this->assertArgumentNotEmpty($uuid, 'Uuid can not be empty');
        $this->assertIsUuid($uuid);
        $this->uuid = $uuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function toString(): string
    {
        return $this->uuid;
    }
}