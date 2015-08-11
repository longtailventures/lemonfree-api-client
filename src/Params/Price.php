<?php

namespace LemonFree\Api\Params;

class Price
{
    const LOWER_PRICE_RANGE = 0;
    const UPPER_PRICE_RANGE = 9999999;

    private static $_priceRanges = array(
        0, 500, 1000, 2000, 3000, 4000, 5000, 6000, 7000, 8000, 9000, 10000, 11000, 12000, 13000, 14000, 15000, 16000,
        17000, 18000, 19000, 20000, 22000, 24000, 25000, 26000, 28000, 30000, 35000, 40000, 50000, 60000, 70000, 80000,
        90000, 100000, 9999999
    );

    public static function getUrlParam($priceA, $priceB, $separator = '-')
    {
        $urlParam = '';

        $isPriceAValid = in_array($priceA, self::$_priceRanges);
        $isPriceBValid = in_array($priceB, self::$_priceRanges);

        if ($isPriceAValid && $isPriceBValid)
        {
            if ($priceA === $priceB)
                $urlParam = $priceA;
            else
            {
                $priceParams[] = $priceA;
                $priceParams[] = $priceB;

                sort($priceParams);
                $urlParam = implode($separator, $priceParams);

                if ($urlParam === self::LOWER_PRICE_RANGE . $separator . self::UPPER_PRICE_RANGE)
                    $urlParam = '';
            }
        }
        else if ($isPriceAValid && $priceB === '')
            $urlParam = $priceA . $separator . self::UPPER_PRICE_RANGE;
        else if ($isPriceBValid && $priceA === '')
            $urlParam = self::LOWER_PRICE_RANGE . $separator . $priceB;

        return $urlParam;
    }


    public static function getParams($price, $separator = '-')
    {
        $priceParams = array('from' => '', 'to' => '');

        if (empty($price))
            return $priceParams;

        $prices = explode($separator, $price);
        if (count($prices) == 2)
        {
            if (!in_array('', $prices) && ctype_digit($prices[0]) && ctype_digit($prices[1]))
                sort($prices);

            $priceA = !empty($prices[0]) ? $prices[0] : self::LOWER_PRICE_RANGE;
            $priceB = !empty($prices[1]) ? $prices[1] : self::UPPER_PRICE_RANGE;

            if ($priceA == self::LOWER_PRICE_RANGE && $priceB == self::UPPER_PRICE_RANGE)
                return $priceParams;
            else
            {
                $priceParams['from'] = $priceA;
                $priceParams['to'] = $priceB;
            }
        }
        else if (count($prices) == 1)
        {
            $priceParams['from'] = $prices[0];
            $priceParams['to'] = $prices[0];
        }

        return $priceParams;
    }


    public static function getPriceRanges($includeLabels = false, $sortDesc = false)
    {
        $prices = array();

        if ($includeLabels)
        {
            foreach (self::$_priceRanges as $price)
            {
                if ($sortDesc)
                {
                    if ($price > 1 && $price < self::UPPER_PRICE_RANGE)
                        $prices[number_format($price)] = $price;
                    else if ($price == self::UPPER_PRICE_RANGE)
                        $prices['>100,000'] = $price;
                }
                else
                {
                    if ($price < self::UPPER_PRICE_RANGE)
                        $prices[number_format($price)] = $price;
                }
            }
        }
        else
            $prices = self::$_priceRanges;

        if ($sortDesc)
            $prices = array_reverse($prices, $includeLabels);

        return $prices;
    }


    public static function roundPrice($priceToRound)
    {
        $roundedMileage = $priceToRound;

        if (empty($priceToRound))
            return self::LOWER_PRICE_RANGE;

        if (!LTV_Validate_Integer::isValid($priceToRound))
            return $roundedMileage;

        foreach (self::$_priceRanges as $i => $price)
        {
            $minValue = $price;
            $maxValue = $price < self::UPPER_PRICE_RANGE
                ? self::$_priceRanges[$i+1]
                : null;

            if ($maxValue === null)
                return $price;

            if ($maxValue == self::UPPER_PRICE_RANGE)
                return $maxValue;

            if ($priceToRound >= $minValue && $priceToRound <= $maxValue)
            {
                $midPoint = $price + (($maxValue - $minValue) / 2);
                if ($priceToRound < $midPoint)
                    return $minValue;
                else
                    return $maxValue;
            }
        }
    }
}