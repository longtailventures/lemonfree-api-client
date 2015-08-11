<?php

namespace LemonFree\Api\Site\Meta;

class Serp
{
    private $_metaParams, $_requestParams;

    public function __construct(array $requestParams)
    {
        $this->_requestParams = $requestParams;
    }


    public function getH1Title()
    {
        if ($this->_requestParams['condition'])
            $metaParams['condition'] = ucfirst(strtolower($this->_requestParams['condition']));

        if ($this->_requestParams['year'])
        {
            $yearParams = LemonFree\Api\Params\Year::getParams($this->_requestParams['year']);
            $metaParams['year'] = $yearParams['from'] === $yearParams['to']
                ? $yearParams['from']
                : "{$yearParams['from']}-{$yearParams['to']}";
        }

        $metaParams['make_model'] = 'Cars For Sale';
        if ($this->_requestParams['make'])
        {
            $metaParams['make_model'] = $this->_requestParams['model']
                ? "{$this->_requestParams['make']} {$this->_requestParams['model']}"
                : $this->_requestParams['make'];

            if (isset($this->_requestParams['trim']))
                $metaParams['make_model'] .= ' ' . $this->_requestParams['trim'];

            $metaParams['make_model'] .= ' For Sale';
        }
        else if ($this->_requestParams['body'])
            $metaParams['make_model'] = $this->_requestParams['body'] . " For Sale";

        if ($this->_requestParams['mileage'])
        {
            $mileageParams = LemonFree\Api\Params\Mileage::getParams($this->_requestParams['mileage'], '.');

            if ($mileageParams['from'] == 1)
                $metaParams['mileage'] = "under " . number_format($mileageParams['to']) . " Miles";
            else
                $metaParams['mileage'] = "over " . number_format($mileageParams['from']) . " Miles";
        }

        if ($this->_requestParams['location'])
        {
        	if (LTV_Validate_ZipCode::isValid($this->_requestParams['location']))
        		$metaParams['location'] = 'in ' . $this->_requestParams['location'];
        	else
        	{
        		list($city, $state) = explode('--', $this->_requestParams['location']);
        		$metaParams['location'] = 'in ' . LemonFree\Api\Site\Url\Param::decode($city) . ', ' . $state;
        	}
        }

        if ($this->_requestParams['price'])
        {
            $params = LemonFree\Api\Params\Price::getParams($this->_requestParams['price'], '.');

            if ($params['from'] == LemonFree\Api\Params\Price::LOWER_PRICE_RANGE)
                $metaParams['price'] = "under $" . number_format($params['from']);
            else
                $metaParams['price'] = "over $" . number_format($params['to']);
        }

        return implode(' ', $metaParams);
    }


	public function getSerpUrl($includeSortParam = false, $includePageParam = false)
	{
		$urlParams = array();

		// valid url params order:
		// make, model | trim | location (zip -or- state, city) | body | price | year | colour | features | sort |
		// page | drive | fuel | range | state | transmission | condition

		if (isset($this->_requestParams['model']) && !empty($this->_requestParams['model']))
		{
			$makeParam = LemonFree\Api\Site\Url\Param::encode($this->_requestParams['make']);
			$modelParam = LemonFree\Api\Site\Url\Param::encode($this->_requestParams['model']);
			$urlParams['model'] = 'used-for-sale-' . $makeParam . '.' . $modelParam;
		}
		else if (isset($this->_requestParams['make']) && !empty($this->_requestParams['make']))
			$urlParams['make'] = 'used-for-sale-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['make']);

		if (isset($this->_requestParams['trim']) && !empty($this->_requestParams['trim']))
			$urlParams['trim'] = 'trim-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['trim']);

		if (isset($this->_requestParams['location']))
		{
			if (LTV_Validate_ZipCode::isValid($this->_requestParams['location']))
				$urlParams['location'] = 'location-' . $this->_requestParams['location'];
			else
				$urlParams['location'] = 'location-' . $this->_requestParams['location'];
		}

		if (isset($this->_requestParams['body']) && !isset($this->_requestParams['make']))
			$urlParams['body'] = 'body-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['body']);

		if (isset($this->_requestParams['price']))
			$urlParams['price'] = 'price-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['price']);

		if (isset($this->_requestParams['mileage']))
			$urlParams['mileage'] = 'mileage-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['mileage']);

		if (isset($this->_requestParams['year']))
			$urlParams['year'] = 'year-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['year']);

		if (isset($this->_requestParams['sort']))
			$urlParams['sort'] = 'sort-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['sort']);

		if ($includePageParam && isset($this->_requestParams['page']))
			$urlParams['page'] = 'page-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['page']);

		if (isset($this->_requestParams['range']))
		{
			if (LTV_Validate_ZipCode::isValid($this->_requestParams['location']))
			{
				$distance = $this->_requestParams['range'];
				if ($distance !== LemonFree_Api_Params_Distance::DEFAULT_VALUE)
					$urlParams['range'] = 'range-' . LemonFree\Api\Site\Url\Param::encode($distance);
			}
		}

		if (isset($this->_requestParams['transmission']))
			$urlParams['transmission'] = 'transmission-' . LemonFree\Api\Site\Url\Param::encode($this->_requestParams['transmission']);

		if (isset($this->_requestParams['condition']) && !empty($this->_requestParams['condition']))
			$urlParams['condition'] = 'condition-' . $this->_requestParams['condition'];

		return count($urlParams) > 0
			? '/cars/' . implode('/', $urlParams)
			: '/cars';
	}
}