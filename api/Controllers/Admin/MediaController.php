<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Core\View;
use Api\Core\Traits\RequiresAuth;
use Api\Models\Media;

class MediaController
{
    use RequiresAuth;

    private Database $db;
    private Auth $auth;
    private Media $mediaModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->mediaModel = new Media($db);
        $this->requirePermission('manage_projects');
    }

    public function upload(): void
    {
        header('Content-Type: application/json');

        if (empty($_FILES['file'])) {
            echo json_encode(['error' => 'No file uploaded']);
            return;
        }

        $result = $this->mediaModel->upload($_FILES['file'], $this->auth->user()['id']);

        if (isset($result['error'])) {
            echo json_encode(['error' => $result['error']]);
            return;
        }

        echo json_encode($result);
    }

    public function uploadDownload(): void
    {
        header('Content-Type: application/json');

        if (empty($_FILES['file'])) {
            echo json_encode(['error' => 'No file uploaded']);
            return;
        }

        $file = $_FILES['file'];
        
        $allowedTypes = [
            'zip' => ['application/zip', 'application/x-zip-compressed'],
            'rar' => ['application/x-rar-compressed', 'application/vnd.rar'],
            '7z'  => ['application/x-7z-compressed'],
            'tar' => ['application/x-tar'],
            'gz'  => ['application/gzip', 'application/x-gzip'],
            'pdf' => ['application/pdf'],
            'exe' => ['application/x-msdownload', 'application/x-dosexec', 'application/octet-stream'],
            'dmg' => ['application/x-apple-diskimage', 'application/octet-stream'],
            'apk' => ['application/vnd.android.package-archive', 'application/zip'],
        ];
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!isset($allowedTypes[$ext])) {
            echo json_encode(['error' => 'File type not allowed. Allowed: ' . implode(', ', array_keys($allowedTypes))]);
            return;
        }
        
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $detectedMime = $finfo->file($file['tmp_name']);
        
        if (!in_array($detectedMime, $allowedTypes[$ext])) {
            echo json_encode(['error' => 'File content does not match extension']);
            return;
        }
        
        $maxSize = 500 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            echo json_encode(['error' => 'File too large. Max 500MB']);
            return;
        }

        $sha = sha1_file($file['tmp_name']);
        $filename = $sha . '.' . $ext;
        $path = "/uploads/files/{$filename}";
        $fullPath = BASE_PATH . $path;

        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        if (file_exists($fullPath)) {
            echo json_encode([
                'success' => true,
                'path' => $path,
                'size' => filesize($fullPath),
                'duplicate' => true,
            ]);
            return;
        }

        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            echo json_encode(['error' => 'Upload failed']);
            return;
        }

        echo json_encode([
            'success' => true,
            'path' => $path,
            'size' => filesize($fullPath),
        ]);
    }

    public function delete(string $id): void
    {
        header('Content-Type: application/json');

        $this->mediaModel->delete((int) $id);
        echo json_encode(['success' => true]);
    }
}