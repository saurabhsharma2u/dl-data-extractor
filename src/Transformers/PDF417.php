<?php

namespace SaurabhSharma\DLExtractor\Transformers;

use SaurabhSharma\DLExtractor\Attributes\PDF417 as AttributesPDF417;

class PDF417 extends AttributesPDF417
{
    public string $pdf417;
    public function __construct(string $pdf417)
    {
        $this->pdf417 = $pdf417;
    }
    public function toJson(): string
    {
        return json_encode($this->extract($this->pdf417));
    }

    public function toArray(): array
    {
        return $this->extract($this->pdf417);
    }
    public function extract(): array
    {
        $pdf417Data = [];
        foreach ($this->Keys as $key => $item) {
            $this->pdf417 = str_replace($item['abbreviation'], ' ' . $item['abbreviation'], $this->pdf417);
        }
        $dl_string = str_replace('  ', ' ', $this->pdf417);
        foreach ($this->Keys as $key => $item) {
            $value = $this->getField(dl_string: $this->pdf417, keyword: $item['abbreviation']);
            if (!empty(trim($value))) {
                $pdf417Data[$item['description']]   = $value;
            }
        }
        return $pdf417Data;
    }
    public function getField(string $dl_string, string $keyword): bool|string
    {
        $k = strpos($dl_string, " " . $keyword);
        if ($k === false) {
            return false;
        }
        $m = strpos($dl_string, " ", $k + 1);
        return substr($dl_string, ($k + 4), ($m - ($k + 4)));
    }
}
