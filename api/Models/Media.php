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

        $detectedMime = $this->detectMimeType($file['tmp_name']);
        
        if (!$detectedMime || !isset($allowed[$detectedMime])) {
            return ['error' => 'File type not allowed or could not be verified'];
        }

        $sha = sha1_file($file['tmp_name']);
        $existing = $this->findBySha($sha);
        if ($existing) {
            return ['success' => true, 'media' => $existing, 'duplicate' => true];
        }

        $ext = $allowed[$detectedMime];
        $type = str_starts_with($detectedMime, 'video/') ? 'video' : 'image';
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
            $this->compressImage($file['tmp_name'], $fullPath, $detectedMime);
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
        $maxDim = 1024;
        $hasTransparency = ($mime === 'image/png' || $mime === 'image/webp');

        if ($width > $maxDim || $height > $maxDim) {
            $ratio = min($maxDim / $width, $maxDim / $height);
            $newWidth = (int) ($width * $ratio);
            $newHeight = (int) ($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            
            if ($hasTransparency) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
                imagefill($resized, 0, 0, $transparent);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        if ($hasTransparency) {
            imagesavealpha($image, true);
        }

        match($mime) {
            'image/png' => imagepng($image, $dest, 8),
            'image/webp' => imagewebp($image, $dest, $quality),
            default => imagejpeg($image, $dest, $quality),
        };

        imagedestroy($image);
    }

    private function detectMimeType(string $filepath): ?string
    {
        if (!file_exists($filepath) || !is_readable($filepath)) {
            return null;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $detectedMime = $finfo->file($filepath);

        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (in_array($detectedMime, $imageTypes)) {
            $imageInfo = @getimagesize($filepath);
            if ($imageInfo === false) {
                return null;
            }
            
            $imageMimeMap = [
                IMAGETYPE_JPEG => 'image/jpeg',
                IMAGETYPE_PNG => 'image/png',
                IMAGETYPE_GIF => 'image/gif',
                IMAGETYPE_WEBP => 'image/webp',
            ];
            
            $imageTypeMime = $imageMimeMap[$imageInfo[2]] ?? null;
            if ($imageTypeMime !== $detectedMime) {
                return null;
            }
        }

        $videoTypes = ['video/mp4', 'video/webm'];
        if (in_array($detectedMime, $videoTypes)) {
            if (!$this->validateVideoMagicBytes($filepath, $detectedMime)) {
                return null;
            }
        }

        return $detectedMime;
    }

    private function validateVideoMagicBytes(string $filepath, string $expectedMime): bool
    {
        $handle = fopen($filepath, 'rb');
        if (!$handle) {
            return false;
        }

        $bytes = fread($handle, 12);
        fclose($handle);

        if (strlen($bytes) < 12) {
            return false;
        }

        return match($expectedMime) {
            'video/mp4' => substr($bytes, 4, 4) === 'ftyp',
            'video/webm' => substr($bytes, 0, 4) === "\x1A\x45\xDF\xA3",
            default => false,
        };
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