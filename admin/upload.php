<?php
require_once 'config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCSRFToken($_POST['csrf_token'])) {
    header('Location: dashboard.php');
    exit;
}

$type = $_POST['type'] ?? '';
$galleryData = file_exists(DATA_FILE) ? json_decode(file_get_contents(DATA_FILE), true) : ['before_after' => [], 'normal' => []];

function processImage($file, $prefix) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];

    if (!in_array($file['type'], $allowed)) {
        return ['error' => 'Nur JPG, PNG und WebP Dateien erlaubt'];
    }

    if ($file['size'] > 10 * 1024 * 1024) { // 10MB
        return ['error' => 'Datei zu groß (max. 10MB)'];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . '_' . uniqid() . '.' . $extension;
    $filepath = GALLERY_DIR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $filepath)) {
        return ['error' => 'Upload fehlgeschlagen'];
    }

    return ['success' => true, 'filename' => 'assets/images/gallery/' . $filename];
}

try {
    if ($type === 'before_after') {
        if (empty($_FILES['before']) || empty($_FILES['after'])) {
            throw new Exception('Beide Bilder sind erforderlich');
        }

        $beforeResult = processImage($_FILES['before'], 'before');
        if (isset($beforeResult['error'])) {
            throw new Exception($beforeResult['error']);
        }

        $afterResult = processImage($_FILES['after'], 'after');
        if (isset($afterResult['error'])) {
            // Delete before image if after fails
            @unlink(GALLERY_DIR . basename($beforeResult['filename']));
            throw new Exception($afterResult['error']);
        }

        $galleryData['before_after'][] = [
            'before' => $beforeResult['filename'],
            'after' => $afterResult['filename'],
            'uploaded' => date('Y-m-d H:i:s')
        ];

    } elseif ($type === 'normal') {
        if (empty($_FILES['image'])) {
            throw new Exception('Bild ist erforderlich');
        }

        $imageResult = processImage($_FILES['image'], 'dog');
        if (isset($imageResult['error'])) {
            throw new Exception($imageResult['error']);
        }

        $galleryData['normal'][] = [
            'image' => $imageResult['filename'],
            'uploaded' => date('Y-m-d H:i:s')
        ];

    } else {
        throw new Exception('Ungültiger Bildtyp');
    }

    // Save data
    file_put_contents(DATA_FILE, json_encode($galleryData, JSON_PRETTY_PRINT));

    // Update Galerie.html
    require_once 'update-gallery.php';
    updateGalleryHTML($galleryData);

    header('Location: dashboard.php?success=uploaded');
    exit;

} catch (Exception $e) {
    header('Location: dashboard.php?error=' . urlencode($e->getMessage()));
    exit;
}
?>
