<?php

namespace App\Services\IpGeoBase;

use App\Entity\GeoCities;
use App\Interfaces\LoadDataFromString;

/**
 * Class GeoCitiesFactory
 * @package App\Services\IpGeoBase
 */
class GeoCitiesFactory implements LoadDataFromString
{
    /** @var string */
    protected static $pattern = '/([0-9]+)\t([А-яёЁ\w0-9\W]+)\t([А-яёЁ\w0-9\W]+)\t([А-яёЁ\w0-9\W]+)\t([0-9\.]+)\t([0-9\.]+)/um';

    /**
     * @param string $row
     *
     * @return GeoCities
     */
    public static function loadDataFromString(string $row): GeoCities
    {
        preg_match_all(self::$pattern, $row, $matches);

        $geoCities = (new GeoCities())
            ->setId(intval($matches[1][0]))
            ->setName($matches[2][0])
            ->setRegion($matches[3][0])
            ->setDistrict($matches[4][0])
            ->setLat(floatval($matches[5][0]))
            ->setLng(floatval($matches[6][0]));

        return $geoCities;
    }
}
