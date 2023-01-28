<?php

declare(strict_types=1);

namespace SaurabhSharma\DLExtractor;

use SaurabhSharma\DLExtractor\Transformers\PDF417;

class DLExtractor
{
    public string $DLString;
    public static function parse(string $driving_license, string $type = 'pdf417'): PDF417
    {
        return new PDF417($driving_license);
    }
}
