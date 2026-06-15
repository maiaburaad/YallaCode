<?php

function escapeHtml($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function getProfileImagePath($filename): string
{
    $safeFilename = basename((string) $filename);
    $absolutePath = __DIR__ . '/users_images/' . $safeFilename;

    if ($safeFilename !== '' && is_file($absolutePath)) {
        return 'users_images/' . rawurlencode($safeFilename);
    }

    return 'images/avatar.jpg';
}

function getCsrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function verifyCsrfToken(): void
{
    $submittedToken = $_POST['csrf_token'] ?? '';
    $sessionToken = $_SESSION['csrf_token'] ?? '';

    if ($sessionToken === '' || !hash_equals($sessionToken, $submittedToken)) {
        http_response_code(403);
        exit('Invalid request token.');
    }
}

function saveProfileImage(array $file): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Please select a profile image.');
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('The profile image must be 5 MB or smaller.');
    }

    $imageInfo = getimagesize($file['tmp_name']);
    $mimeType = $imageInfo['mime'] ?? '';
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
    ];

    if (!isset($extensions[$mimeType])) {
        throw new RuntimeException('Only valid JPG and PNG images are allowed.');
    }

    $uploadDirectory = __DIR__ . '/users_images';
    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
        throw new RuntimeException('Could not create the profile-image directory.');
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $extensions[$mimeType];
    $targetPath = $uploadDirectory . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new RuntimeException('Could not save the profile image.');
    }

    return $filename;
}
