<?php

namespace Pav\Zed\FileUpload\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @method \Pav\Zed\FileUpload\Business\FileUploadBusinessFactory getFactory()
 */
class FileUploadFacade extends AbstractFacade
{

    /**
     * @param string $containerName
     * @param \SplFileInfo $file
     *
     * @return bool
     */
    public function saveFile($containerName, \SplFileInfo $file)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->saveFile($containerName, $file);
    }

    /**
     * @param string $containerName
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return bool
     */
    public function saveUploadedFile($containerName, UploadedFile $file)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->saveUploadedFile($containerName, $file);
    }

    /**
     * @param string $containerName
     * @param string $filePath
     *
     * @return bool
     */
    public function deleteFile($containerName, $filePath)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->deleteFile($filePath);
    }

    /**
     * @param string $containerName
     *
     * @return array
     */
    public function listFilesByContainerName($containerName)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->listFilesByContainerName($containerName);
    }

    /**
     * @param string $containerName
     * @return array
     */
    public function getConfigForContainerName($containerName)
    {
        return $this->getFactory()
            ->createStorageProvider()
            ->getConfigForContainerName($containerName);
    }

    /**
     * @param string $containerName
     * @param string $fileName
     *
     * @return false|resource
     */
    public function readFileStream($containerName, $fileName)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->readFileStream($containerName, $fileName);
    }

    /**
     * @param string $containerName
     * @param string $fileName
     *
     * @return bool
     */
    public function fileExist($containerName, $fileName)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->fileExist($containerName, $fileName);
    }

}
