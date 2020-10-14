<?php
namespace Vkrms\Wpl2zip;

use Laravie\Parser\Xml\Reader;
use Laravie\Parser\Xml\Document;

class Parser
{
    public function __construct(string $inputPath, string $outputFolder)
    {
        // create folder
        if (!is_dir($outputFolder)) {
            mkdir($outputFolder, 0755, 'recursive');
        }

        // parse wpl
        $wpl = (new Reader(new Document()))->load($inputPath);
        $parsed = $wpl->parse([
            'media' => ['uses' => 'body.seq.media[::src>src]'],
        ]);

        foreach ($parsed['media'] as $media) {
            $path = str_replace('..\\', 'G:\Music\\', $media['src']);
            $paths[] = $path;
            $pathChunks = explode('\\', $path);
            $filename = array_pop($pathChunks);
            echo copy($path, $outputFolder.'\\'.$filename) ? "$path copied \r\n" : "\033[31m copy failed on $path \033[37\r\n";
        }

        return;
    }
}
