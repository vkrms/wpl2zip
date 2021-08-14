<?php
require __DIR__ . '/vendor/autoload.php';

use Vkrms\Wpl2zip\Parser;

// $folder   = $argv[2];
// get options
$optind;
$options = getopt('izo', ['input','zip','output'], $optind);

$playlistName = $argv[$optind];


// flip specified options
// array_walk($options, function (&$option) {
//     $option = (is_bool($option)) ? !$option : $option;
// });

// TODO: add flag for full output path
if (isset($options['o']) || isset($options['output'])) {
    $folder = $argv[$optind + 1];
} else {
    $folder = 'D:\sets\\' . $playlistName;
}

// With -i or --input use full playlist path
if (isset($options['i']) || isset($options['input'])) {
    $playlistPath = $argv[$optind];

    // oops, not a file
    if (!is_file($playlistPath)) {
        var_dump($argv);

        throw new \Exception("Input is not a file: " . $playlistPath, 1);
    }

    // oops, no extension
    if (false === strpos($playlistPath, '.wpl')) {
        throw new \Exception("Input file has no .wpl extension", 1);
    }
} else {
    $playlistPath = 'G:\Music\Playlists\\' . $playlistName . '.wpl';
}

$parser = new Parser($playlistPath);


// create folder
if (!is_dir($folder)) {
    mkdir($folder, 0755, 'recursive');
} else {
// remove existing files
    $files = glob($folder);
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
}


function getFilename($index, $path)
{
    $pathChunks = explode('\\', $path);
    $leadingNumber =  str_pad($index, 2, 0, STR_PAD_LEFT);
    return $leadingNumber . ' - ' . array_pop($pathChunks);
}

// make zip
if (array_search('--zip', $argv) || array_search('-z', $argv)) {
    $zipname = $folder . '.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);

    foreach ($parser->paths as $index => $path) {
        $filename = getFilename($index, $path);
        $zip->addFile($path, $filename);
    }

    echo $zip->close() ? 'Saved as ' . $zipname : 'Not saved...';
    return;
}

// copy files
foreach ($parser->paths as $index => $path) {
    $filename = getFilename($index, $path);
    echo copy($path, $folder.'\\'.$filename)
        ? "$path copied \r\n"
        // escaped thingies are windows console color codes
        : "\033[31m copy failed on $path \033[37m\r\n";
}

// open folder
exec("EXPLORER /E,$folder");
