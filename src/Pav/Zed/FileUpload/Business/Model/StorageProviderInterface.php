<?php

namespace Pav\Zed\FileUpload\Business\Model;

interface StorageProviderInterface
{

    /**
     * @param string $containerName
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @throws \Pav\Zed\FileUpload\Business\Exception\NotImplementedException
     * @throws \Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException
     * @return \League\Flysystem\Filesystem
     */
    public function createFilesystem($containerName);

}
