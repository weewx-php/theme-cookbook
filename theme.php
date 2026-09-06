<?php

declare(strict_types=1);

use WeewxPhp\Frontend\Theme;
use WeewxPhp\Frontend\Weather;

return static function (Weather $wx, Theme $theme): string {
    ob_start();
    try {
        require __DIR__ . '/template.php';
        return (string) ob_get_contents();
    } finally {
        ob_end_clean();
    }
};
