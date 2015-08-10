<?php

namespace LemonFree\Api\Params;

class Year
{
    public static function getUrlParam($yearA, $yearB, $separator = '-')
    {
        $urlParam = '';

        $isYearAValid = LemonFree\Api\Validate\Year::isValid($yearA);
        $isYearBValid = LemonFree\Api\Validate\Year::isValid($yearB);

        if ($isYearAValid && $isYearBValid)
        {
            if ($yearA === $yearB)
                $urlParam = $yearA;
            else
            {
                $yearParams[] = $yearA;
                $yearParams[] = $yearB;

                sort($yearParams);
                $urlParam = implode($separator, $yearParams);
            }
        }
        else if ($isYearAValid && $yearB === '')
            $urlParam = $yearA;
        else if ($isYearBValid && $yearA === '')
            $urlParam = $yearB;

        return $urlParam;
    }


    public static function getParams($yearParam, $separator = '-')
    {
        $yearParams = array('from' => '', 'to' => '');

        if (empty($yearParam))
            return $yearParams;

        if (preg_match('/([0-9]{4})' . $separator . '([0-9]{4})/', $yearParam))
        {
            $years = explode($separator, $yearParam);
            if ($years[0] > $years[1])
                sort($years);

            $yearParams['from'] = $years[0];
            $yearParams['to'] = $years[1];
        }
        else
        {
            $years = explode($separator, $yearParam);
            if (count($years) == 2)
            {
                $yearFrom = $years[0];
                $yearTo = $years[1];

                if (!empty($yearFrom) && !empty($yearTo))
                {
                    $yearParams['from'] = $yearFrom;
                    $yearParams['to'] = $yearTo;
                }
                else if (!empty($yearFrom) && empty($yearTo))
                {
                    $yearParams['from'] = $yearFrom;
                    $yearParams['to'] = $yearFrom;
                }
                else if (empty($yearFrom) && !empty($yearTo))
                {
                    $yearParams['from'] = $yearTo;
                    $yearParams['to'] = $yearTo;
                }
            }
            else
            {
                $yearParams['from'] = $years[0];
                $yearParams['to'] = $years[0];
            }
        }

        return $yearParams;
    }


    public static function getYears($includeLabels = false, $sortDesc = false)
    {
        $years = array();

        $startYear = 1900;
        $endYear = date('Y') + 1;

        for ($i = $startYear; $i <= $endYear; $i++)
        {
            $year = (string)$i;
            if ($includeLabels)
                $years[$year] = $year;
            else
                $years[] = $year;
        }

        if ($sortDesc)
            $years = array_reverse($years, $includeLabels);

        return $years;
    }
}