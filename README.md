# Laminas Static Pages Module

![testing workflow](https://github.com/settermjd/laminas-static-pages/actions/workflows/continuous-integration.yml/badge.svg)

An, almost, painless way to render static pages in [Mezzio](https://docs.mezzio.dev/mezzio/) applications.

**Note:** This module **does not** support laminas-mvc applications.

The intent of this package is to avoid the necessity to create handlers and handler factories just to render static content.
It was motivated by various projects that I've worked on, where that seemed to be the case, at least at the time.
That approach never made sense to me, so that's that motivated me to scratch my own itch.

## Getting Started

To install the package, run `composer require settermjd/laminas-static-pages`.

If you want to automate the enabling of the module when running `composer require/install/update`, then your project needs to use [laminas/laminas-component-installer].
If it does, when the package is installed you'll be asked if you want to enable its ConfigProvider.
Answer with `Y` and the package will be ready to use.

If you don't use `laminas-component-installer`, or for some reason or other can't, then ensure that `\StaticPages\ConfigProvider::class,` is in the `ConfigAggregator` list in `config/config.php`, as in the example below.

```php
$aggregator = new ConfigAggregator([
    \StaticPages\ConfigProvider::class,
]);
```

With the package installed, you now need to do two further steps:

1. Configure the template path
2. Create routes
3. Create template files

### Configure The Template Path

To configure the template path, ensure that in your template paths list, there's one with the key `static-pages`, as in the example below.

```php
public function getTemplates() : array
{
    return [
        'paths' => [
            'static-pages' => [__DIR__ . '/../templates/static-pages'],
        ],
    ];
}
```

### Create Routes

To create a route for a static page, in your routing table, add one or more named routes where:

1. The route’s handler is `StaticPagesHandler::class`
2. The name follows the convention: `static.<template_file_name_minus_file_extension>`.

Let's assume that we are adding a route for a privacy page and that the template file which will be rendered is `privacy.phtml`.
In that case we'd add the following to `config/routes.php`:

```php
$app->get('/privacy', StaticPagesHandler::class, 'static.privacy');
```

### Create Template Files

The file can contain whatever you like, it doesn't matter.

### That’s It

All being well, this should be all that you need to rapidly serve static content files in your [Mezzio](https://docs.mezzio.dev/mezzio/) applications.

[laminas/laminas-component-installer]: https://github.com/laminas/laminas-component-installer

## Support

- [Issues](https://github.com/settermjd/laminas-static-pages/issues)
