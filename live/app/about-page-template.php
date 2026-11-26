<?php
/* Shared template for the three ASPI About pages. */
if (!function_exists('aspi_about_setting')) {
    function aspi_about_setting(PDO $pdo, string $key, string $fallback = ''): string {
        static $settings = null;
        if ($settings === null) {
            $settings = [];
            try {
                $q = $pdo->query('SELECT setting_key, setting_value FROM site_settings');
                foreach ($q->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $settings[(string)$row['setting_key']] = (string)$row['setting_value'];
                }
            } catch (Throwable $e) { /* graceful fallback */ }
        }
        return (string)($settings[$key] ?? $fallback);
    }
}
if (!function_exists('aspi_about_h')) {
    function aspi_about_h($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$siteName = aspi_about_setting($pdo, 'site_name', 'আসহাব সিরাজ পলিটেকনিক ইনস্টিটিউট');
$siteTagline = aspi_about_setting($pdo, 'tagline', 'একটাই লক্ষ্য, হতে হবে দক্ষ।');
$pageText = aspi_about_setting($pdo, $legacyKey, $legacyFallback);
$pageEditorJson = aspi_about_setting($pdo, $editorKey, '');
$pageEditorHtml = $pageEditorJson !== '' ? aspi_page_render_editor_json($pageEditorJson) : '';

// Hide accidental duplicate section-label/title headings left in older editor content.
if ($pageEditorHtml !== '') {
    $lower = static fn($s) => function_exists('mb_strtolower') ? mb_strtolower((string)$s, 'UTF-8') : strtolower((string)$s);
    $removeHeadings = array_map(static fn($s) => trim(strip_tags((string)$s)), array_merge([$pageTitle], $duplicateHeadings ?? []));
    $pageEditorHtml = preg_replace_callback('~<h([2-4])\b[^>]*>(.*?)</h\1>~is', static function ($m) use ($removeHeadings) {
        $plain = html_entity_decode(trim(strip_tags($m[2])), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $plain = preg_replace('/\s+/u', ' ', $plain);
        return in_array(trim($plain), $removeHeadings, true) ? '' : $m[0];
    }, $pageEditorHtml);
}

$plainLegacy = trim(strip_tags($pageText));
$genericLegacy = ($plainLegacy === '')
    || strpos($plainLegacy, 'শিগগিরই প্রকাশ করা হবে') !== false
    || strpos($plainLegacy, 'তথ্য হালনাগাদ করা হচ্ছে') !== false;
$hasEditorContent = trim(strip_tags($pageEditorHtml)) !== '';
$hasLegacyContent = !$hasEditorContent && !$genericLegacy;
$metaDescription = $hasEditorContent ? $pageTitle . ' — ' . $siteName : ($hasLegacyContent ? substr($plainLegacy, 0, 450) : $pageTitle . ' — ' . $siteName);
?><!doctype html>
<html lang="bn">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#10224b">
<meta name="description" content="<?=aspi_about_h($metaDescription)?>">
<title><?=aspi_about_h($pageTitle)?> | <?=aspi_about_h($siteName)?></title>
<link rel="canonical" href="https://aspi.edu.bd/live/<?=aspi_about_h($canonicalFile)?>">
<link rel="icon" type="image/svg+xml" href="/assets/aspi-logo.svg">
<link rel="preload" as="font" href="/fonts/Hind-Siliguri-Regular.ttf" type="font/ttf" crossorigin>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<link rel="stylesheet" href="/live/assets/public-shell.css?v=1.0.4">
<link rel="stylesheet" href="/live/assets/about-pages.css?v=1.0.0">
<link rel="stylesheet" href="/live/assets/about-page-extras.css?v=1.0.2">
</head>
<body class="aspi-about-page">
<a class="about-skip" href="#main-content">মূল কনটেন্টে যান</a>
<?php /* Exact same shared public header used by the homepage. */ require __DIR__ . '/public-header.php'; ?>
<section class="about-hero">
  <div class="about-container">
    <nav class="about-breadcrumb" aria-label="Breadcrumb"><a href="/">হোম</a><i class="fa-solid fa-chevron-right" aria-hidden="true"></i><span>আমাদের সম্পর্কে</span><i class="fa-solid fa-chevron-right" aria-hidden="true"></i><strong><?=aspi_about_h($pageTitle)?></strong></nav>
    <div class="about-hero-copy"><span class="about-kicker">ASPI · ABOUT US</span><h1><?=aspi_about_h($pageTitle)?></h1><span class="about-accent" aria-hidden="true"></span></div>
  </div>
</section>
<main id="main-content" class="about-main">
  <div class="about-container">
    <article class="about-content-card">
      <?php if ($hasEditorContent): ?>
        <div class="page-editor-content"><?=$pageEditorHtml?></div>
      <?php elseif ($hasLegacyContent): ?>
        <div class="page-editor-content"><p><?=nl2br(aspi_about_h($pageText))?></p></div>
      <?php else: ?>
        <div class="about-empty"><span class="about-empty-icon"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></span><h2>তথ্য হালনাগাদের কাজ চলছে</h2><p>এই অংশের বিস্তারিত তথ্য প্রস্তুত হলে এখানে প্রকাশ করা হবে।</p></div>
      <?php endif; ?>
    </article>
  </div>
</main>
<?php /* Keep the shared dark footer; suppress only the contact cards on About pages. */ $aspiHideContactStrip = true; require __DIR__ . '/public-footer.php'; ?>

</body>
</html>
