<?php
function updateGalleryHTML($galleryData) {
    $galleryHTML = file_get_contents(GALLERY_HTML);

    // Generate Before/After HTML
    $beforeAfterHTML = '';
    foreach ($galleryData['before_after'] as $index => $item) {
        $beforeAfterHTML .= '                <div class="gallery-item animate-in">
                    <img src="' . htmlspecialchars($item['before']) . '" alt="Vorher/Nachher Transformation ' . ($index + 1) . '" loading="lazy">
                </div>
                <div class="gallery-item animate-in">
                    <img src="' . htmlspecialchars($item['after']) . '" alt="Vorher/Nachher Transformation ' . ($index + 1) . '" loading="lazy">
                </div>
';
    }

    // Generate Normal HTML
    $normalHTML = '';
    foreach ($galleryData['normal'] as $index => $item) {
        $normalHTML .= '                <div class="gallery-item animate-in">
                    <img src="' . htmlspecialchars($item['image']) . '" alt="Glücklicher Hund ' . ($index + 1) . '" loading="lazy">
                </div>
';
    }

    // Replace Before/After section
    $pattern1 = '/<!-- Vorher\/Nachher Gallery -->.*?<div class="gallery-grid">(.*?)<\/div>\s*<\/div>\s*<!-- Normale Hunde-Galerie -->/s';
    $replacement1 = '<!-- Vorher/Nachher Gallery -->
            <div class="gallery-category active" id="before-after">
                <h3 style="text-align: center; color: var(--primary); font-size: clamp(1.5rem, 3vw, 2rem); margin-bottom: 2rem;">
                    ✨ Vorher/Nachher Transformationen
                </h3>
                <div class="gallery-grid">
' . $beforeAfterHTML . '                </div>
            </div>

            <!-- Normale Hunde-Galerie -->';

    $galleryHTML = preg_replace($pattern1, $replacement1, $galleryHTML);

    // Replace Normal Gallery section
    $pattern2 = '/<!-- Normale Hunde-Galerie -->.*?<div class="gallery-grid">(.*?)<!-- HINWEIS:.*?-->\s*<\/div>\s*<\/div>/s';
    $replacement2 = '<!-- Normale Hunde-Galerie -->
            <div class="gallery-category" id="normal">
                <h3 style="text-align: center; color: var(--primary); font-size: clamp(1.5rem, 3vw, 2rem); margin-bottom: 2rem;">
                    🐕 Unsere glücklichen Fellnasen
                </h3>
                <div class="gallery-grid">
' . $normalHTML . '                </div>
            </div>';

    $galleryHTML = preg_replace($pattern2, $replacement2, $galleryHTML);

    file_put_contents(GALLERY_HTML, $galleryHTML);
}
?>
