<?php

namespace Api\Controllers\Admin;

use Api\Core\Database;
use Api\Core\Auth;
use Api\Models\Media;

class MediaController
{
    private Database $db;
    private Auth $auth;
    private Media $mediaModel;

    public function __construct(Database $db, Auth $auth)
    {
        $this->db = $db;
        $this->auth = $auth;
        $this->mediaModel = new Media($db);

        if (!$this->auth->isTeamMember()) {
            View::notFound();
        }
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
        
        $allowedExt = ['zip', 'rar', '7z', 'tar', 'gz', 'pdf', 'exe', 'dmg', 'apk'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowedExt)) {
            echo json_encode(['error' => 'File type not allowed. Allowed: ' . implode(', ', $allowedExt)]);
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