<?php

// Order matters: prev/next navigation follows this order (for chapters only).
// Add a new chapter by appending a new entry with layout='chapter'.

return [

    'index' => [
        'layout'         => 'page',
        'title'          => 'Za polární kruh &mdash; Vaklafův cestovní deník',
        'description'    => 'Vaklafův cestovní deník z výpravy za polární kruh. Psáno se salámovým nadhledem.',
        'og_title'       => 'Za polární kruh — Vaklafův cestovní deník',
        'og_description' => 'Vaklafův cestovní deník z výpravy za polární kruh. Psáno se salámovým nadhledem.',
        'og_url'         => 'https://holly382.github.io/za-polarni-kruh/',
        'og_type'        => 'website',
    ],

    'den-1-cesta-za-polarni-kruh' => [
        'layout'         => 'chapter',
        'chapter_label'  => 'Kapitola 1',
        'title'          => 'Kapitola 1 &mdash; Cesta za polární kruh',
        'description'    => 'Čtyřicet hodin na cestě: Růžďka, Havířov, Kraków, Oslo, Bodø, Pollfjellet, Nordskot.',
        'og_title'       => 'Kapitola 1 — Cesta za polární kruh',
        'og_description' => 'Čtyřicet hodin na cestě: Růžďka, Havířov, Kraków, Oslo, Bodø, Pollfjellet, Nordskot.',
        'og_url'         => 'https://holly382.github.io/za-polarni-kruh/den-1-cesta-za-polarni-kruh.html',
        'og_type'        => 'article',
        'hero_h1'        => 'Kapitola 1 &mdash; Cesta za polární kruh',
        'hero_subtitle'  => 'Z Růžďky na sever, čtyřicet hodin na cestě',
        'hero_dates'     => '10.&ndash;11. dubna 2026 &middot; Růžďka &rarr; Havířov &rarr; Kraków &rarr; Oslo &rarr; Bodø &rarr; Pollfjellet &rarr; Nordskot',
    ],

    'den-2-po-prijezdu-na-manshausen' => [
        'layout'         => 'chapter',
        'chapter_label'  => 'Kapitola 2',
        'title'          => 'Kapitola 2 &mdash; Manshausen',
        'description'    => 'Darek na molu, Michelin klíč na dveřích, whisky do tří ráno a ohořelá bouda se skleněným stropem.',
        'og_title'       => 'Kapitola 2 — Manshausen',
        'og_description' => 'Darek na molu, Michelin klíč na dveřích, whisky do tří ráno a ohořelá bouda se skleněným stropem.',
        'og_url'         => 'https://holly382.github.io/za-polarni-kruh/den-2-po-prijezdu-na-manshausen.html',
        'og_type'        => 'article',
        'hero_h1'        => 'Kapitola 2 &mdash; Manshausen',
        'hero_subtitle'  => 'Příjezd &middot; Whisky &middot; Ohořelá bouda &middot; Ryby',
        'hero_dates'     => '11.&ndash;12. dubna 2026',
    ],

];
