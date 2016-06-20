<?php

namespace Pav\Zed\FileUpload\Business\Exception;

use Exception;

class InvalidConfigException extends Exception
{

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        parent::__construct(sprintf('Config is missing key "%s"', $key));
    }

}
