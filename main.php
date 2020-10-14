<?php
require __DIR__ . '/vendor/autoload.php';

use Vkrms\Wpl2zip\Parser;

$playlist = $argv[1];
$folder   = $argv[2];

new Parser($playlist, $folder);
