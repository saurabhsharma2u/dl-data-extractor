# Driving License Data Extractor

![CI](https://github.com/saurabhsharma/dl-data-extractor/actions/workflows/ci.yml/badge.svg)

A small PHP library for extracting AAMVA-style fields from a driver license PDF417 payload.

## Compatibility

- PHP: `^8.2 || ^8.3`
- Tested with Pest 3

## Installation

```bash
composer require saurabhsharma/dl-data-extractor
```

## Usage

```php
<?php

use SaurabhSharma\DLExtractor\DLExtractor;

$payload = 'DCSDOE DACJANE DAQ1234';

// Backward-compatible default: canonical fields + aliases
DLExtractor::parse($payload)->toArray();

// Canonical-only mode (stable output contract)
DLExtractor::parse($payload, 'pdf417', ['aliases' => false])->toArray();

// JSON output
DLExtractor::parse($payload)->toJson();
```

## Supported parser types

- `pdf417`

Unknown types throw `UnsupportedTransformerException`.

## Field model

The parser now builds a canonical field map per code and can optionally emit alias keys for backward compatibility.

## Security & privacy

Driver license payloads may contain PII. Avoid logging raw payloads in production, and redact extracted values in diagnostics.

## Development

```bash
composer test
composer lint
```
