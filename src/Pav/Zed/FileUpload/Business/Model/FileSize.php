<?php

namespace Pav\Zed\FileUpload\Business\Model;

class FileSize
{

    /**
     * @var array
     */
    protected static $sizes = ['B', 'kB', 'MB', 'GB', 'TB'];

    /**
     * @param int $bytes
     * @param int $decimals
     *
     * @return string
     */
    public static function convertToHumanReadable($bytes, $decimals = 2)
    {
        $factor = floor((strlen($bytes) - 1) / 3);

        $value = sprintf("%.{$decimals}f", $bytes / pow(1024, $factor));
        $unit = static::$sizes[(string)$factor];

        return sprintf('%g %s', $value, $unit);
    }

}
