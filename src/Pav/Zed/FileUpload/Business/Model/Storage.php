<?php

namespace Pav\Zed\FileUpload\Business\Model;

use League\Flysystem\FilesystemInterface;
use Pav\Zed\FileUpload\Business\Model\FileSize;
use Pav\Zed\FileUpload\Business\Model\StorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Storage implements StorageInterface
{

    /**
     * @var \League\Flysystem\FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @param \League\Flysystem\FilesystemInterface $filesystem
     * @param string $baseUrl
     */
    public function __construct(FilesystemInterface $filesystem, $baseUrl)
    {
        $this->filesystem = $filesystem;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @param string $containerName
     * @param \SplFileInfo $file
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function saveFile($containerName, \SplFileInfo $file, $relativePath)
    {
        $fileName = $file->getFilename();
        $filePath = $this->getFullFileName($containerName, $fileName, $relativePath);

        return $this->move($file->getRealPath(), $filePath);
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
        if ($file->isValid() === false) {
            return false;
        }

        $fileName = $file->getClientOriginalName();
        $filePath = $this->getFullFileName($containerName, $fileName);

        return $this->move($file->getRealPath(), $filePath);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     *
     * @return array
     */
    protected function getFileConfig(UploadedFile $file)
    {
        return [
            'mimetype' => $file->getMimeType()
        ];
    }

    /**
     * @param string $containerName
     *
     * @return array
     */
    public function listFilesByContainerName($containerName)
    {
        $files = [];
        foreach ($this->filesystem->listContents($containerName, true) as $file) {
            $file['mapped_path'] = sprintf('%s/%s', $this->baseUrl, $file['path']);

            if (array_key_exists('size', $file)) {
                $file['size_human'] = FileSize::convertToHumanReadable($file['size']);
            }

            $files[] = $file;
        }

        return $files;
    }

    /**
     * @param string $containerName
     * @param string $fileName
     * @param string|null $relativePath
     *
     * @return bool
     */
    public function deleteFile($containerName, $fileName, $relativePath)
    {
        $fullFileName = $this->getFullFileName($containerName, $fileName, $relativePath);

        return $this->filesystem->delete($fullFileName);
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
        $fullFileName = $this->getFullFileName($containerName, $fileName, $relativePath);

        return $this->filesystem->readStream($fullFileName);
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
        $fullFileName = $this->getFullFileName($containerName, $fileName, $relativePath);

        return $this->filesystem->has($fullFileName);
    }

    /**
     * @param string $containerName
     * @param string $fileName
     * @param string|null $relativePath
     *
     * @return string
     */
    protected function getFullFileName($containerName, $fileName, $relativePath)
    {
        if ($relativePath === null) {
            $relativePath = '';
        } else {
            $relativePath = trim($relativePath, '/');
            $relativePath = $relativePath . '/';
        }

        return sprintf('%s/%s%s', $containerName, $relativePath, $fileName);
    }

    /**
     * @param string $sourcePath
     * @param string $targetPath
     *
     * @return bool
     */
    protected function move($sourcePath, $targetPath)
    {
        if ($this->filesystem->has($targetPath)) {
            return false;
        }

        $stream = fopen($sourcePath, 'r+');
        if ($stream === null) {
            return false;
        }

        $success = $this->filesystem->writeStream($targetPath, $stream);

        if (fclose($stream) === false) {
            return false;
        }

        return $success;
    }

}
