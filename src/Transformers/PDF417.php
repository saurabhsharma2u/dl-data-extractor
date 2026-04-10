<?php

declare(strict_types=1);

namespace SaurabhSharma\DLExtractor\Transformers;

use SaurabhSharma\DLExtractor\Attributes\PDF417 as AttributesPDF417;
use SaurabhSharma\DLExtractor\Contracts\TransformerInterface;

class PDF417 extends AttributesPDF417 implements TransformerInterface
{
    public function __construct(
        public string $pdf417,
        private bool $includeAliases = true
    ) {
    }

    public function toJson(): string
    {
        $json = json_encode($this->extract(), JSON_UNESCAPED_UNICODE);

        return $json === false ? '[]' : $json;
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->extract();
    }

    /**
     * @return array<string, string>
     */
    public function extract(): array
    {
        $data = [];
        $canonical = $this->canonicalMap();
        $aliases = $this->aliasMap();

        foreach ($this->tokenize($this->pdf417) as $code => $value) {
            if (! isset($canonical[$code]) || $value === '') {
                continue;
            }

            $data[$canonical[$code]] = $value;

            if (! $this->includeAliases || ! isset($aliases[$code])) {
                continue;
            }

            foreach ($aliases[$code] as $aliasKey) {
                $data[$aliasKey] = $value;
            }
        }

        return $data;
    }

    /**
     * @return array<string, string>
     */
    private function tokenize(string $payload): array
    {
        $normalized = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $payload) ?? $payload;
        $codes = array_keys($this->canonicalMap());

        if ($codes === []) {
            return [];
        }

        $pattern = '/(' . implode('|', array_map('preg_quote', $codes)) . ')(.*?)(?=(' . implode('|', array_map('preg_quote', $codes)) . ')|$)/s';

        if (! preg_match_all($pattern, $normalized, $matches, PREG_SET_ORDER)) {
            return [];
        }

        $tokens = [];

        foreach ($matches as $match) {
            $code = $match[1];
            $value = trim($match[2]);

            if ($value !== '') {
                $tokens[$code] = $value;
            }
        }

        return $tokens;
    }
}
