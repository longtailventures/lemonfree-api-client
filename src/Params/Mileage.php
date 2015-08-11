<?php

namespace LemonFree\Api\Params;

class Mileage
{
    const LOWER_MILEAGE_RANGE = 1;
    const UPPER_MILEAGE_RANGE = 9999999;

    private static $_mileageRanges = array(
        self::LOWER_MILEAGE_RANGE,
        20000, 30000, 40000, 50000, 60000, 70000, 80000, 90000,
        100000, 110000, 120000, 130000, 140000, 150000, 160000, 170000, 180000, 190000,
        200000, 500000,
        self::UPPER_MILEAGE_RANGE
    );

    public static function getUrlParam($mileageA, $mileageB, $separator = '-')
    {
        $urlParam = '';

        $isMileageAValid = in_array($mileageA, self::$_mileageRanges);
        $isMileageBValid = in_array($mileageB, self::$_mileageRanges);

        if ($isMileageAValid && $isMileageBValid)
        {
            if ($mileageA === $mileageB)
                $urlParam = $mileageA;
            else
            {
                $mileageParams[] = $mileageA;
                $mileageParams[] = $mileageB;

                sort($mileageParams);
                $urlParam = implode($separator, $mileageParams);

                if ($urlParam === self::LOWER_MILEAGE_RANGE . $separator . self::UPPER_MILEAGE_RANGE)
                    $urlParam = '';
            }
        }
        else if ($isMileageAValid && $mileageB === '')
            $urlParam = $mileageA . $separator . self::UPPER_MILEAGE_RANGE;
        else if ($isMileageBValid && $mileageA === '')
            $urlParam = self::LOWER_MILEAGE_RANGE . $separator . $mileageB;

        return $urlParam;
    }


    public static function getParams($mileage, $separator = '-')
    {
        $mileageParams = array('from' => '', 'to' => '');

        if (empty($mileage))
            return $mileageParams;

        $mileages = explode($separator, $mileage);
        if (count($mileages) == 2)
        {
            if (!in_array('', $mileages) && ctype_digit($mileages[0]) && ctype_digit($mileages[1]))
                sort($mileages);

            $mileageA = !empty($mileages[0]) ? $mileages[0] : self::LOWER_MILEAGE_RANGE;
            $mileageB = !empty($mileages[1]) ? $mileages[1] : self::UPPER_MILEAGE_RANGE;

            if ($mileageA == self::LOWER_MILEAGE_RANGE && $mileageB == self::UPPER_MILEAGE_RANGE)
                return $mileageParams;
            else
            {
                $mileageParams['from'] = $mileageA;
                $mileageParams['to'] = $mileageB;
            }
        }
        else
        {
            $mileageParams['from'] = $mileages[0];
            $mileageParams['to'] = $mileages[0];
        }

        return $mileageParams;
    }


    public static function getMileageRanges($includeLabels = false, $sortDesc = false)
    {
        $mileages = array();

        if ($includeLabels)
        {
            foreach (self::$_mileageRanges as $mileage)
            {
                if ($sortDesc)
                {
                    if ($mileage > 1 && $mileage < self::UPPER_MILEAGE_RANGE)
                        $mileages[number_format($mileage)] = $mileage;
                    else if ($mileage == self::UPPER_MILEAGE_RANGE)
                        $mileages['>500,000'] = $mileage;
                }
                else
                {
                    if ($mileage >= self::LOWER_MILEAGE_RANGE && $mileage < self::UPPER_MILEAGE_RANGE)
                        $mileages[number_format($mileage)] = $mileage;
                }
            }
        }
        else
            $mileages = self::$_mileageRanges;

        if ($sortDesc)
            $mileages = array_reverse($mileages, $includeLabels);

        return $mileages;
    }


    public static function roundMileage($mileageToRound)
    {
        $roundedMileage = $mileageToRound;

        if (empty($mileageToRound))
            return self::LOWER_MILEAGE_RANGE;

        if (!is_numeric($mileageToRound))
            return $roundedMileage;

        foreach (self::$_mileageRanges as $i => $mileage)
        {
            $minValue = $mileage;
            $maxValue = $mileage < self::UPPER_MILEAGE_RANGE
                ? self::$_mileageRanges[$i+1]
                : null;

            if ($maxValue === null)
                return $mileage;

            if ($maxValue == self::UPPER_MILEAGE_RANGE)
                return $maxValue;

            if ($mileageToRound >= $minValue && $mileageToRound <= $maxValue)
            {
                $midPoint = $mileage + (($maxValue - $minValue) / 2);
                if ($mileageToRound < $midPoint)
                    return $minValue;
                else
                    return $maxValue;
            }
        }
    }
}