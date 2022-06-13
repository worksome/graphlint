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

// TODO: Add homebrew instructions

## Usage
The tool can be run via
```bash
$ graphlint path/to/schema.graphql
```

## Configuration


> ⚠️ Currently the package only supports running on compiled schema.
> It will later get support for running on original schemas also.

Create a file in the root called `graphlint.php` with the following configuration.

```php
declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Worksome\Graphlint\Configuration\Visitor;
use Worksome\Graphlint\Inspections\CamelCaseFieldDefinitionInspection;

return function (ContainerConfigurator $config): void {
    $services = $config->services();

    $services->set(CamelCaseFieldDefinitionInspection::class)
        ->tag(Visitor::COMPILED);
};
```

The tool can have a configuration for schemas before compiling and after.
Some libraries do not compile their schema, so for those only one of the tags should be used.