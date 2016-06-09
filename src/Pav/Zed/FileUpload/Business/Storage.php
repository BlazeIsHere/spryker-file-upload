<?php

namespace Pav\Zed\FileUpload\Business;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Storage implements StorageInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $mapping;

    /**
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem, $mapping)
    {
        $this->filesystem = $filesystem;
        $this->mapping = $mapping;
    }

    /**
     * @param string $type
     * @param \SplFileInfo $file
     *
     * @return bool
     */
    public function saveFile($type, \SplFileInfo $file)
    {
        $fileName = $file->getFilename();
        $filePath = $this->getFullFileName($type, $fileName);

        return $this->move($file->getRealPath(), $filePath);
    }

    /**
     * @param string $type
     * @param UploadedFile $file
     *
     * @return bool
     */
    public function saveUploadedFile($type, UploadedFile $file)
    {
        if ($file->isValid() === false) {
            return false;
        }

        $fileName = $file->getClientOriginalName();
        $filePath = $this->getFullFileName($type, $fileName);

        return $this->move($file->getRealPath(), $filePath);
    }

    /**
     * @param UploadedFile $file
     * @return array
     */
    protected function getFileConfig(UploadedFile $file)
    {
        return [
            'mimetype' => $file->getMimeType()
        ];
    }

    /**
     * @param string $type
     * @return array
     */
    public function listFilesByType($type)
    {
        $files = [];
        foreach ($this->filesystem->listContents($type, true) as $file) {
            $file['mapped_path'] = sprintf('%s/%s',$this->mapping, $file['path']);

            if (array_key_exists('size', $file)) {
                $file['size_human'] = FileSize::convertToHumanReadable($file['size']);
            }

            $files[] = $file;
        }

        return $files;
    }

    /**

     * @param string $filePath
     *
     * @return bool
     */
    public function deleteFile($filePath)
    {
        return $this->filesystem->delete($filePath);
    }

    /**
     * @param string $type
     * @param string $fileName
     *
     * @return false|resource
     */
    public function readFileStream($type, $fileName)
    {
        $fullFileName = $this->getFullFileName($type, $fileName);

        return $this->filesystem->readStream($fullFileName);
    }

    /**
     * @param string $type
     * @param string $fileName
     *
     * @return bool
     */
    public function fileExist($type, $fileName)
    {
        $fullFileName = $this->getFullFileName($type, $fileName);

        return $this->filesystem->has($fullFileName);
    }

    /**
     * @param string $type
     * @param string $fileName
     *
     * @return string
     */
    protected function getFullFileName($type, $fileName)
    {
        return sprintf('%s/%s', $type, $fileName);
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
