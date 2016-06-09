<?php

namespace Pav\Zed\FileUpload\Business\Model;

use Aws\S3\S3Client;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Pav\Shared\FileUpload\FileUploadConstants;
use Pav\Zed\FileUpload\Business\Exception\InvalidConfigException;
use Pav\Zed\FileUpload\Business\Exception\NotImplementedException;
use Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException;
use Pav\Zed\FileUpload\Business\Model\StorageProviderInterface;

class StorageProvider implements StorageProviderInterface
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $type
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @throws \Pav\Zed\FileUpload\Business\Exception\NotImplementedException
     * @throws \Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException
     * @return \League\Flysystem\Filesystem
     */
    public function createFilesystem($type)
    {
        $config = $this->getConfigForType($type);

        $adapter = $config[FileUploadConstants::CONFIG_TYPE];
        switch ($adapter) {

            case FileUploadConstants::ADAPTER_LOCAL:
                return $this->createLocalFilesystem($config[FileUploadConstants::CONFIG_CONFIG]);

            case FileUploadConstants::ADAPTER_S3:
                return $this->createS3Filesystem($config[FileUploadConstants::CONFIG_CONFIG]);

            default:
                throw new NotImplementedException(sprintf('Type "%s" is currently not supported', $adapter));
        }
    }

    /**
     * @param array $config
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @return \League\Flysystem\Filesystem
     */
    protected function createS3Filesystem(array $config)
    {
        if (isset($config['aws_key']) === false) {
            throw new InvalidConfigException('aws_key');
        }
        if (isset($config['aws_secret']) === false) {
            throw new InvalidConfigException('aws_secret');
        }
        if (isset($config['aws_region']) === false) {
            throw new InvalidConfigException('aws_region');
        }
        if (isset($config['bucket_name']) === false) {
            throw new InvalidConfigException('bucket_name');
        }

        $client = S3Client::factory([
            'credentials' => [
                'key'    => $config['aws_key'],
                'secret' => $config['aws_secret'],
            ],
            'region' => $config['aws_region'],
            'version' => 'latest',
        ]);

        $adapter = new AwsS3Adapter(
            $client,
            $config['bucket_name'],
            isset($config['bucket_prefix']) ? $config['bucket_prefix'] : null
        );

        $filesystem = new Filesystem($adapter);

        return $filesystem;
    }

    /**
     * @param array $config
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @return \League\Flysystem\Filesystem
     */
    protected function createLocalFilesystem(array $config)
    {
        if (isset($config['path']) === false) {
            throw new InvalidConfigException('path');
        }

        $localFilesystem = new Local($config['path']);
        $filesystem = new Filesystem($localFilesystem);

        return $filesystem;
    }

    /**
     * @param string $type
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @throws \Pav\Zed\FileUpload\Business\Exception\NotImplementedException
     * @throws \Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException
     * @return mixed
     */
    public function getMapping($type)
    {
        $config = $this->getConfigForType($type);

        $adapter = $config[FileUploadConstants::CONFIG_TYPE];
        switch ($adapter) {

            case FileUploadConstants::ADAPTER_LOCAL:
            case FileUploadConstants::ADAPTER_S3:
                if (isset($config[FileUploadConstants::CONFIG_CONFIG]['mapping']) === false) {
                    throw new InvalidConfigException('mapping');
                }
                return $config[FileUploadConstants::CONFIG_CONFIG]['mapping'];

            default:
                throw new NotImplementedException(sprintf('Type "%s" is currently not supported', $adapter));
        }
    }

    /**
     * @param string $type
     *
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     * @throws \Pav\Zed\FileUpload\Business\Exception\StorageForTypeNotConfiguredException
     * @return mixed
     */
    public function getConfigForType($type)
    {
        if (isset($this->config[$type]) === false) {
            throw new StorageForTypeNotConfiguredException($type);
        }

        $config = $this->config[$type];

        if (isset($config[FileUploadConstants::CONFIG_TYPE]) === false) {
            throw new InvalidConfigException(FileUploadConstants::CONFIG_TYPE);
        }

        if (isset($config[FileUploadConstants::CONFIG_CONFIG]) === false) {
            throw new InvalidConfigException(FileUploadConstants::CONFIG_CONFIG);
        }

        return $config;
    }

}
