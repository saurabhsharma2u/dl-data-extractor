<?php

declare(strict_types=1);

namespace SaurabhSharma\DLExtractor\Contracts;

interface TransformerInterface
{
    public function toJson(): string;

    /**
     * @return array<string, string>
     */
    public function toArray(): array;
}
