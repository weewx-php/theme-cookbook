<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use WeewxPhp\Frontend\Theme;

final class TemplateTest extends TestCase
{
    public function testDefaultLanguageAndGermanTranslationEscapeAttributeText(): void
    {
        foreach (['en', 'de'] as $language) {
            $texts = json_decode(file_get_contents(dirname(__DIR__) . '/locales/' . $language . '.json'), true, flags: JSON_THROW_ON_ERROR);
            $texts['Temperature and humidity over the last 24 hours'] = '\"><script>alert(1)</script>';
            $theme = new Theme(language: $language, texts: $texts);
            ob_start();
            try {
                require dirname(__DIR__) . '/template.php';
                $html = (string) ob_get_contents();
            } finally {
                ob_end_clean();
            }
            self::assertStringContainsString('<html lang="' . $language . '">', $html);
            self::assertStringContainsString($language === 'en' ? 'Chart data table' : 'Diagrammdaten als Tabelle', $html);
            self::assertStringNotContainsString('<script>alert(1)</script>', $html);
            self::assertStringContainsString('theme-assets.php/cookbook/weather-widget.js', $html);
        }
    }
}
