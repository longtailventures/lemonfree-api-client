<?php

namespace LemonFree\Api\Site;

class LeadForm extends \LongTailVentures\Form
{
    public function isValid($values)
    {
    	$validator = new \Zend\Validator\NotEmpty();
    	$validator->setMessage('Please enter your first name');
    	$this->addValidator('FirstName', $validator);

    	$validator = new \Zend\Validator\NotEmpty();
    	$validator->setMessage('Please enter your last name');
    	$this->addValidator('LastName', $validator);

    	$validator = new \Zend\Validator\EmailAddress();
    	$validator->setMessage('Please enter a valid email address');
    	$this->addValidator('Email', $validator);

    	$validator = new \LongTailVentures\Validator\ZipCode();
    	$validator->setMessage('Please enter a 5 digit zip code');
    	$this->addValidator('PostCode', $validator);

    	$validator = new \LongTailVentures\Validator\PhoneNumber();
    	$validator->setMessage('Please enter a 10 digit phone number');
    	$this->addValidator('Phone', $validator);

        return parent::isValid($values);
    }
}
