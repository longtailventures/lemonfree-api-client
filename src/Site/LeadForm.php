<?php

namespace LemonFree\Api\Site;

class LeadForm extends LongTailVentures\Form
{
    public function isValid($values)
    {
    	$validator = new Zend\Validatator\NotEmpty();
    	$validator->setMessage('Please enter your first name');
    	$this->addValidator('FirstName', $validator);

    	$validator = new Zend\Validatator\NotEmpty();
    	$validator->setMessage('Please enter your last name');
    	$this->addValidator('LastName', $validator);

    	$validator = new Zend\Validatator\EmailAddress();
    	$validator->setMessage('Please enter a valid email address');
    	$this->addValidator('Email', $validator);

    	$validator = new Zend\Il8n\Validatator\PostCode();
    	$validator->setMessage('Please enter a 5 digit zip code');
    	$this->addValidator('PostCode', $validator);

    	$validator = new Zend\Validatator\NotEmpty();
    	$validator->setMessage('Please enter a 10 digit phone number');
    	$this->addValidator('Phone', $validator);

        return parent::isValid($values);
    }
}