<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.07.19
 * Time: 22:16
 */

namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class CanopyImageUploader
{

    private $targetDir;

    public function __construct($targetDir)
    {
        $this->targetDir = $targetDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getTargetDir(), $fileName);

        return $fileName;
    }

    public function getTargetDir()
    {
        return $this->targetDir;
    }
}