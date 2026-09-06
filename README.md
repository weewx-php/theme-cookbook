# Cookbook theme

An optional weewx-php theme with four Apache ECharts examples, a data table and
two embeddable weather widgets. Requires PHP 8.1+ and theme API 1.

## Installation

In **Admin → Themes → Shop**, install **Cookbook** and activate it. For a manual
installation, copy this repository into `themes/cookbook` or configure an
external directory:

```ini
[Themes]
    active = cookbook
    [[cookbook]]
        directory = /srv/weather/themes/theme-cookbook
```

Relative paths resolve against the configuration file. English is the default
language; select German in theme settings or set `language = de` under
`[Themes][[cookbook]]`.

The active theme is rendered at the homepage. `data.php` defines prepared
queries; `feeds.php` publishes `sidebar`, `live` and `charts` through `api/v1.php`
while Cookbook is active. An existing `public-feeds.php` or explicit
`WEEWX_PHP_FEEDS` takes precedence. These example feeds allow cross-origin
requests. The feed client and Apache ECharts are loaded from the core. Chart
recipes and widget presentation belong to this package. Feed labels default to
English; the page translates them for the selected language.

After installing into `themes/cookbook`, run from the core directory:

```sh
php bin/weewx-php --config station.conf analytics sync cookbook themes/cookbook/data.php
php bin/weewx-php --config station.conf analytics run
```

Large initial datasets may need multiple worker runs. Live values require LOOP
input; historical comparisons require sufficiently complete previous years.
The full PHP/API guide is in the core at `docs/theme-cookbook.md`.

## Tests

Use Docker for all checks. Start Docker if it is not running. Run the template
checks against a core checkout:

```sh
WEEWX_PHP_ROOT=/path/to/weewx-php docker compose -f tests/docker/compose.yml run --rm unit
```

For PowerShell, set `$env:WEEWX_PHP_ROOT` before invoking Docker Compose.
