# phpnomad/twig-integration

[![Latest Version](https://img.shields.io/packagist/v/phpnomad/twig-integration.svg)](https://packagist.org/packages/phpnomad/twig-integration)
[![Total Downloads](https://img.shields.io/packagist/dt/phpnomad/twig-integration.svg)](https://packagist.org/packages/phpnomad/twig-integration)
[![PHP Version](https://img.shields.io/packagist/php-v/phpnomad/twig-integration.svg)](https://packagist.org/packages/phpnomad/twig-integration)
[![License](https://img.shields.io/packagist/l/phpnomad/twig-integration.svg)](https://packagist.org/packages/phpnomad/twig-integration)

Integrates [Twig](https://twig.symfony.com/) with PHPNomad's template strategy. It implements `phpnomad/template`'s `CanRender` interface on top of Twig 3, so any code in your application that depends on `CanRender` can render `.twig` files without knowing Twig is doing the work. This is the default templating integration for PHPNomad applications that run outside WordPress.

## Installation

```bash
composer require phpnomad/twig-integration
```

## What this provides

- `TwigEngine`, a `CanRender` implementation that loads templates through Twig's `Environment` and `FilesystemLoader` and auto-appends `.twig` to the template path you pass in.
- `TwigConfigProvider`, an interface your application implements to tell the engine where its template directory lives.
- Exception mapping so a missing template surfaces as `TemplateNotFound` and any other render failure surfaces as `TemplateException`, both from `phpnomad/template`.

## Requirements

- PHP 8.0 or later (inherited from `twig/twig ^3.0`)
- `phpnomad/template ^1.0`
- `twig/twig ^3.0`

## Usage

Implement `TwigConfigProvider` to point at your templates directory, then bind `TwigEngine` to `CanRender` in your bootstrapper's class definitions.

```php
<?php

namespace MyApp\Templates;

use PHPNomad\Twig\Integration\Interfaces\TwigConfigProvider;

class TemplateConfig implements TwigConfigProvider
{
    public function getTemplateDirectory(): string
    {
        return __DIR__ . '/templates';
    }
}
```

```php
<?php

namespace MyApp;

use MyApp\Templates\TemplateConfig;
use PHPNomad\Loader\Interfaces\HasClassDefinitions;
use PHPNomad\Template\Interfaces\CanRender;
use PHPNomad\Twig\Integration\Interfaces\TwigConfigProvider;
use PHPNomad\Twig\Integration\Strategies\TwigEngine;

class TemplateInitializer implements HasClassDefinitions
{
    public function getClassDefinitions(): array
    {
        return [
            TwigEngine::class => CanRender::class,
            TemplateConfig::class => TwigConfigProvider::class,
        ];
    }
}
```

With those bindings in place, anywhere `CanRender` is injected you can call `$template->render('emails/welcome', ['name' => $user->name])` and the engine will resolve `templates/emails/welcome.twig`.

## Documentation

- Bootstrapping and class definitions: [phpnomad.com](https://phpnomad.com)
- Twig template syntax and features: [twig.symfony.com](https://twig.symfony.com/)

## License

MIT. See [LICENSE.txt](LICENSE.txt).
