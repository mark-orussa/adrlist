<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     Amazon_FPS
 *  @copyright   Copyright 2008-2009 Amazon.com, Inc. or its affiliates. All Rights Reserved.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2010-08-28
 */
/******************************************************************************* 
 *    __  _    _  ___ 
 *   (  )( \/\/ )/ __)
 *   /__\ \    / \__ \
 *  (_)(_) \/\/  (___/
 * 
 *  Amazon FPS PHP5 Library
 *  Generated: Wed Jun 15 05:50:14 GMT+00:00 2011
 * 
 */

/**
 *  @see Amazon_FPS_Model
 */
//require_once ('Amazon/FPS/Model.php');  

    

/**
 * Amazon_FPS_Model_DescriptorPolicy
 * 
 * Properties:
 * <ul>
 * 
 * <li>SoftDescriptorType: SoftDescriptorType</li>
 * <li>CSOwner: CSOwner</li>
 *
 * </ul>
 */ 
class Amazon_FPS_Model_DescriptorPolicy extends Amazon_FPS_Model
{


    /**
     * Construct new Amazon_FPS_Model_DescriptorPolicy
     * 
     * @param mixed $data DOMElement or Associative Array to construct from. 
     * 
     * Valid properties:
     * <ul>
     * 
     * <li>SoftDescriptorType: SoftDescriptorType</li>
     * <li>CSOwner: CSOwner</li>
     *
     * </ul>
     */
    public function __construct($data = null)
    {
        $this->_fields = array (
        'SoftDescriptorType' => array('FieldValue' => null, 'FieldType' => 'SoftDescriptorType'),
        'CSOwner' => array('FieldValue' => null, 'FieldType' => 'CSOwner'),
        );
        parent::__construct($data);
    }

        /**
     * Gets the value of the SoftDescriptorType property.
     * 
     * @return SoftDescriptorType SoftDescriptorType
     */
    public function getSoftDescriptorType() 
    {
        return $this->_fields['SoftDescriptorType']['FieldValue'];
    }

    /**
     * Sets the value of the SoftDescriptorType property.
     * 
     * @param SoftDescriptorType SoftDescriptorType
     * @return this instance
     */
    public function setSoftDescriptorType($value) 
    {
        $this->_fields['SoftDescriptorType']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the SoftDescriptorType and returns this instance
     * 
     * @param SoftDescriptorType $value SoftDescriptorType
     * @return Amazon_FPS_Model_DescriptorPolicy instance
     */
    public function withSoftDescriptorType($value)
    {
        $this->setSoftDescriptorType($value);
        return $this;
    }


    /**
     * Checks if SoftDescriptorType is set
     * 
     * @return bool true if SoftDescriptorType  is set
     */
    public function isSetSoftDescriptorType()
    {
        return !is_null($this->_fields['SoftDescriptorType']['FieldValue']);
    }

    /**
     * Gets the value of the CSOwner property.
     * 
     * @return CSOwner CSOwner
     */
    public function getCSOwner() 
    {
        return $this->_fields['CSOwner']['FieldValue'];
    }

    /**
     * Sets the value of the CSOwner property.
     * 
     * @param CSOwner CSOwner
     * @return this instance
     */
    public function setCSOwner($value) 
    {
        $this->_fields['CSOwner']['FieldValue'] = $value;
        return $this;
    }

    /**
     * Sets the value of the CSOwner and returns this instance
     * 
     * @param CSOwner $value CSOwner
     * @return Amazon_FPS_Model_DescriptorPolicy instance
     */
    public function withCSOwner($value)
    {
        $this->setCSOwner($value);
        return $this;
    }


    /**
     * Checks if CSOwner is set
     * 
     * @return bool true if CSOwner  is set
     */
    public function isSetCSOwner()
    {
        return !is_null($this->_fields['CSOwner']['FieldValue']);
    }




}