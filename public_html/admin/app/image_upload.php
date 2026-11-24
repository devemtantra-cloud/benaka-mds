<?php
// Shared helpers for the slider and service photo uploads (admin only).

const IMAGE_TYPES = [
    IMAGETYPE_JPEG => 'jpg',
    IMAGETYPE_PNG  => 'png',
    IMAGETYPE_GIF  => 'gif',
    IMAGETYPE_WEBP => 'webp',
];

// Saves $_FILES[$field] into admin/img/<$sub>/ if it is a real image.
// Returns the site-relative URL (e.g. /admin/img/slider/x.jpg) or an error string in $error.
function save_uploaded_image($field, $sub, &$error)
{
    $error = '';
    if (empty($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please choose an image to upload';
        return null;
    }
    $tmp = $_FILES[$field]['tmp_name'];
    $info = @getimagesize($tmp);
    if (!$info || !isset(IMAGE_TYPES[$info[2]])) {
        $error = 'Only JPG, PNG, GIF or WEBP images are allowed';
        return null;
    }
    if ($_FILES[$field]['size'] > 8 * 1024 * 1024) {
        $error = 'Image is larger than 8 MB';
        return null;
    }

    // Safe new name: cleaned original name + random suffix + real extension.
    $base = pathinfo($_FILES[$field]['name'], PATHINFO_FILENAME);
    $base = trim(preg_replace('/[^A-Za-z0-9_-]+/', '-', $base), '-');
    $base = substr($base !== '' ? $base : 'image', 0, 40);
    $name = $base . '-' . bin2hex(random_bytes(4)) . '.' . IMAGE_TYPES[$info[2]];

    $dir = dirname(__DIR__) . '/img/' . $sub;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    if (!move_uploaded_file($tmp, $dir . '/' . $name)) {
        $error = 'Failed to save image';
        return null;
    }
    return '/admin/img/' . $sub . '/' . $name;
}

// Deletes the file behind a stored image URL, but only inside admin/img/<$sub>/.
function delete_image_file($url, $sub)
{
    $file = basename((string) parse_url($url, PHP_URL_PATH));
    $path = dirname(__DIR__) . '/img/' . $sub . '/' . $file;
    if ($file !== '' && is_file($path)) {
        unlink($path);
    }
}
