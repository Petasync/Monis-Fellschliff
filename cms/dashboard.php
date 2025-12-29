<?php
require_once 'config.php';
requireLogin();

// Load gallery data
$galleryData = file_exists(DATA_FILE) ? json_decode(file_get_contents(DATA_FILE), true) : ['before_after' => [], 'normal' => []];

// Handle image deletion
if (isset($_POST['delete']) && isset($_POST['image_id']) && verifyCSRFToken($_POST['csrf_token'])) {
    $imageId = $_POST['image_id'];
    $category = $_POST['category'];

    if (isset($galleryData[$category][$imageId])) {
        $imageData = $galleryData[$category][$imageId];

        // Delete files
        if (isset($imageData['before'])) {
            @unlink(GALLERY_DIR . basename($imageData['before']));
            @unlink(GALLERY_DIR . basename($imageData['after']));
        } else {
            @unlink(GALLERY_DIR . basename($imageData['image']));
        }

        // Remove from data
        unset($galleryData[$category][$imageId]);
        $galleryData[$category] = array_values($galleryData[$category]);

        file_put_contents(DATA_FILE, json_encode($galleryData, JSON_PRETTY_PRINT));

        // Update Galerie.html
        require_once 'update-gallery.php';
        updateGalleryHTML($galleryData);

        header('Location: dashboard.php?success=deleted');
        exit;
    }
}

$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Moni's Fellschliff Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f8f9fa;
            color: #2c3e50;
        }

        .header {
            background: linear-gradient(135deg, #4A90E2 0%, #87CEEB 100%);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header h1 {
            font-size: clamp(1.5rem, 4vw, 2rem);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            background: white;
            color: #4A90E2;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .stat-card h3 {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #4A90E2;
        }

        .upload-section {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .upload-section h2 {
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .gallery-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .gallery-item-info {
            padding: 1rem;
        }

        .gallery-item-info h4 {
            margin-bottom: 0.5rem;
            color: #2c3e50;
        }

        .gallery-item-info p {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            border-left: 4px solid #28a745;
        }

        .section-title {
            font-size: 1.5rem;
            margin: 2rem 0 1rem;
            color: #2c3e50;
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <div>
                <h1>🐾 Galerie-Verwaltung</h1>
                <p>Willkommen, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</p>
            </div>
            <div>
                <a href="../index.html" class="btn" target="_blank">Website ansehen</a>
                <a href="logout.php" class="btn btn-danger">Abmelden</a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <?php
                if ($_GET['success'] === 'deleted') echo '✅ Bild erfolgreich gelöscht!';
                if ($_GET['success'] === 'uploaded') echo '✅ Bilder erfolgreich hochgeladen!';
                ?>
            </div>
        <?php endif; ?>

        <div class="stats">
            <div class="stat-card">
                <h3>Vorher/Nachher Bilder</h3>
                <div class="number"><?php echo count($galleryData['before_after']); ?></div>
            </div>
            <div class="stat-card">
                <h3>Normale Bilder</h3>
                <div class="number"><?php echo count($galleryData['normal']); ?></div>
            </div>
            <div class="stat-card">
                <h3>Gesamt</h3>
                <div class="number"><?php echo count($galleryData['before_after']) + count($galleryData['normal']); ?></div>
            </div>
        </div>

        <div class="upload-section">
            <h2>📤 Neue Bilder hochladen</h2>
            <form action="upload.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bildtyp wählen:</label>
                    <select name="type" id="typeSelect" required style="width: 100%; padding: 0.75rem; border: 2px solid #e3f2fd; border-radius: 10px; font-size: 1rem;" onchange="toggleFileInputs()">
                        <option value="">-- Bitte wählen --</option>
                        <option value="before_after">Vorher/Nachher (2 Bilder)</option>
                        <option value="normal">Normales Bild (1 Bild)</option>
                    </select>
                </div>

                <div id="beforeAfterInputs" style="display: none; margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Vorher-Bild:</label>
                    <input type="file" name="before" accept="image/webp,image/jpeg,image/png" style="width: 100%; padding: 0.75rem; border: 2px solid #e3f2fd; border-radius: 10px;">

                    <label style="display: block; margin: 1rem 0 0.5rem; font-weight: 600;">Nachher-Bild:</label>
                    <input type="file" name="after" accept="image/webp,image/jpeg,image/png" style="width: 100%; padding: 0.75rem; border: 2px solid #e3f2fd; border-radius: 10px;">
                </div>

                <div id="normalInput" style="display: none; margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Bild hochladen:</label>
                    <input type="file" name="image" accept="image/webp,image/jpeg,image/png" style="width: 100%; padding: 0.75rem; border: 2px solid #e3f2fd; border-radius: 10px;">
                </div>

                <button type="submit" class="btn" style="background: linear-gradient(135deg, #4A90E2 0%, #87CEEB 100%); color: white;">Hochladen</button>
            </form>
        </div>

        <!-- Vorher/Nachher Gallery -->
        <?php if (!empty($galleryData['before_after'])): ?>
            <h2 class="section-title">✨ Vorher/Nachher Bilder</h2>
            <div class="gallery-grid">
                <?php foreach ($galleryData['before_after'] as $index => $item): ?>
                    <div class="gallery-item">
                        <img src="<?php echo htmlspecialchars('../' . $item['before']); ?>" alt="Vorher">
                        <div class="gallery-item-info">
                            <h4>Vorher/Nachher #<?php echo $index + 1; ?></h4>
                            <form method="POST" style="margin-top: 0.5rem;">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="image_id" value="<?php echo $index; ?>">
                                <input type="hidden" name="category" value="before_after">
                                <button type="submit" name="delete" class="btn btn-danger" style="font-size: 0.9rem; padding: 0.5rem 1rem;" onclick="return confirm('Wirklich löschen?')">Löschen</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Normal Gallery -->
        <?php if (!empty($galleryData['normal'])): ?>
            <h2 class="section-title">🐕 Normale Bilder</h2>
            <div class="gallery-grid">
                <?php foreach ($galleryData['normal'] as $index => $item): ?>
                    <div class="gallery-item">
                        <img src="<?php echo htmlspecialchars('../' . $item['image']); ?>" alt="Hund">
                        <div class="gallery-item-info">
                            <h4>Bild #<?php echo $index + 1; ?></h4>
                            <form method="POST" style="margin-top: 0.5rem;">
                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                <input type="hidden" name="image_id" value="<?php echo $index; ?>">
                                <input type="hidden" name="category" value="normal">
                                <button type="submit" name="delete" class="btn btn-danger" style="font-size: 0.9rem; padding: 0.5rem 1rem;" onclick="return confirm('Wirklich löschen?')">Löschen</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function toggleFileInputs() {
            const type = document.getElementById('typeSelect').value;
            document.getElementById('beforeAfterInputs').style.display = type === 'before_after' ? 'block' : 'none';
            document.getElementById('normalInput').style.display = type === 'normal' ? 'block' : 'none';
        }
    </script>
</body>
</html>
