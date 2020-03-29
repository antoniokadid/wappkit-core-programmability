<?php

namespace AntonioKadid\WAPPKitCore\Programmability;

use AntonioKadid\WAPPKitCore\IO\Exceptions\IOException;

/**
 * Class CodeEvaluator
 *
 * @package AntonioKadid\WAPPKitCore\Programmability
 */
class CodeEvaluator
{
    /**
     * CodeEvaluator constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param string $filename
     *
     * @throws IOException
     */
    public static function fromFile(string $filename): void
    {
        if (!file_exists($filename) || !is_readable($filename))
            throw new IOException(sprintf('Cannot read from "%s".', $filename));

        $code = file_get_contents($filename);
        if ($code === FALSE)
            throw new IOException(sprintf('Cannot get contents of "%s".', $filename));

        self::evaluate($code);
    }

    /**
     * @param string $code
     *
     * @throws IOException
     */
    public static function evaluate(string $code): void
    {
        if (function_exists('eval')) {
            eval($code);
            return;
        }

        $temp = tmpfile();
        if ($temp === FALSE)
            throw new IOException('Unable to create a temporary file.');

        $meta = stream_get_meta_data($temp);
        if (fwrite($temp, $code) === FALSE)
            throw new IOException('Unable to write code in temporary file.');

        // import code using the standard require method.
        require_once $meta['uri'];

        fclose($temp);
    }
}