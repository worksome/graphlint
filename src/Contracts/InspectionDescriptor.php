<?php

declare(strict_types=1);

namespace Worksome\Graphlint\Contracts;

use Worksome\Graphlint\InspectionDescription;

interface InspectionDescriptor
{
    public function definition(): InspectionDescription;
}
