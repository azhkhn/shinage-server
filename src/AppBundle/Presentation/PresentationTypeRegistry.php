<?php
declare(strict_types=1);

/*
 * Copyright 2018 by Michael Zapf.
 * Licensed under MIT. See file /LICENSE.
 */

namespace AppBundle\Presentation;

use AppBundle\Exceptions\PresentationTypeNotFoundException;

class PresentationTypeRegistry implements PresentationTypeRegistryInterface
{
    /** @var string[]|array */
    private $types = [];

    public function addPresentationType(PresentationTypeInterface $type): void
    {
        $this->types[$type->getSlug()] = $type;
    }

    public function getPresentationType(string $slug): PresentationTypeInterface
    {
        if (isset($this->types[$slug])) {
            return $this->types[$slug];
        }

        throw new PresentationTypeNotFoundException($slug);
    }

    /**
     * @return string[]|array
     */
    public function getPresentationTypes(): array
    {
        return $this->types;
    }
}
