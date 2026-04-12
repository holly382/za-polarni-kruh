# za-polarni-kruh

Vaklafův cestovní deník, statický web generovaný z PHP šablon.

- Živá verze: https://holly382.github.io/za-polarni-kruh/
- `main` větev: zdroje
- `gh-pages` větev: buildnutý výstup (servírováno GitHub Pages)

## Struktura

```
chapters.php       Metadata všech stránek (title, description, og:*, hero)
content/           Těla stránek (jen vnitřek, bez head/footer/nav)
build.php          Renderer: content/ + templates → dist/
deploy.sh          php build.php + push dist/ na gh-pages
photos/            Zdrojové fotky, kopírují se do dist/
style.css          CSS
favicon.svg
og-image.jpg
```

## Přidání nové kapitoly

1. Přidat záznam do `chapters.php` (nejlépe na konec — order = prev/next nav)
2. Vytvořit `content/<slug>.html` s obsahem (jen `<section class="day">...</section>`)
3. `./deploy.sh`

## Build bez deploye

```
php build.php
```

Výstup v `dist/`. Pro lokální preview:

```
php -S 127.0.0.1:8000 -t dist/
```
