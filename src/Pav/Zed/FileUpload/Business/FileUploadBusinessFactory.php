<?php

namespace Pav\Zed\FileUpload\Business;

use Pav\Zed\Document\FileUploadDependencyProvider;
use Pav\Zed\FileUpload\Business\Model\Storage;
use Pav\Zed\FileUpload\Business\Model\StorageProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pav\Zed\FileUpload\FileUploadConfig getConfig()
 */
class FileUploadBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @param string $containerName
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @throws \Pav\Zed\FileUpload\Business\Exception\NotImplementedException
     * @return \Pav\Zed\FileUpload\Business\Model\Storage
     */
    public function createStorage($containerName)
    {
        $storageProvider = $this->createStorageProvider();

        return new Storage(
            $storageProvider->createFilesystem($containerName),
            $storageProvider->getBaseUrl($containerName)
        );
    }

    /**
     * @return \Pav\Zed\FileUpload\Business\Model\StorageProvider
     */
    public function createStorageProvider()
    {
        return new StorageProvider(
            $this->getFilesystemPlugins(),
            $this->getConfig()->getStorageConfig()
        );
    }

    /**
     * @return \Pav\Zed\FileUpload\Communication\Plugin\FileSystemPluginInterface[]
     */
    protected function getFilesystemPlugins()
    {
        return $this->getProvidedDependency(FileUploadDependencyProvider::FILESYSTEM_PLUGINS);
    }

}
