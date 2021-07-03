<?php


namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class FileUploadedRemover
{
    private string $targetDirectory;
    private Filesystem $fileSystem;

    public function __construct(string $targetDirectory, Filesystem $fileSystem)
    {
        $this->targetDirectory = $targetDirectory;
        $this->fileSystem = $fileSystem;
    }

    public function removeUploadedFile(string $fileName): Void
    {
        $this->fileSystem->remove($this->targetDirectory.'/'.$fileName);
    }
}
