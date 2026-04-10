<?php

declare(strict_types=1);

namespace SaurabhSharma\DLExtractor;

use SaurabhSharma\DLExtractor\Contracts\TransformerInterface;
use SaurabhSharma\DLExtractor\Exceptions\UnsupportedTransformerException;
use SaurabhSharma\DLExtractor\Transformers\PDF417;

class DLExtractor
{
    public static function parse(string $drivingLicense, string $type = 'pdf417', array $options = []): TransformerInterface
    {
        return match (strtolower($type)) {
            'pdf417' => new PDF417($drivingLicense, $options['aliases'] ?? true),
            default => throw new UnsupportedTransformerException(sprintf('Unsupported transformer type "%s".', $type)),
        };
    }
}
