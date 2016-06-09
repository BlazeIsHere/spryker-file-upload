<?php

namespace Pav\Zed\FileUpload\Communication\Controller;

use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class AbstractImageController extends AbstractController
{

    const FILE_REQUEST = 'uploadedFile';

    /**
     * @return string
     */
    abstract protected function getFileType();

    /**
     * @return array
     */
    public function indexAction()
    {
        $files = $this->getFileUploadFacade()->listFilesByType($this->getFileType());

        return [
            'files' => $files,
            'fileType' => $this->getFileType()
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function uploadAction(Request $request)
    {
        if ($request->isMethod('POST') === false) {
            throw new BadRequestHttpException('Only POST is allowed for file uploads');
        }

        /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
        $file = $request->files->get(self::FILE_REQUEST);
        if ($file === null || ($file->isValid() === false)) {
            $this->addErrorMessage('No file sent!');
            return $this->redirectToIndex();
        }

        $fileUploaded = $this->getFileUploadFacade()->saveFile($this->getFileType(), $file);

        if ($fileUploaded === false) {
            $this->addErrorMessage('Could not upload file!');
        } else {
            $this->addSuccessMessage('Successfully uploaded file: ' . $file->getClientOriginalName());
        }

        return $this->redirectToIndex();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $path = $request->request->get('path');
        if ($path) {
            $deleted = $this->getFileUploadFacade()->deleteFile($this->getFileType(), $path);
            if ($deleted) {
                $this->addSuccessMessage('Successfully deleted file: ' . $path);
            } else {
                $this->addErrorMessage('Could NOT delete file: ' . $path);
            }
        } else {
            throw new BadRequestHttpException('No "path" provided.');
        }

        return $this->redirectToIndex();
    }

    /**
     * @return \Pav\Zed\FileUpload\Business\FileUploadFacade
     * @throws \ErrorException
     */
    abstract protected function getFileUploadFacade();

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectToIndex()
    {
        return $this->redirectResponse($this->getIndexPath());
    }

    /**
     * @return string
     */
    abstract protected function getIndexPath();

}
