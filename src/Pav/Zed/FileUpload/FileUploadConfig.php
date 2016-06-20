<?php

namespace Pav\Zed\FileUpload;

use Pav\Shared\FileUpload\FileUploadConstants as SharedFileUploadConfig;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class FileUploadConfig extends AbstractBundleConfig
{

    /**
     * @return string
     */
    public function getStorageConfig()
    {
        return $this->get(SharedFileUploadConfig::UPLOAD_STORAGE);
    }

}
