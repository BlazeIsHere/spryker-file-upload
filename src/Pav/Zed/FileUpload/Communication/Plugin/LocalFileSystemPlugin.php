<?php

namespace Pav\Zed\FileUpload\Communication\Plugin;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Pav\Shared\FileUpload\FileUploadConstants;
use Pav\Zed\FileUpload\Business\Exception\InvalidConfigException;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class LocalFileSystemPlugin extends AbstractPlugin implements FileSystemPluginInterface
{

    /**
     * @return string
     */
    public function getAdapteName()
    {
        return FileUploadConstants::ADAPTER_LOCAL;
    }

    /**
     * @param array $config
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @return \League\Flysystem\Filesystem
     */
    public function createFileSystem(array $config)
    {
        if (isset($config[FileUploadConstants::LOCAL_PATH]) === false) {
            throw new InvalidConfigException(FileUploadConstants::LOCAL_PATH);
        }

        $localFilesystem = new Local($config[FileUploadConstants::LOCAL_PATH]);
        $filesystem = new Filesystem($localFilesystem);

        return $filesystem;
    }

}
