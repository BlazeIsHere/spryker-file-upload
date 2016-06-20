<?php

namespace Pav\Zed\Document;

use Pav\Zed\FileUpload\Communication\Plugin\LocalFileSystemPlugin;
use Pav\Zed\FileUpload\Communication\Plugin\S3FileSystemPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class FileUploadDependencyProvider extends AbstractBundleDependencyProvider
{

    const FILESYSTEM_PLUGINS = 'filesystem plugins';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FILESYSTEM_PLUGINS] = function () {
            return $this->getFilesystemPlugins();
        };

        return parent::provideBusinessLayerDependencies($container);
    }

    /**
     * @return \Pav\Zed\Document\Dependency\Plugin\PdfDocumentConverterInterface[]
     */
    protected function getFilesystemPlugins()
    {
        return [
            new LocalFileSystemPlugin(),
            new S3FileSystemPlugin(),
        ];
    }

}
