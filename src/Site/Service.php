<?php

namespace LemonFree\Api\Site;

class Service
{
	private $_api;

	public function __construct(LemonFree\Api\Client $api)
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
        	$listingResult = $this->_api->getListings($searchParams, $searchParams['page'], $nPerPage = 10);

	        foreach ($listingResult['listings'] as $i => $listing)
	        {
	        	$listingMeta = new CarForums_Meta_Listing($listing);
	        	$listing['url'] = $listingMeta->getListingUrl();
	
	        	$listingResult['listings'][$i] = $listing;
	        }
	
	        return $listingResult;
	}


	public function getListing($listingId)
	{
		$listing = $this->_api->getListing($listingId);

		// set the main image
		$mainImage = array_shift($listing['images']);
		$listing['main_image'] = str_replace('_img', '', $mainImage);

		return $listing;
	}


	public function submitLead($listingId, $lead)
	{

	}
}
