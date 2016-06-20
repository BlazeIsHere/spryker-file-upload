<?php

namespace Pav\Zed\FileUpload\Communication\Plugin;

interface FileSystemPluginInterface
{

    /**
     * @return string
     */
    public function getAdapteName();

    /**
     * @param array $config
     *
     * @return \League\Flysystem\FilesystemInterface
     */
    public function createFileSystem(array $config);

}
