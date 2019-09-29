<?php
declare(strict_types=1);

/*
 * Licensed under MIT. See file /LICENSE.
 */

namespace Tests\Behat\Transform;

use Behat\Behat\Context\Context;
use shinage\server\behat\Helper\StringInflector;
use Tests\Behat\Service\SharedStorageInterface;

/*
 * This file is inspried by Sylius. Thanks for fantastic open source software!
 * See: https://github.com/Sylius/Sylius/blob/master/src/Sylius/Behat/Service/SharedStorage.php
 */
class SharedStorageTransformerContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @param SharedStorageInterface $sharedStorage */
    public function __construct(SharedStorageInterface $sharedStorage)
    {
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Transform /^(it|its|theirs|them)$/
     *
     * @return mixed
     */
    public function getLatestResource()
    {
        return $this->sharedStorage->getLatestResource();
    }

    /**
     * @Transform /^(?:this|that|the) ([^"]+)$/
     *
     * @return mixed
     */
    public function getResource(string $resource)
    {
        return $this->sharedStorage->get(StringInflector::nameToCode($resource));
    }
}
