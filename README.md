<div align="center">

# Value objects for strictly typed TYPO3 configuration

[![Coverage](https://img.shields.io/coverallsCoverage/github/eliashaeussler/typo3-config-objects?logo=coveralls)](https://coveralls.io/github/eliashaeussler/typo3-config-objects)
[![CGL](https://img.shields.io/github/actions/workflow/status/eliashaeussler/typo3-config-objects/cgl.yaml?label=cgl&logo=github)](https://github.com/eliashaeussler/typo3-config-objects/actions/workflows/cgl.yaml)
[![Tests](https://img.shields.io/github/actions/workflow/status/eliashaeussler/typo3-config-objects/tests.yaml?label=tests&logo=github)](https://github.com/eliashaeussler/typo3-config-objects/actions/workflows/tests.yaml)
[![Supported PHP Versions](https://img.shields.io/packagist/dependency-v/eliashaeussler/typo3-config-objects/php?logo=php)](https://packagist.org/packages/eliashaeussler/typo3-config-objects)

</div>

This library provides custom value objects for strictly typed [TYPO3](https://typo3.org/) configuration.
They can be used to replace the usage of arrays in configuration files, such as `Confguration/Icons.php`.

## 🔥 Installation

[![Packagist](https://img.shields.io/packagist/v/eliashaeussler/typo3-config-objects?label=version&logo=packagist)](https://packagist.org/packages/eliashaeussler/typo3-config-objects)
[![Packagist Downloads](https://img.shields.io/packagist/dt/eliashaeussler/typo3-config-objects?color=brightgreen)](https://packagist.org/packages/eliashaeussler/typo3-config-objects)

```bash
composer require eliashaeussler/typo3-config-objects
```

## ⚡ Usage

It's pretty easy: Replace arrays in your configuration files with value objects 💅.
The following configuration classes are available:

* [`IconConfiguration`](#iconconfiguration-for-configurationiconsphp)
* [`MiddlewareConfiguration`](#middlewareconfiguration-for-configurationrequestmiddlewaresphp)

### [`IconConfiguration`](src/Configuration/IconConfiguration.php) (for `Configuration/Icons.php`)

**Before:**

```php
// Configuration/Icons.php

use TYPO3\CMS\Core;

return [
    'tx-example-my-icon' => [
        'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:example/Resources/Public/Icons/my-icon.svg',
    ],
    'tx-example-my-second-icon' => [
        'provider' => Core\Imaging\IconProvider\BitmapIconProvider::class,
        'source' => 'EXT:example/Resources/Public/Icons/my-second-icon.jpg',
    ],
    'tx-example-my-legacy-icon' => [
        'provider' => Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:example/Resources/Public/Icons/my-legacy-icon.svg',
        'deprecated' => [
            'since' =>  'TYPO3 v12',
            'until' => 'TYPO3 v13',
            'replacement' => 'tx-example-my-new-icon',
        ],
    ],
    'tx-example-my-new-icon' => [
        'provider' => \Vendor\Example\Imaging\MyCustomIconProvider::class,
        'renderer' => \Vendor\Example\Renderer\MyCustomIconRenderer::class,
    ],
];
```

**After:**

```php
// Configuration/Icons.php

use EliasHaeussler\Typo3ConfigObjects;

return Typo3ConfigObjects\Configuration\IconConfiguration::create()
    // Add a list of icons to configure
    ->add(
        Typo3ConfigObjects\ValueObject\Icon::create('tx-example-my-icon')
            ->useSvgIconProvider('EXT:example/Resources/Public/Icons/my-icon.svg'),
        Typo3ConfigObjects\ValueObject\Icon::create('tx-example-my-second-icon')
            ->useBitmapIconProvider('EXT:example/Resources/Public/Icons/my-second-icon.jpg')
    )

    // You can also use deprecated icons
    ->add(
        Typo3ConfigObjects\ValueObject\Icon::create('tx-example-my-legacy-icon')
            ->useSvgIconProvider('EXT:example/Resources/Public/Icons/my-legacy-icon.svg')
            ->setDeprecated(
                new Typo3ConfigObjects\ValueObject\DeprecatedIcon(
                    since: 'TYPO3 v12',
                    until: 'TYPO3 v13',
                    replacement: 'tx-example-my-new-icon',
                ),
            )
    )

    // You can also use custom icon providers and custom options
    ->add(
        Typo3ConfigObjects\ValueObject\Icon::create('tx-example-my-new-icon')
            ->useCustomIconProvider(\Vendor\Example\Imaging\MyCustomIconProvider::class)
            ->addOption('renderer', \Vendor\Example\Renderer\MyCustomIconRenderer::class)
    )

    // Don't forget to return an array representation (TYPO3 expects an array to be returned)
    ->toArray();
```

### [`MiddlewareConfiguration`](src/Configuration/MiddlewareConfiguration.php) (for `Configuration/RequestMiddlewares.php`)

**Before:**

```php
// Configuration/RequestMiddlewares.php

use TYPO3\CMS\Core;

return [
    'backend' => [
        'vendor/extension/my-middleware' => [
            'target' => \Vendor\Example\Middleware\MyMiddleware::class,
        ],
        'vendor/extension/my-other-middleware' => [
            'target' => \Vendor\Example\Middleware\MyOtherMiddleware::class,
            'before' => [
                'vendor/extension/my-middleware',
            ],
            'after' => [
                'typo3/cms-backend/authentication',
            ],
        ],
    ],
    'frontend' => [
        'typo3/cms-redirects/redirecthandler' => [
            'disabled' => true,
        ],
    ],
];
```

**After:**

```php
// Configuration/RequestMiddlewares.php

use EliasHaeussler\Typo3ConfigObjects;

return Typo3ConfigObjects\Configuration\MiddlewareConfiguration::create()
    // Add a list of middlewares
    ->addToBackendStack(
        Typo3ConfigObjects\ValueObject\RequestMiddleware::create('vendor/extension/my-middleware')
            ->setTarget(\Vendor\Example\Middleware\MyMiddleware::class),
        Typo3ConfigObjects\ValueObject\RequestMiddleware::create('vendor/extension/my-other-middleware')
            ->setTarget(\Vendor\Example\Middleware\MyOtherMiddleware::class)
            ->before('vendor/extension/my-middleware')
            ->after('typo3/cms-backend/authentication')
    )

    // You can also disable existing middlewares
    ->addToFrontendStack(
        Typo3ConfigObjects\ValueObject\RequestMiddleware::create('typo3/cms-redirects/redirecthandler')
            ->disable()
    )

    // Don't forget to return an array representation (TYPO3 expects an array to be returned)
    ->toArray();
```

## 🧑‍💻 Contributing

Please have a look at [`CONTRIBUTING.md`](CONTRIBUTING.md).

## ⭐ License

This project is licensed under [GNU General Public License 2.0 (or later)](LICENSE).
