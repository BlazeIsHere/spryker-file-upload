<?php

namespace Pav\Zed\FileUpload\Communication\Plugin;

use Aws\S3\S3Client;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;
use Pav\Shared\FileUpload\FileUploadConstants;
use Pav\Zed\FileUpload\Business\Exception\InvalidConfigException;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class S3FileSystemPlugin extends AbstractPlugin implements FileSystemPluginInterface
{

    /**
     * @var array
     */
    protected $requiredAwsKeys = [
        FileUploadConstants::AWS_KEY,
        FileUploadConstants::AWS_REGION,
        FileUploadConstants::AWS_SECRET,
        FileUploadConstants::BUCKET_NAME
    ];

    /**
     * @return string
     */
    public function getAdapteName()
    {
        return FileUploadConstants::ADAPTER_S3;
    }

    /**
     * @param array $config
     *
     * @return \League\Flysystem\FilesystemInterface
     */
    public function createFileSystem(array $config)
    {

        $this->validateS3Config($config, $this->requiredAwsKeys);

        $client = S3Client::factory([
            FileUploadConstants::AWS_CREDENTIALS => [
                FileUploadConstants::KEY => $config[FileUploadConstants::AWS_KEY],
                FileUploadConstants::SECRET => $config[FileUploadConstants::AWS_SECRET],
            ],
            FileUploadConstants::REGION => $config[FileUploadConstants::AWS_REGION],
            FileUploadConstants::VERSION => FileUploadConstants::LATEST,
        ]);

        $adapter = new AwsS3Adapter(
            $client,
            $config[FileUploadConstants::BUCKET_NAME],
            isset($config[FileUploadConstants::BUCKET_PREFIX]) ? $config[FileUploadConstants::BUCKET_PREFIX] : null
        );

        $filesystem = new Filesystem($adapter);

        return $filesystem;
    }

    /**
     * @param array $config
     * @param array $requiredKeys
     * @throws \Pav\Zed\FileUpload\Business\Exception\InvalidConfigException
     *
     * @return void
     */
    protected function validateS3Config(array $config, array $requiredKeys)
    {
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $config)) {
                throw new InvalidConfigException($requiredKey);
            }
        }
    }

}
