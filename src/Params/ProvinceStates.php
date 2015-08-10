<?php

namespace LemonFree\Api\Params;

class ProvinceStates extends LemonFree\Api\ProvinceStates
{
    public static function getProvinces($includeLabels = false)
    {
        $provinces = array();

        $result = parent::getProvinces();

        if ($includeLabels)
        {
            foreach ($result as $abbreviation => $name)
                $provinces[$name] = $abbreviation;
        }
        else
            $provinces = $result;

        return $provinces;
    }


    public static function getStates($includeLabels = false)
    {
        $states = array();

        $result = parent::getStates();

        if ($includeLabels)
        {
            foreach ($result as $abbreviation => $name)
                $states[$name] = $abbreviation;
        }
        else
            $states = $result;

        return $states;
    }


    public static function getProvinceStates($isCanada = false, $includeLabels = false)
    {
        return $isCanada
            ? array_merge(self::getProvinces($includeLabels), self::getStates($includeLabels))
            : array_merge(self::getStates($includeLabels), self::getProvinces($includeLabels));
    }
}