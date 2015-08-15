<?php

namespace LemonFree\Api\Site;

use LemonFree\Api\Client;
use LemonFree\Api\Site\Meta\Listing;

class Service
{
	private $_api;

	public function __construct(Client $api)
	{
		$this->_api = $api;
	}


	public function getMakes()
	{
		$makes = array();

		$apiMakes = $this->_api->getMakes();
		foreach ($apiMakes as $make)
			$makes[$make['make']] = $make['make'];

		return $makes;
	}


	public function getModels($make)
	{
		$models = array();

		$apiModels = $this->_api->getModels($make);
		foreach ($apiModels as $model)
			$models[$model['model']] = $model['model'];

		return $models;
	}


	public function getListings($searchParams)
	{
    	$listingResult = $this->_api->getListings($searchParams, $searchParams['page'], $searchParams['n_per_page']);

        foreach ($listingResult['listings'] as $i => $listing)
        {
        	$listingMeta = new Listing($listing);
        	$listing['url'] = $listingMeta->getListingUrl();

        	$listingResult['listings'][$i] = $listing;
        }

        return $listingResult;
	}


	public function getListing($listingId)
	{
		$listing = $this->_api->getListing($listingId);

		if ($listing)
		{
    		// set the main image
    		$mainImage = array_shift($listing['images']);
    		$listing['main_image'] = str_replace('_img', '', $mainImage);
		}

		return $listing;
	}


	public function submitLead($listingId, $lead)
	{

	}
}