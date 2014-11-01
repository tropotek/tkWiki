<?php
/*
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2007 Michael Mifsud
 */

/**
 *  A type object converts form element values to required types.
 *
 * @package Form
 */
class Form_Type_Integer extends Form_Type
{

    /**
     * Create an instance of this object
     *
     * @return Form_Type_Integer
     */
    static function create()
    {
        return new self();
    }
    
    /**
     * Load the field value object from a data sorce array.
     * This is usually, but not limited to, the request array
     *
     * @param array $array
     */
    function loadFromArray($array)
    {
        $name = $this->getFieldName();
        if (!array_key_exists($name, $array)) {
            return;
        }
        $strValue = 0;
        if (isset($array[$name])) {
            $strValue = trim($array[$name]);
        }
        $this->field->setSubFieldValue($name, $strValue);
        $this->field->setRawValue((int)$strValue);
    }
    
    /**
     * Set the raw sub-field values from the field value object
     *
     * @param string $obj
     */
    function setSubFieldValues($obj)
    {
        $this->field->setSubFieldValue($this->getFieldName(), $obj);
    }
    
    
    
}