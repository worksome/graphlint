# GraphLint

A linting tool for GraphQL schemas.

This tool is meant for finding errors in your GraphQL schemas.
It is not made for your Queries.
The tool contains multiple inspections which can be added to the user's config file for checking for different things.
The purpose of this tool is
to implement the [GraphQL Standard from Worksome](https://github.com/worksome/graphql-standards).

## Installation

The tool can be installed as a composer global dependency via

```bash
$ composer global require worksome/graphlint
```

or via Homebrew

```bash
brew tap worksome/tap
brew install --formula worksome/tap/graphlint
```

## Usage

The tool can be run via

```bash
$ graphlint path/to/schema.graphql
```

### CI Usage with GitHub Actions

With GitHub Actions, we support using the [`cs2pr`](https://github.com/staabm/annotate-pull-request-from-checkstyle)
tool to add inline annotations to your pull requests.

```bash
graphlint --format=checkstyle path/to/schema.graphql | cs2pr
```

## Configuration

> ⚠️ Currently the package only supports running on compiled schema.
> It will later get support for running on original schemas also.

Create a file in the root called `graphlint.php` with the following configuration.

```php
<?php declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\Configuration\Visitor;
use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;

return function (ContainerConfigurator $config): void {
    $services = $config->services();

    $services->set(CamelCaseFieldDefinitionInspection::class)
        ->tag(Visitor::COMPILED);
};
```

To use the Worksome GraphQL standard:

```php
<?php declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\GraphlintSet;

return function (ContainerConfigurator $config): void {
    $config->import(GraphlintSet::Standard->value);
};
```

The tool can have a configuration for schemas before compiling and after.
Some libraries do not compile their schema, so for those only one of the tags should be used.

### Ignoring problems

A problem can be suppressed by adding an `ignore-next-line` comment before it.

```graphql
interface Account {
    # @graphlint-ignore-next-line
    id: ID
}
```

In some cases, it is not possible to add a comment because the schema is auto generated. For
those cases, the error can be ignored by adding the following in the configuration file.

```php
return function (ContainerConfigurator $config): void {
    $config->services()
        ->set(IgnoreByNameSuppressorInspection::class)
        ->call('configure', [
            'TEST',
            'AccountInput.name' // Dotted value for only applying on some fields
        ]);
};
```

## Testing

This package ships with a docker configuration for running the tests.
Assuming you have cloned the repository and have docker and docker-compose installed, you can run the tests by running

```bash
docker-compose run --rm composer install # Only needed the first time
docker-compose run --rm pest
```
