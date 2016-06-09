<?php
namespace Pav\Zed\FileUpload\Business\Model;

interface StorageInterface
{

    /**
     * @param string $type
     * @param \SplFileInfo $file
     *
     * @return mixed
     */
    public function saveFile($type, \SplFileInfo $file);

    /**
     * @param string $filePath
     *
     * @return bool
     */
    public function deleteFile($filePath);

    /**
     * @param string $type
     * @return array
     */
    public function listFilesByType($type);

    /**
     * @param string $type
     * @param string $fileName
     *
     * @return false|resource
     */
    public function readFileStream($type, $fileName);

    /**
     * @param string $type
     * @param string $fileName
     *
     * @return bool
     */
    public function fileExist($type, $fileName);

}
