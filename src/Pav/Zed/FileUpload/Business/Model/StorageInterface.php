<?php

namespace Pav\Zed\FileUpload\Business\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface StorageInterface
{

    /**
     * @param string $containerName
     * @param \SplFileInfo $file
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function saveFile($containerName, \SplFileInfo $file, $relativePath);

    /**
     * @param string $containerName
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function saveUploadedFile($containerName, UploadedFile $file, $relativePath);

    /**
     * @param string $containerName
     *
     * @return array
     */
    public function listFilesByContainerName($containerName);

    /**
     * @param string $containerName
     * @param string $fileName
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function deleteFile($containerName, $fileName, $relativePath);

    /**
     * @param string $containerName
     * @param string $fileName
     * @param string|null $relativePath
     *
     * @return false|resource
     */
    public function readFileStream($containerName, $fileName, $relativePath);

    /**
     * @param string $containerName
     * @param string $fileName
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function fileExists($containerName, $fileName, $relativePath);

}
