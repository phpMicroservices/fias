<?php

namespace App\Services\IpGeoBase;

use App\Entity\GeoBase;
use App\Interfaces\LoadDataFromString;

/**
 * Class GeoBaseFactory
 * @package App\Services\IpGeoBase
 */
class GeoBaseFactory implements LoadDataFromString
{
    /** @var string */
    protected static $pattern = '/(\d+)\t(\d+)\t([0-9\.?]+)\W-\W([0-9\.?]+)\t(\w+)\t([0-9\-]+)/m';

    /**
     * @param string $row
     *
     * @return GeoBase
     */
    public static function loadDataFromString(string $row): GeoBase
    {
        preg_match_all(self::$pattern, $row, $matches);

        $geoBase = (new GeoBase())
            ->setLongIp1(intval($matches[1][0]))
            ->setLongIp2(intval($matches[2][0]))
            ->setIp1($matches[3][0])
            ->setIp2($matches[4][0])
            ->setCountryCode($matches[5][0]);

        if (is_numeric($matches[5][0])) {
            $geoBase->setCityId(intval($matches[6][0]));
        }

        return $geoBase;
    }
}
