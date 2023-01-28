# PDF417 Driving License Data Extractor

A simple class to return Driving License information, such as Driver First Name, Last Name, License Number etc.

## Installation

Using composer:

```bash
composer require saurabhsharma/dl-data-extractor
```

You are then free to use it as needed within your projects.

## Usage

```php
<?php

use SaurabhSharma\DLExtractor\DLExtractor;

DLExtractor::parse('pdf417-bar-code')->toArray(); // get Response in Array

DLExtractor::parse('pdf417-bar-code')->toJson(); // get Response in Json String

```
