<?php

namespace LemonFree\Api\Site\Meta;

use LemonFree\Api\Site\Url\Param;
use LemonFree\Api\Params\Mileage;
use LemonFree\Api\Params\Price;
use LemonFree\Api\Params\Distance;

use LongTailVentures\Validator\ZipCode as ZipCodeValidator;

class Util
{
    public static function getParamsFromQueryString($queryString)
    {
        $queryStringParams = !empty($queryString) ? explode('/', $queryString) : array();

        // api search params
        $searchParams = array(
            'make' => null,
            'model' => null,
            'trim' => null,
            'bodystyle' => null,
            'condition' => null,
            'year_from' => null,
            'year_to' => null,
            'price_min' => null,
            'price_max' => null,
            'mileage_min' => null,
            'mileage_max' => null,
            'zip' => null,
            'distance' => null,
        	'state' => null,
        	'city' => null,
            'sort_by' => null,
        	'sort_dir' => null,
            'page' => '1'
        );

        // user request params (from serp url)
        $requestParams = array(
            'make' => null,
            'model' => null,
            'trim' => null,
            'body' => null,
            'condition' => null,
            'year' => null,
            'price' => null,
            'mileage' => null,
            'location' => null,
            'range' => null,
            'sort' => null,
            'page' => '1'
        );

        foreach ($queryStringParams as $param)
        {
            if (strpos($param, 'used-for-sale-') === 0)
            {
                $value = str_replace('used-for-sale-', '', $param);
                $paramParts = explode('.', $value);
                if (count($paramParts) == 2)
                {
                    $requestParams['make'] = $searchParams['make'] = Param::decode($paramParts[0]);
                    $requestParams['model'] = $searchParams['model'] = Param::decode($paramParts[1]);
                }
                else if (count($paramParts) == 1)
                    $requestParams['make'] = $searchParams['make'] = Param::decode($paramParts[0]);
            }
            else
            {
                $paramParts = explode('-', $param);
                $field = $paramParts[0];
                $value = str_replace($field . '-', '', $param);

                if ($field === 'cars' || (empty($field) && empty($value)))
                    continue;

                switch ($field)
                {
                    case 'body':
                        $requestParams['body'] = $value;
                        $searchParams['bodystyle'] = $value;
                        break;

                    case 'location':
                    	$requestParams['location'] = $value;

                    	$validator = new ZipCodeValidator();
                        if ($validator->isValid($value))
                        {
	                        $searchParams['zip'] = $value;
	                        $searchParams['distance'] = Distance::DEFAULT_VALUE;

                            setcookie('LFAPI_LOCATION', $value, time()+60*60*24*7, '/');
                        }
                        else if (stripos($value, '--') !== false)
                        {
                        	list($city, $state) = explode('--', $value);
                        	$searchParams['city'] = Param::decode($city);
                        	$searchParams['state'] = $state;
                        }
                        break;

                    case 'range':
                        $requestParams['range'] = $value;
                        $searchParams['distance'] = $value;
                        break;

                    case 'sort':
                    	$requestParams['sort'] = $value;
                    	if ($value === 'lowprice')
                    	{
                    		$searchParams['sort_by'] = 'price';
                    		$searchParams['sort_dir'] = 'asc';
                    	}
                    	else if ($value === 'highprice')
                    	{
                    		$searchParams['sort_by'] = 'price';
                    		$searchParams['sort_dir'] = 'desc';
                    	}
                    	else if ($value === 'lowmileage')
                    	{
                    		$searchParams['sort_by'] = 'mileage';
                    		$searchParams['sort_dir'] = 'asc';
                    	}
                    	else if ($value === 'highmileage')
                    	{
                    		$searchParams['sort_by'] = 'mileage';
                    		$searchParams['sort_dir'] = 'desc';
                    	}
                        break;

                    case 'year':
                        $requestParams['year'] = $value;
                        $searchParams['year_from'] = $value;
                        $searchParams['year_to'] = $value;
                        break;

                    case 'price':
                        $requestParams['price'] = $value;
                        $priceParams = Price::getParams($value, $separator = '.');
                        $searchParams['price_min'] = $priceParams['from'];
                        $searchParams['price_max'] = $priceParams['to'];
                        break;

                    case 'mileage':
                        $requestParams['mileage'] = $value;
                        $mileageParams = Mileage::getParams($value, $separator = '.');
                        $searchParams['mileage_min'] = $mileageParams['from'];
                        $searchParams['mileage_max'] = $mileageParams['to'];
                        break;

                    default:
                        $requestParams[$field] = $searchParams[$field] = $value;
                        break;
                }
            }
        }

        // if location is missing read the cookie
        if ($searchParams['zip'] === null && $searchParams['state'] === null)
        {
            $validator = new ZipCodeValidator();
            $storedLocation = isset($_COOKIE['LFAPI_LOCATION']) && $validator->isValid($_COOKIE['LFAPI_LOCATION'])
                ? $_COOKIE['LFAPI_LOCATION']
                : null;

            $requestParams['location'] = $storedLocation;
            $searchParams['zip'] = $storedLocation;

            if ($storedLocation)
                $searchParams['distance'] = Distance::DEFAULT_VALUE;
        }

        return array(
            'request' => $requestParams,
            'search' => $searchParams
        );
    }
}