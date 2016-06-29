<?php

namespace Pav\Zed\FileUpload\Business\Model;

use Pav\Shared\FileUpload\FileUploadConstants;
use Pav\Zed\FileUpload\Business\Exception\InvalidConfigException;
use Pav\Zed\FileUpload\Business\Exception\NotImplementedException;
use Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException;
use Pav\Zed\FileUpload\Business\Model\StorageProviderInterface;
use Pav\Zed\FileUpload\Communication\Plugin\FileSystemPluginInterface;

class StorageProvider implements StorageProviderInterface
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @var \Pav\Zed\FileUpload\Communication\Plugin\FileSystemPluginInterface[]
     */
    protected $fileSystemPlugins = [];

    /**
     * @param \Pav\Zed\FileUpload\Communication\Plugin\FileSystemPluginInterface[] $fileSystemPlugins
     * @param array $config
     */
    public function __construct(array $fileSystemPlugins, array $config)
    {
        foreach ($fileSystemPlugins as $fileSystemPlugin) {
            $this->addFileSystemPlugin($fileSystemPlugin);
        }

        $this->config = $config;
    }

    /**
     * @param string $containerName
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @throws \Pav\Zed\FileUpload\Business\Exception\NotImplementedException
     * @throws \Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException
     * @return \League\Flysystem\Filesystem
     */
    public function createFilesystem($containerName)
    {
        $config = $this->getConfigForContainerName($containerName);

        $adapterName = $config[FileUploadConstants::CONFIG_TYPE];

        if (!isset($this->fileSystemPlugins[$adapterName])) {
            throw new NotImplementedException(sprintf('Type "%s" is currently not supported', $adapterName));
        }

        return $this->fileSystemPlugins[$adapterName]->createFileSystem($config[FileUploadConstants::CONFIG_CONFIG]);
    }

    /**
     * @param string $containerName
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @throws \Pav\Zed\FileUpload\Business\Exception\NotImplementedException
     * @throws \Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException
     * @return mixed
     */
    public function getBaseUrl($containerName)
    {
        $config = $this->getConfigForContainerName($containerName);

        if (isset($config[FileUploadConstants::CONFIG_CONFIG][FileUploadConstants::BASE_URL])) {
            return $config[FileUploadConstants::CONFIG_CONFIG][FileUploadConstants::BASE_URL];
        }

        return '';
    }

    /**
     * @param string $containerName
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @throws \Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException
     * @return mixed
     */
    public function getConfigForContainerName($containerName)
    {
        if (isset($this->config[$containerName]) === false) {
            throw new StorageForTypeNotConfiguredException($containerName);
        }

        $config = $this->config[$containerName];

        if (isset($config[FileUploadConstants::CONFIG_TYPE]) === false) {
            throw new InvalidConfigException(FileUploadConstants::CONFIG_TYPE);
        }

        if (isset($config[FileUploadConstants::CONFIG_CONFIG]) === false) {
            throw new InvalidConfigException(FileUploadConstants::CONFIG_CONFIG);
        }

        return $config;
    }

    /**
     * @param \Pav\Zed\FileUpload\Communication\Plugin\FileSystemPluginInterface $fileSystemPlugin
     * @return void
     */
    protected function addFileSystemPlugin(FileSystemPluginInterface $fileSystemPlugin)
    {
        $this->fileSystemPlugins[$fileSystemPlugin->getAdapteName()] = $fileSystemPlugin;
    }

}
