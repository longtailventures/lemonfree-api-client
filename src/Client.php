<?php

namespace LemonFree\Api;

class Client
{
    private $_apiToken, $_messages;

    const URL = 'http://api.lemonfree.com/v2';

    public function __construct($apiToken)
    {
        $this->_apiToken = $apiToken;
        $this->_messages = array();
    }



    public function getMessages()
    {
        return $this->_messages;
    }


    // year_from, year_to, price_min, price_max, state, city
    public function getMakes($yearFrom = null, $yearTo = null, $priceMin = null, $priceMax = null, $state = null,
                             $city = null, $sortBy = null)
    {
        $makes = array();

        $params = array('format=json');

        if ($yearFrom && $yearTo)
        {
            $params[] = 'year_from=' . urlencode($yearFrom);
            $params[] = 'year_to=' . urlencode($yearTo);
        }

        if ($priceMin && $priceMax)
        {
            $params[] = 'price_min=' . $priceMin;
            $params[] = 'price_max=' . $priceMax;
        }

        if ($state)
            $params[] = 'state=' . urlencode($state);

        if ($city)
            $params[] = 'city=' . urlencode($city);

        $params[] = 'key=' . $this->_apiToken;

        if ($sortBy)
            $params[] = 'sort_by=' . urlencode($sortBy);

        $requestUrl = self::URL . '/makes?' . implode('&', $params);
        $makes = $this->_getResponse($requestUrl);

        return $makes;
    }


    // make, year_from, year_to, price_min, price_max, state, city
    public function getModels($make, $yearFrom = null, $yearTo = null, $priceMin = null, $priceMax = null,
                              $state = null, $city = null, $sortBy = null)
    {
        $models = array();

        $params = array(
            'format=json',
            'make=' . urlencode($make)
        );

        if ($yearFrom && $yearTo)
        {
            $params[] = 'year_from=' . urlencode($yearFrom);
            $params[] = 'year_to=' . urlencode($yearTo);
        }

        if ($priceMin && $priceMax)
        {
            $params[] = 'price_min=' . $priceMin;
            $params[] = 'price_max=' . $priceMax;
        }

        if ($state)
            $params[] = 'state=' . urlencode($state);

        if ($city)
            $params[] = 'city=' . urlencode($city);

        if ($sortBy)
            $params[] = 'sort_by=' . urlencode($sortBy);

        $params[] = 'key=' . $this->_apiToken;

        $requestUrl = self::URL . '/models?' . implode('&', $params);
        $models = $this->_getResponse($requestUrl);

        return $models;
    }


    // make, model, year_from, year_to, price_min, price_max, state, city
    public function getTrims($make, $model, $yearFrom = null, $yearTo = null, $priceMin = null, $priceMax = null,
                             $state = null, $city = null, $sortBy = null)
    {
        $trims = array();

        $params = array(
            'format=json',
            'make=' . urlencode($make),
            'model=' . urlencode($model)
        );

        if ($yearFrom && $yearTo)
        {
            $params[] = 'year_from=' . urlencode($yearFrom);
            $params[] = 'year_to=' . urlencode($yearTo);
        }

        if ($priceMin && $priceMax)
        {
            $params[] = 'price_min=' . $priceMin;
            $params[] = 'price_max=' . $priceMax;
        }

        if ($state)
            $params[] = 'state=' . urlencode($state);

        if ($city)
            $params[] = 'city=' . urlencode($city);

        if ($sortBy)
            $params[] = 'sort_by=' . urlencode($sortBy);

        $params[] = 'key=' . $this->_apiToken;

        $requestUrl = self::URL . '/trims?' . implode('&', $params);
        $trims = $this->_getResponse($requestUrl);

        return $trims;
    }


    // make, model, trim, price_min, price_max, state, city
    public function getYears($make = null, $model = null, $trim = null, $priceMin = null, $priceMax = null,
                             $state = null, $city = null, $sortBy = null)
    {
        $years = array();

        $params = array('format=json');

        if ($make)
            $params[] = 'make=' . urlencode($make);

        if ($model)
            $params[] = 'model=' . urlencode($model);

        if ($trim)
            $params[] = 'trim=' . urlencode($trim);

        if ($priceMin && $priceMax)
        {
            $params[] = 'price_min=' . $priceMin;
            $params[] = 'price_max=' . $priceMax;
        }

        if ($state)
            $params[] = 'state=' . urlencode($state);

        if ($city)
            $params[] = 'city=' . urlencode($city);

        if ($sortBy)
            $params[] = 'sort_by=' . urlencode($sortBy);

        $params[] = 'key=' . $this->_apiToken;

        $requestUrl = self::URL . '/years?' . implode('&', $params);
        $years = $this->_getResponse($requestUrl);

        return $years;
    }


    // make, model, trim, year_from, year_to, state, city
    public function getPrices($make = null, $model = null, $trim = null, $yearFrom = null, $yearTo = null,
                              $state = null, $city = null, $sortBy = null)
    {
        $prices = array();

        $params = array('format=json');

        if ($make)
            $params[] = 'make=' . urlencode($make);

        if ($model)
            $params[] = 'model=' . urlencode($model);

        if ($trim)
            $params[] = 'trim=' . urlencode($trim);

        if ($yearFrom && $yearTo)
        {
            $params[] = 'year_from=' . urlencode($yearFrom);
            $params[] = 'year_to=' . urlencode($yearTo);
        }

        if ($state)
            $params[] = 'state=' . urlencode($state);

        if ($city)
            $params[] = 'city=' . urlencode($city);

        if ($sortBy)
            $params[] = 'sort_by=' . urlencode($sortBy);

        $params[] = 'key=' . $this->_apiToken;

        $requestUrl = self::URL . '/prices?' . implode('&', $params);
        $prices = $this->_getResponse($requestUrl);

        return $prices;
    }


    // make, model, trim, year_from, year_to, price_min, price_max, state
    public function getLocations($make = null, $model = null, $trim = null, $yearFrom = null, $yearTo = null,
                                 $priceMin = null, $priceMax = null, $state = null, $sortBy = null)
    {
        $locations = array();

        $params = array('format=json');

        if ($make)
            $params[] = 'make=' . urlencode($make);

        if ($model)
            $params[] = 'model=' . urlencode($model);

        if ($trim)
            $params[] = 'trim=' . urlencode($trim);

        if ($yearFrom && $yearTo)
        {
            $params[] = 'year_from=' . urlencode($yearFrom);
            $params[] = 'year_to=' . urlencode($yearTo);
        }

        if ($priceMin && $priceMax)
        {
            $params[] = 'price_min=' . $priceMin;
            $params[] = 'price_max=' . $priceMax;
        }

        if ($state)
            $params[] = 'state=' . urlencode($state);

        if ($sortBy)
            $params[] = 'sort_by=' . urlencode($sortBy);

        $params[] = 'key=' . $this->_apiToken;

        $requestUrl = self::URL . '/locations?' . implode('&', $params);
        $locations = $this->_getResponse($requestUrl);

        return $locations;
    }


    public function getListings($searchParams = array(), $page = 1, $n = 10, $sortBy = null)
    {
        $listingResult = array(
            'listings' => array(),
            'number_of_listings' => 0
        );

        if ($n < 10 || $n > 40)
            return $listingResult;

        $params = array('format=json');

        $validParams = array(
            'make', 'model', 'trim', 'bodystyle', 'condition', 'year_from', 'year_to', 'price_min', 'price_max',
            'mileage_min', 'mileage_max', 'zip', 'distance', 'city', 'state', 'country', 'certified_only',
            'sort_by', 'sort_dir'
        );

        foreach ($searchParams as $field => $value)
        {
            if (in_array($field, $validParams) && trim($value) !== '')
                $params[] = $field . '=' . urlencode($value);
        }

        $params[] = 'page=' . urlencode($page);
        $params[] = 'per_page=' . urlencode($n);
        $params[] = 'key=' . $this->_apiToken;

        $requestUrl = self::URL . '/listings?' . implode('&', $params);
        $result = $this->_getResponse($requestUrl);

        if (count($result) > 0)
        {
            $listingResult = array(
                'listings' => $result['listings'],
                'number_of_listings' => isset($result['listing_count']) ? $result['listing_count'] : 0
            );
        }

        return $listingResult;
    }


    public function getListing($listingId)
    {
        $listing = null;

        $listingIdEncoded = urlencode($listingId);
        $requestUrl = self::URL . '/details?format=json&listing_id=' . $listingIdEncoded . '&key=' . $this->_apiToken;
        $result = $this->_getResponse($requestUrl);
        if ($result)
            $listing = $result[0];

        return $listing;
    }


    public function getCarfaxReport($vin, $carfaxPartnerId)
    {
        $report = null;

        $params = array('format=json');
        $params[] = 'vin=' . urlencode($vin);
        $params[] = 'partner_id=' . urlencode($carfaxPartnerId);
        $params[] = 'key=' . $this->_apiToken;

        $requestUrl = self::URL . '/carfax?' . implode('&', $params);
        $report = $this->_getResponse($requestUrl);

        return $report;
    }


    public function getFacts($fact, $year, $make, $model)
    {
    }


    /**
     * Submits a lead ($leadDetails) for the incoming $listingId
     *
     * @param string $listingId
     * The listing id
     *
     * @param array $leadDetails
     * A hash that should have the following keys set:
     * - email
     * - fname
     * - lname
     * - zip
     * - phone
     * - questions (optional)
     *
     * @return array $response
     */
    public function submitLead($listingId, array $leadDetails)
    {
        $lead = $leadDetails;
        $lead['listing_id'] = $listingId;
        $lead['key'] = $this->_apiToken;

        $leadApiParams = array("format=json");
        foreach ($lead as $field => $value)
            $leadApiParams[] = "$field=$value";

        $requestUrl = self::URL . "/lead/";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $requestUrl);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, implode('&', $leadApiParams));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        $response = curl_exec($curl);
        $response = json_decode($response, true);

        return $response['response'];
    }


    private function _getResponse($requestUrl)
    {
        $this->_messages = array();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);
        if (isset($response['response']) && isset($response['response']['result']))
            $result = $response['response']['result'];
        else
            $result = array();

        if (isset($response['response']['response_messages']))
        {
            $this->_messages = $response['response']['response_messages'];
        }

        return $result;
    }
}