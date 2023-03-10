<?php

use SaurabhSharma\DLExtractor\DLExtractor;


it('return response as array', function () {
    $a = DLExtractor::parse('asd')->toArray();
    expect($a)->toBeArray();
})->skip();

it('return response as json string', function () {
    $a = DLExtractor::parse('asd')->toJson();
    expect($a)->toBeJson();
})->skip();

it('can parse actual PDF417 string to array', function () {
    $a = DLExtractor::parse('@ANSI636000030001DL00310447DLDCADDCBNONEDCDNONEDBA09192025DCSAPARICIOVASQUEZDCTMARIOANTONIODBD11292021DBB09191997DBC1DAYBRODAU072inDAG6903HAMILTONCTDAILORTONDAJVADAK220791213DAQB66150819DCF089686156DCGUSADCHDDDC00000000DDB12102008DDDNDDAFDCK00601017273016	')->toArray();
    print_r($a);
    expect($a)->toBeArray();
});

it('can parse actual PDF417 string', function () {
    $a = DLExtractor::parse('@  ANSI 636014090102DL00410284ZC03250024DLDAQN8046504 DCSSQUIRE DDEN DACKEVIN DDFN DADWAYNE DDGN DCAC DCBNONE DCDNONE DBD07132020 DBB04241963 DBA04242025 DBC1 DAU072 IN DAYBRO DAG4255 DARTMOUTH DR DAIYORBA LINDA DAJCA DAK928860000   DCF07/13/2020542B7/DDFD/25 DCGUSA DAW190 DAZBRO DCK20195N80465040401 DDAF DDB08292017 DDK1 ZCZCABRN ZCBBRN ZCC ZCD')->toJson();
    expect($a)->toBeJson();
})->skip();
