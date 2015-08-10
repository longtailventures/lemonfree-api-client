<?php

namespace LemonFree\Api\Params;

class Distance
{
    const UPPER_DISTANCE_LIMIT = 4000;
    const DEFAULT_VALUE = '100';

    private static $_distances = array(5, 10, 20, 50, 75, 100, 200, 500, self::UPPER_DISTANCE_LIMIT);

    public static function getDistances($includeLabels = false)
    {
        if ($includeLabels)
        {
            $distances = array();
            foreach (self::$_distances as $distance)
                $distances[$distance] = $distance;

            return $distances;
        }
        else
            return self::$_distances;
    }
}