<?php

declare(strict_types=1);

namespace Worksome\Graphlint;

class InspectionDescription
{
    public function __construct(
        private string $title,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
