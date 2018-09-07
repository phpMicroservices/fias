<?php

namespace App\Services\IpGeoBase;

use App\Entity\GeoBase;
use App\Entity\GeoCities;
use App\Exceptions\FactoryNotFoundException;

/**
 * Class FactoryEntityStrategy
 * @package App\Services\IpGeoBase
 */
class EntityStrategy
{
    /**
     * @param string $row
     * @param string $fileName
     *
     * @throws FactoryNotFoundException
     *
     * @return mixed
     */
    public static function createFromRowByFileName(string $row, string $fileName)
    {
        if ($fileName === 'cities.txt') {
            return GeoCitiesFactory::loadDataFromString($row);
        }

        if ($fileName === 'cidr_optim.txt') {
            return GeoBaseFactory::loadDataFromString($row);
        }

        throw new FactoryNotFoundException("factory by file name {$fileName} is not found!");
    }

    /**
     * @param string $fileName
     *
     * @throws FactoryNotFoundException
     *
     * @return string
     */
    public static function getClassNameByFileName(string $fileName): string
    {
        if ($fileName === 'cities.txt') {
            return GeoCities::class;
        }

        if ($fileName === 'cidr_optim.txt') {
            return GeoBase::class;
        }

        throw new FactoryNotFoundException("factory by file name {$fileName} is not found!");
    }
}
