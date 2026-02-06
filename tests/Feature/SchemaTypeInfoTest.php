<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Tests\Feature;

use GraphQL\Language\AST\ObjectTypeDefinitionNode;
use GraphQL\Language\Parser;
use GraphQL\Utils\BuildSchema;
use Worksome\Graphlint\Analyser\Analyser;
use Worksome\Graphlint\InspectionDescription;
use Worksome\Graphlint\Inspections\Inspection;
use Worksome\Graphlint\ProblemsHolder;
use Worksome\Graphlint\Visitors\CompiledVisitorCollector;

it('provides type info to inspections when schema is available', function () {
    $schemaSdl = <<<'SDL'
type Query {
  user: User
}

type User {
  id: ID
}
SDL;

    $inspection = new class() extends Inspection {
        public bool $sawTypeInfo = false;

        public function visitObjectTypeDefinition(
            ProblemsHolder $problemsHolder,
            ObjectTypeDefinitionNode $objectTypeDefinitionNode,
        ): void {
            $this->sawTypeInfo = $this->typeInfo() !== null;
        }

        public function definition(): InspectionDescription
        {
            return new InspectionDescription('Schema type info is available.');
        }
    };

    $analyser = new Analyser();
    $analyser->analyse(
        Parser::parse($schemaSdl),
        new CompiledVisitorCollector([$inspection], []),
        BuildSchema::build($schemaSdl),
    );

    expect($inspection->sawTypeInfo)->toBeTrue();
});
