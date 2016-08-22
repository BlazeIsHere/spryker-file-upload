<?php
namespace Pav\Zed\FileUpload\Business\Model;

interface StorageInterface
{

    /**
     * @param string $containerName
     * @param \SplFileInfo $file
     *
     * @return mixed
     */
    public function saveFile($containerName, \SplFileInfo $file);

    /**
     * @param string $containerName
     * @param string $fileName
     *
     * @return bool
     */
    public function deleteFile($containerName, $fileName);

    /**
     * @param string $containerName
     * @return array
     */
    public function listFilesByContainerName($containerName);

    /**
     * @param string $containerName
     * @param string $fileName
     *
     * @return false|resource
     */
    public function readFileStream($containerName, $fileName);

    /**
     * @param string $containerName
     * @param string $fileName
     *
     * @return bool
     */
    public function fileExists($containerName, $fileName);

}
