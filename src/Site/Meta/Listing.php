<?php

namespace LemonFree\Api\Site\Meta;

class Listing
{
	public function __construct(array $listing)
	{
		$this->_listing = $listing;
	}


	public function getListingUrl()
	{
		$url = "/car/" . LemonFree\Api\Url\Param::encode($this->_listing['make']) . "-";
		$url .= LemonFree\Api\Url\Param::encode($this->_listing['model']) . "-";
		$url .= $this->_listing['year'] . "/" . $this->_listing['id'];

		return $url;
	}
}