<?php

namespace Api\Models;

class Media extends Model
{
    protected string $table = 'media';

    public function upload(array $file, int $uploadedBy = null): ?array
    {
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'video/mp4' => 'mp4',
            'video/webm' => 'webm',
        ];

        if (!isset($allowed[$file['type']])) {
            return ['error' => 'File type not allowed'];
        }

        $sha = sha1_file($file['tmp_name']);
        $existing = $this->findBySha($sha);
        if ($existing) {
            return ['success' => true, 'media' => $existing, 'duplicate' => true];
        }

        $ext = $allowed[$file['type']];
        $type = str_starts_with($file['type'], 'video/') ? 'video' : 'image';
        if ($ext === 'gif') $type = 'gif';

        $folder = match($type) {
            'video' => 'videos',
            default => 'images',
        };

        $filename = $sha . '.' . $ext;
        $path = "/uploads/{$folder}/{$filename}";
        $fullPath = BASE_PATH . $path;

        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        if ($type === 'image' && $ext !== 'gif') {
            $this->compressImage($file['tmp_name'], $fullPath, $file['type']);
        } else {
            move_uploaded_file($file['tmp_name'], $fullPath);
        }

        $id = $this->create([
            'filename' => $file['name'],
            'path' => $path,
            'type' => $type,
            'sha' => $sha,
            'uploaded_by' => $uploadedBy,
        ]);

        return ['success' => true, 'media' => $this->find($id)];
    }

    public function uploadFromPath(string $sourcePath, string $originalName, int $uploadedBy = null): ?array
    {
        $mime = mime_content_type($sourcePath);
        $fakeFile = [
            'tmp_name' => $sourcePath,
            'type' => $mime,
            'name' => $originalName,
        ];

        return $this->upload($fakeFile, $uploadedBy);
    }

    public function findBySha(string $sha): ?array
    {
        return $this->db->queryOne("SELECT * FROM {$this->table} WHERE sha = ?", [$sha]);
    }

    private function compressImage(string $source, string $dest, string $mime, int $quality = 80): void
    {
        $image = match($mime) {
            'image/jpeg' => imagecreatefromjpeg($source),
            'image/png' => imagecreatefrompng($source),
            'image/webp' => imagecreatefromwebp($source),
            default => null,
        };

        if (!$image) {
            move_uploaded_file($source, $dest);
            return;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $maxDim = 1920;

        if ($width > $maxDim || $height > $maxDim) {
            $ratio = min($maxDim / $width, $maxDim / $height);
            $newWidth = (int) ($width * $ratio);
            $newHeight = (int) ($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            
            if ($mime === 'image/png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        match($mime) {
            'image/png' => imagepng($image, $dest, 8),
            'image/webp' => imagewebp($image, $dest, $quality),
            default => imagejpeg($image, $dest, $quality),
        };

        imagedestroy($image);
    }

    public function delete(int $id): int
    {
        $media = $this->find($id);

        if ($media) {
            $fullPath = BASE_PATH . $media['path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        return parent::delete($id);
    }
}