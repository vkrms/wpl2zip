<?php
namespace Vkrms\Wpl2zip;

use Laravie\Parser\Xml\Reader;
use Laravie\Parser\Xml\Document;

class Parser
{
    public $paths = [];

    public function __construct(string $inputPath)
    {
        try {
            // parse wpl
            $wpl = (new Reader(new Document()))->load($inputPath);

            $parsed = $wpl->parse([
                'media' => ['uses' => 'body.seq.media[::src>src]'],
            ]);

        // something went wrong
        } catch (\Laravie\Parser\InvalidContentException $e) {
            echo "\033[31mInput path: " . $inputPath . "\033[37m\r\n";
            throw $e;
        }

        foreach ($parsed['media'] as $media) {
            $path = str_replace('..\\', 'G:\Music\\', $media['src']);
            $this->paths[] = $path;
        }

        return;
    }
}
