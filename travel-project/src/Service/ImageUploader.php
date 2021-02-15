<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;


class ImageUploader
{
    public function getRandFilename(string $extension): string
    {
        return preg_replace('/[+=\/]/', random_int(0, 9), base64_encode(random_bytes(8))) . '.' . $extension;
    }

    public function moveFile(?UploadedFile $file, string $path): ?string
    {
        if ($file != NULL) {
            $fileName = $this->getRandFilename($file->getClientOriginalExtension());
            $file->move($path, $fileName);

            return $fileName;
        } else {
            return NULL;
        }
    }
}