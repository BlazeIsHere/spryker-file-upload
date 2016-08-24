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
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function saveFile($containerName, \SplFileInfo $file, $relativePath)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->saveFile($containerName, $file, $relativePath);
    }

    /**
     * @param string $containerName
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function saveUploadedFile($containerName, UploadedFile $file, $relativePath)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->saveUploadedFile($containerName, $file, $relativePath);
    }

    /**
     * @param string $containerName
     * @param string $filePath
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function deleteFile($containerName, $filePath, $relativePath)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->deleteFile($containerName, $filePath, $relativePath);
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
     * @param string|null $relativePath
     *
     * @return false|resource
     */
    public function readFileStream($containerName, $fileName, $relativePath)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->readFileStream($containerName, $fileName, $relativePath);
    }

    /**
     * @param string $containerName
     * @param string $fileName
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function fileExists($containerName, $fileName, $relativePath)
    {
        return $this->getFactory()
            ->createStorage($containerName)
            ->fileExists($containerName, $fileName, $relativePath);
    }

}
