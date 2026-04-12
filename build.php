<?php
declare(strict_types=1);

// Static-site build: renders content/*.html with templates into dist/.
// Usage: php build.php [--out=dist]

$baseDir = __DIR__;
$outDir  = $baseDir . '/dist';

foreach ($argv as $arg) {
    if (str_starts_with($arg, '--out=')) {
        $outDir = substr($arg, 6);
        if ($outDir[0] !== '/') $outDir = $baseDir . '/' . $outDir;
    }
}

$pages = require $baseDir . '/chapters.php';

// Wipe and recreate output directory
if (is_dir($outDir)) rrm($outDir);
mkdir($outDir, 0777, true);

// Ordered chapter slugs for prev/next
$chapterSlugs = [];
foreach ($pages as $slug => $m) {
    if (($m['layout'] ?? '') === 'chapter') $chapterSlugs[] = $slug;
}

foreach ($pages as $slug => $m) {
    $content = file_get_contents("{$baseDir}/content/{$slug}.html");
    if ($content === false) {
        fwrite(STDERR, "Missing content/{$slug}.html\n");
        exit(1);
    }
    $html = render_page($slug, $m, $content, $pages, $chapterSlugs);
    file_put_contents("{$outDir}/{$slug}.html", $html);
}

// Copy static assets
foreach (['style.css', 'favicon.svg', 'og-image.jpg'] as $asset) {
    $src = "{$baseDir}/{$asset}";
    if (file_exists($src)) copy($src, "{$outDir}/{$asset}");
}

// Copy photos/
rcopy("{$baseDir}/photos", "{$outDir}/photos");

fwrite(STDERR, "Built " . count($pages) . " pages → {$outDir}\n");


// ---------- rendering ----------

function render_page(string $slug, array $m, string $content, array $pages, array $chapterSlugs): string
{
    $head = render_head($m);
    $foot = render_footer();

    if (($m['layout'] ?? '') === 'chapter') {
        $topNav = '<nav class="top-nav"><a href="index.html">&larr; Zpátky na přehled</a></nav>';
        $hero   = render_chapter_hero($m);
        $dayNav = render_day_nav($slug, $pages, $chapterSlugs);
        $main   = "<main>\n" . $content . "\n" . $dayNav . "\n</main>";
        return $head . "\n\n" . $topNav . "\n\n" . $hero . "\n\n" . $main . "\n\n" . $foot;
    }

    // page layout (homepage, other static pages): content contains its own sections
    return $head . "\n\n" . $content . "\n" . $foot;
}

function render_head(array $m): string
{
    $title          = $m['title'];
    $description    = $m['description'];
    $ogTitle        = $m['og_title'];
    $ogDescription  = $m['og_description'];
    $ogUrl          = $m['og_url'];
    $ogType         = $m['og_type'];

    return <<<HTML
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title}</title>
    <meta name="description" content="{$description}">
    <meta property="og:title" content="{$ogTitle}">
    <meta property="og:description" content="{$ogDescription}">
    <meta property="og:image" content="https://holly382.github.io/za-polarni-kruh/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="{$ogUrl}">
    <meta property="og:type" content="{$ogType}">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="favicon.svg" type="image/svg+xml">
    <link rel="stylesheet" href="style.css">
</head>
<body>
HTML;
}

function render_chapter_hero(array $m): string
{
    $h1       = $m['hero_h1'];
    $subtitle = $m['hero_subtitle'];
    $dates    = $m['hero_dates'];

    return <<<HTML
<header class="hero hero-day">
    <div class="hero-overlay">
        <h1>{$h1}</h1>
        <p class="subtitle">{$subtitle}</p>
        <p class="dates">{$dates}</p>
    </div>
</header>
HTML;
}

function render_day_nav(string $slug, array $pages, array $chapterSlugs): string
{
    $i = array_search($slug, $chapterSlugs, true);
    $prevSlug = $i > 0 ? $chapterSlugs[$i - 1] : null;
    $nextSlug = $i < count($chapterSlugs) - 1 ? $chapterSlugs[$i + 1] : null;

    $prev = $prevSlug !== null
        ? '<a href="' . $prevSlug . '.html">&larr; ' . $pages[$prevSlug]['chapter_label'] . '</a>'
        : '<span class="disabled">&larr; Předchozí kapitola</span>';

    $next = $nextSlug !== null
        ? '<a href="' . $nextSlug . '.html">' . $pages[$nextSlug]['chapter_label'] . ' &rarr;</a>'
        : '<span class="disabled">Další kapitola &rarr;</span>';

    return "    <nav class=\"day-nav\">\n        {$prev}\n        {$next}\n    </nav>";
}

function render_footer(): string
{
    return <<<HTML
<footer>
    <p class="footer-main">Vaklafův cestovní deník &middot; Za polární kruh 2026</p>
    <p class="footer-sub">Psáno s láskou a sarkasmem. Vaklaf dodává zážitky, Holly dodává slova.<br>Žádné AI nebylo při tvorbě tohoto deníku zraněno (jen trochu přetaktováno).</p>
</footer>

<script data-goatcounter="https://za-polarni-kruh.goatcounter.com/count"
        async src="//gc.zgo.at/count.js"></script>
</body>
</html>

HTML;
}


// ---------- filesystem helpers ----------

function rcopy(string $src, string $dst): void
{
    if (!is_dir($src)) return;
    if (!is_dir($dst)) mkdir($dst, 0777, true);
    foreach (scandir($src) as $f) {
        if ($f === '.' || $f === '..') continue;
        $s = "{$src}/{$f}";
        $d = "{$dst}/{$f}";
        if (is_dir($s)) rcopy($s, $d);
        else copy($s, $d);
    }
}

function rrm(string $path): void
{
    if (!file_exists($path)) return;
    if (is_file($path) || is_link($path)) { unlink($path); return; }
    foreach (scandir($path) as $f) {
        if ($f === '.' || $f === '..') continue;
        rrm("{$path}/{$f}");
    }
    rmdir($path);
}
