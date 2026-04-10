<?php

declare(strict_types=1);

use SaurabhSharma\DLExtractor\DLExtractor;
use SaurabhSharma\DLExtractor\Exceptions\UnsupportedTransformerException;

it('returns response as array', function () {
    $result = DLExtractor::parse('DCSDOE DACJANE DAQ1234')->toArray();

    expect($result)->toBeArray()
        ->toHaveKey('Family_Name')
        ->toHaveKey('First_Name')
        ->toHaveKey('License_or_ID_Number')
        ->and($result['Family_Name'])->toBe('DOE')
        ->and($result['First_Name'])->toBe('JANE')
        ->and($result['License_or_ID_Number'])->toBe('1234');
});

it('returns response as json string', function () {
    $result = DLExtractor::parse('DCSDOE DACJANE DAQ1234')->toJson();

    expect($result)->toBeJson();
});

it('can parse actual PDF417 string to array', function () {
    $result = DLExtractor::parse('@ANSI636000030001DL00310447DLDCADDCBNONEDCDNONEDBA09192025DCSAPARICIOVASQUEZDCTMARIOANTONIODBD11292021DBB09191997DBC1DAYBRODAU072inDAG6903HAMILTONCTDAILORTONDAJVADAK220791213DAQB66150819DCF089686156DCGUSADCHDDDC00000000DDB12102008DDDNDDAFDCK00601017273016')->toArray();

    expect($result)
        ->toBeArray()
        ->toHaveKey('Family_Name')
        ->toHaveKey('Given_Name')
        ->toHaveKey('License_or_ID_Number')
        ->and($result['Family_Name'])->toBe('APARICIOVASQUEZ')
        ->and($result['Given_Name'])->toBe('MARIOANTONIO')
        ->and($result['License_or_ID_Number'])->toBe('B66150819');
});

it('supports canonical only mode', function () {
    $result = DLExtractor::parse('DCSDOE DACJANE DAQ1234', 'pdf417', ['aliases' => false])->toArray();

    expect($result)
        ->toHaveKey('Family_Name')
        ->toHaveKey('Given_Name')
        ->not->toHaveKey('Last_Name')
        ->not->toHaveKey('First_Name')
        ->and($result['Family_Name'])->toBe('DOE')
        ->and($result['Given_Name'])->toBe('JANE');
});

it('throws for unsupported formats', function () {
    DLExtractor::parse('DCSDOE', 'mrz');
})->throws(UnsupportedTransformerException::class);
})->skip();
