<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class ZoneContext implements Context
{
    /** @var RepositoryInterface */
    private $zoneRepository;

    public function __construct(RepositoryInterface $zoneRepository)
    {
        $this->zoneRepository = $zoneRepository;
    }

    /**
     * @Transform /^"([^"]+)" zone$/
     * @Transform /^zone "([^"]+)"$/
     * @Transform :zone
     */
    public function getZoneByCode(string $code): ZoneInterface
    {
        return $this->getZoneBy(['code' => $code]);
    }

    /**
     * @Transform /^zone named "([^"]+)"$/
     */
    public function getZoneByName(string $name): ZoneInterface
    {
        return $this->getZoneBy(['name' => $name]);
    }

    /**
     * @Transform /^rest of the world$/
     */
    public function getRestOfTheWorldZone(): ZoneInterface
    {
        $zone = $this->zoneRepository->findOneBy(['code' => 'RoW']);
        Assert::notNull(
            $zone,
            'Rest of the world zone does not exist.'
        );

        return $zone;
    }

    private function getZoneBy(array $parameters): ZoneInterface
    {
        $existingZone = $this->zoneRepository->findOneBy($parameters);
        Assert::notNull(
            $existingZone,
            'Zone does not exist.'
        );

        return $existingZone;
    }
}
