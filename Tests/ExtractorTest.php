<?php

declare(strict_types=1);

use SaurabhSharma\DLExtractor\DLExtractor;
use SaurabhSharma\DLExtractor\Exceptions\UnsupportedTransformerException;

it('returns response as array', function () {
    $result = DLExtractor::parse('DCSDOE DACJANE DAQ1234')->toArray();

    expect($result)->toBeArray()
        ->and($result)->toHaveKey('Family_Name', 'DOE')
        ->and($result)->toHaveKey('First_Name', 'JANE')
        ->and($result)->toHaveKey('License_or_ID_Number', '1234');
});

it('returns response as json string', function () {
    $result = DLExtractor::parse('DCSDOE DACJANE DAQ1234')->toJson();

    expect($result)->toBeJson();
});

it('can parse actual PDF417 string to array', function () {
    $result = DLExtractor::parse('@ANSI636000030001DL00310447DLDCADDCBNONEDCDNONEDBA09192025DCSAPARICIOVASQUEZDCTMARIOANTONIODBD11292021DBB09191997DBC1DAYBRODAU072inDAG6903HAMILTONCTDAILORTONDAJVADAK220791213DAQB66150819DCF089686156DCGUSADCHDDDC00000000DDB12102008DDDNDDAFDCK00601017273016')->toArray();

    expect($result)
        ->toBeArray()
        ->toHaveKey('Family_Name', 'APARICIOVASQUEZ')
        ->toHaveKey('Given_Name', 'MARIOANTONIO')
        ->toHaveKey('License_or_ID_Number', 'B66150819');
});

it('supports canonical only mode', function () {
    $result = DLExtractor::parse('DCSDOE DACJANE DAQ1234', 'pdf417', ['aliases' => false])->toArray();

    expect($result)
        ->toHaveKey('Family_Name', 'DOE')
        ->toHaveKey('Given_Name', 'JANE')
        ->not->toHaveKey('Last_Name')
        ->not->toHaveKey('First_Name');
});

it('throws for unsupported formats', function () {
    DLExtractor::parse('DCSDOE', 'mrz');
})->throws(UnsupportedTransformerException::class);
})->skip();
