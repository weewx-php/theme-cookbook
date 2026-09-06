<?php

declare(strict_types=1);

use WeewxPhp\Frontend\Theme;

$theme ??= new Theme();

?><!doctype html>
<html lang="<?= htmlspecialchars($theme->language, ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $theme->html('Theme cookbook · Examples') ?></title>
    <link rel="stylesheet" href="theme-assets.php/cookbook/cookbook.css">
    <script defer src="assets/vendor/echarts/echarts.min.js"></script>
    <script type="module" src="theme-assets.php/cookbook/cookbook.js"></script>
    <script type="module" src="theme-assets.php/cookbook/weather-widget.js"></script>
</head>
<body data-texts="<?= htmlspecialchars(json_encode($theme->texts, JSON_THROW_ON_ERROR), ENT_QUOTES, 'UTF-8') ?>" data-api="api/v1.php?feed=charts">
<header><a href="./"><?= $theme->html('Weather station') ?></a><span><?= $theme->html('Theme cookbook') ?></span><a href="api/v1.php?feed=charts"><?= $theme->html('JSON API') ?></a></header>
<main>
    <h1><?= $theme->html('Weather data with Apache ECharts') ?></h1>
    <p id="chart-status" role="status"><?= $theme->html('Loading …') ?></p>
    <div class="charts">
        <section><h2><?= $theme->html('Temperature & humidity') ?></h2><p data-status="temperature24h"></p><div data-chart="temperature" class="chart" role="img" aria-label="<?= $theme->html('Temperature and humidity over the last 24 hours') ?>"></div></section>
        <section><h2><?= $theme->html('Rainfall · 7 calendar days') ?></h2><p data-status="rain7d"></p><div data-chart="rain" class="chart" role="img" aria-label="<?= $theme->html('Rainfall per calendar day') ?>"></div></section>
        <section><h2><?= $theme->html('Rainfall · cumulative over 24 h') ?></h2><p data-status="rain24h"></p><div data-chart="cumulative" class="chart" role="img" aria-label="<?= $theme->html('Cumulative rainfall; stops at data gaps') ?>"></div></section>
        <section><h2><?= $theme->html('Month compared with previous years') ?></h2><p data-status="rainComparison"></p><div data-chart="comparison" class="chart" role="img" aria-label="<?= $theme->html('Monthly rainfall in previous years up to the same calendar time') ?>"></div></section>
    </div>
    <section class="embed"><h2><?= $theme->html('Sidebar widgets') ?></h2><div class="widgets">
        <weewx-weather api="api/v1.php?feed=sidebar" title="<?= $theme->html('Last archive reading') ?>"></weewx-weather>
        <weewx-weather api="api/v1.php?feed=live" title="Live"></weewx-weather>
    </div></section>
    <details><summary><?= $theme->html('Chart data table') ?></summary><div class="table-scroll"><table id="chart-table"><thead><tr><th><?= $theme->html('Series') ?></th><th><?= $theme->html('Start') ?></th><th><?= $theme->html('End') ?></th><th><?= $theme->html('Value') ?></th><th><?= $theme->html('Unit') ?></th><th><?= $theme->html('Coverage') ?></th></tr></thead><tbody></tbody></table></div></details>
    <noscript><?= $theme->html('JavaScript is required for charts and widgets.') ?></noscript>
</main>
</body></html>
