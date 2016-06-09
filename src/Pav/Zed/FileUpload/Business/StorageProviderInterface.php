<?php
namespace Pav\Zed\FileUpload\Business;

use League\Flysystem\Filesystem;
use Pav\Zed\FileUpload\Business\Exception\InvalidConfigException;
use Pav\Zed\FileUpload\Business\Exception\NotImplementedException;
use Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException;

interface StorageProviderInterface
{
    /**
     * @param string $type
     * @return Filesystem
     * @throws InvalidConfigException
     * @throws NotImplementedException
     * @throws StorageForTypeNotConfiguredException
     */
    public function createFilesystem($type);
}
