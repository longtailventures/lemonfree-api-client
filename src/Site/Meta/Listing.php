<?php

namespace LemonFree\Api\Site\Meta;

use LemonFree\Api\Site\Url\Param;

class Listing
{
	public function __construct(array $listing)
	{
		$this->_listing = $listing;
	}


	public function getListingUrl()
	{
		$url = "/car/" . Param::encode($this->_listing['make']) . "-";
		$url .= Param::encode($this->_listing['model']) . "-";
		$url .= $this->_listing['year'] . "/" . $this->_listing['id'];

		return $url;
	}
}