<?php

namespace app\helpers;

class UploadHelper{
    public function handleImageUpload($field = 'image') {
        if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $fileTmpPath = $_FILES[$field]['tmp_name'];
        $fileName = $_FILES[$field]['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            return null;
        }

        $newFileName = uniqid('media_', true) . '.' . $fileExtension;
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destPath = $uploadDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            return '/uploads/' . $newFileName;
        }

        return null;
    }

    public function handleMultipleUploads($inputName, $destination = 'uploads/'): array
    {
        error_log("FILES: " . print_r($_FILES[$inputName], true));
        $results = [];

        if (empty($_FILES[$inputName])) return $results;

        foreach ($_FILES[$inputName]['tmp_name'] as $i => $tmp) {
            if ($_FILES[$inputName]['error'][$i] !== UPLOAD_ERR_OK) continue;

            $original = $_FILES[$inputName]['name'][$i];
            $type = $_FILES[$inputName]['type'][$i];
            $filename = uniqid() . '_' . basename($original);
            $target = $destination . $filename;

            if (move_uploaded_file($tmp, __DIR__ . '/../../public/' . $target)) {
                $results[] = [
                    'path' => $target,
                    'file_type' => $type
                ];
            }
        }

        return $results;
    }

}