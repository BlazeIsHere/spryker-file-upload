<?php

namespace Pav\Zed\FileUpload\Business\Exception;

use Exception;

class StorageForTypeNotConfiguredException extends Exception
{

    /**
     * @param string $containerName
     */
    public function __construct($containerName)
    {
        parent::__construct(sprintf('Storage for container name "%s" is not configured', $containerName));
    }

}
