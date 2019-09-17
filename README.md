# Zend Expressive Static Pages Module

An easy, almost painless, way to render static pages in Zend Expressive applications. 

## Getting Started

To install the package, you have two choices:

1. Use Composer

This is currently a proof of concept, so this section is here as a placeholder.

2. Use Git

```console
# First clone the repository to the src directory of your Zend Expressive application. 
# I recommend calling it StaticPages, but you don't have to.
git clone https://github.com/zfmastery/ze-static-page src/StaticPages

# Then, use Zend Expressive Tooling to enable the module
./vendor/bin/expressive module:register StaticPages # or whatever you called it when you cloned the module
```

With the package installed, you now need to do two further steps:

1. Create the relevant static templates inside `src/StaticPages/templates/static-pages`
2. Create relevant routes in config/routes.php

### Programmatic Pipeline Example

Create a new route to render the product's disclosure information.
Note the third parameter, the route's name. This is important.
It has to start with `static.` and end with the applicable template's name, minus the `.phtml` file extension.
As such, assuming that you're using Zend-View as your template layer, this route will attempt to render the contents of: `src/StaticPages/templates/static-pages/disclosure.phtml`. 
If the template doesn't exist, a 404 Not Found page will be rendered. 

```php
$app->get('/disclosure', StaticPages\Action\StaticPagesAction::class, 'static.disclosure');
```
