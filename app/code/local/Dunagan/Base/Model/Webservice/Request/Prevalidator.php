<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 1/5/16
 * Class Dunagan_Base_Model_Webservice_Request_Prevalidator
 */
class Dunagan_Base_Model_Webservice_Request_Prevalidator implements Dunagan_Base_Model_Webservice_Request_Prevalidator_Interface
{
    const ERROR_OBJECT_FIELD_NOT_DECLARED = 'The %s field is required to be set on the %s object';
    const ERROR_REQUIRED_FIELD_EMPTY = "The required '%s' field was not populated";
    const ERROR_REQUIRED_STDCLASS_FIELD_EMPTY = "The '%s' field is required to be a stdClass object, but is not a stdClass object: %s";
    const ERROR_REQUIRED_ARRAY_FIELD_EMPTY = "The required array field '%s' was not populated";
    const ERROR_REQUIRED_FIELDS_EMPTY = 'The following required fields were not populated: %s';
    const ERROR_UNDECLARED_DATA_FIELDS         = 'The following fields which need to be declared were not present in the post data array: %s';

    protected $_translationHelper = null;

    protected $_required_fields = null;
    protected $_required_fields_allowed_to_be_empty = null;
    protected $_required_array_fields = null;
    protected $_required_std_class_object_fields = null;

    /**
     * Validates that all of the necessary data points in the data array have been populated
     *
     * @param array $data_to_post
     * @return mixed
     */
    public function preValidatePostData(array $data_to_post)
    {
        $errors_array = array();
        // Validate the fields which are required to be declared
        $required_fields = $this->getRequiredFields();
        $fields_allowed_to_be_empty = $this->getFieldsAllowedToBeEmpty();
        $fields_required_to_be_declared = array_merge($required_fields, $fields_allowed_to_be_empty);

        $fields_not_declared = array_diff_key($fields_required_to_be_declared, $data_to_post);
        if (!empty($fields_not_declared)) {
            $error_message
                = $this->_getTranslationHelper()->__(self::ERROR_UNDECLARED_DATA_FIELDS,
                                                     implode(', ', array_keys($fields_not_declared)));
            $errors_array[] = $error_message;
        }
        // Validate the fields which are required to be declared and populated
        $declared_fields = array_diff_key($fields_required_to_be_declared, $fields_not_declared);
        $required_declared_fields = array_intersect_key($required_fields, $declared_fields);
        $required_declared_fields_data = array_intersect_key($data_to_post, $required_declared_fields);
        $non_empty_fields = array_filter($required_declared_fields_data);
        $empty_fields = array_diff_key($required_declared_fields_data, $non_empty_fields);
        if (!empty($empty_fields)) {
            $error_message = $this->_getTranslationHelper()->__(self::ERROR_REQUIRED_FIELDS_EMPTY,
                                                                implode(', ', array_keys($empty_fields)));
            $errors_array[] = $error_message;
        }
        // Validate the fields which are required to be populated arrays
        foreach($this->getRequiredArrayFields() as $array_field)
        {
            $array_field_error = $this->validateRequiredArrayField($data_to_post, $array_field);
            if (!empty($array_field_error))
            {
                $errors_array[] = $array_field_error;
            }
        }
        // Validate the fields which are required to be stdClass objects
        foreach($this->getRequiredStdClassObjectFields() as $stdClass_field)
        {
            $stdclass_object_error = $this->validateRequiredStdClassObjectField($data_to_post, $stdClass_field);
            if (!empty($stdclass_object_error))
            {
                $errors_array[] = $stdclass_object_error;
            }
        }
        return $errors_array;
    }

    /**
     * Validates that the required stdClass object field is populated
     *
     * @param array $data_to_post
     * @param string $stdClass_field
     * @return array
     */
    public function validateRequiredStdClassObjectField(array $data_to_post, $stdClass_field)
    {
        $requiredStdClassObject = isset($data_to_post[$stdClass_field]) ? $data_to_post[$stdClass_field] : null;
        if ((!is_object($requiredStdClassObject)) || (!($requiredStdClassObject instanceof stdClass)))
        {
            $error_message = $this->_getTranslationHelper()->__(self::ERROR_REQUIRED_STDCLASS_FIELD_EMPTY,
                                                                $stdClass_field, serialize($requiredStdClassObject));
            return $error_message;
        }
        return null;
    }

    /**
     * Validates that the required array field is populated
     *
     * @param array $data_to_post
     * @param string $array_field
     * @return array
     */
    public function validateRequiredArrayField(array $data_to_post, $array_field)
    {
        $required_array = isset($data_to_post[$array_field]) ? $data_to_post[$array_field] : null;
        if ((!is_array($required_array)) || empty($required_array))
        {
            $error_message = $this->_getTranslationHelper()->__(self::ERROR_REQUIRED_ARRAY_FIELD_EMPTY, $array_field);
            return $error_message;
        }
        return null;
    }

    /**
     * Validates that the object contains all of the required data
     *
     * @param stdClass $objectToPrevalidate
     * @param string   $object_field_name
     * @param array    $required_fields
     * @param array    $required_fields_allowed_to_be_empty
     * @return array
     */
    public function validateStdClassObjectFields(stdClass $objectToPrevalidate, $object_field_name,
                                                 array $required_fields, array $required_fields_allowed_to_be_empty)
    {
        $errors_array = array();
        $fields_required_to_be_declared = array_merge($required_fields, $required_fields_allowed_to_be_empty);
        foreach($fields_required_to_be_declared as $field_required_to_be_declared)
        {
            if (!property_exists($objectToPrevalidate, $field_required_to_be_declared))
            {
                $error_message = $this->_getTranslationHelper()->__(self::ERROR_OBJECT_FIELD_NOT_DECLARED,
                                                                    $field_required_to_be_declared, $object_field_name);
                $errors_array[] = $error_message;
            }
        }

        foreach($required_fields as $required_field)
        {
            if (property_exists($objectToPrevalidate, $required_field))
            {
                $value = $objectToPrevalidate->$required_field;
                if ((is_null($value)) || ('' == $value) || ($value === FALSE))
                {
                    $error_message = $this->_getTranslationHelper()->__(self::ERROR_REQUIRED_FIELD_EMPTY, $value);
                    $errors_array[] = $error_message;
                }
            }
        }

        return $errors_array;
    }

    /**
     * Required Fields Accessor
     *
     * @return array
     */
    public function getRequiredFields()
    {
        return $this->_required_fields;
    }

    /**
     * Required Fields mutator
     *
     * @param array $required_fields
     * @return $this
     */
    public function setRequiredFields(array $required_fields)
    {
        $this->_required_fields = $required_fields;
        return $this;
    }

    /**
     * Fields allowed to be empty but required to be declared Accessor
     *
     * @return array
     */
    public function getFieldsAllowedToBeEmpty()
    {
        return $this->_required_fields_allowed_to_be_empty;
    }

    /**
     * Fields allowed to be empty but required to be declared mutator
     *
     * @param array $required_fields_allowed_to_be_empty
     * @return $this
     */
    public function setFieldsAllowedToBeEmpty(array $required_fields_allowed_to_be_empty)
    {
        $this->_required_fields_allowed_to_be_empty = $required_fields_allowed_to_be_empty;
        return $this;
    }

    /**
     * Fields required to be a populated array accessor
     *
     * @return array
     */
    public function getRequiredArrayFields()
    {
        return $this->_required_array_fields;
    }

    /**
     * Fields allowed to be empty but required to be declared mutator
     *
     * @param array $required_array_fields
     * @return $this
     */
    public function setRequiredArrayFields(array $required_array_fields)
    {
        $this->_required_array_fields = $required_array_fields;
        return $this;
    }

    /**
     * Fields required to be stdClass Objects accessor
     *
     * @return array
     */
    public function getRequiredStdClassObjectFields()
    {
        return $this->_required_std_class_object_fields;
    }

    /**
     * Fields required to be stdClass Objects mutator
     *
     * @param array $required_std_class_object_fields
     * @return $this
     */
    public function setRequiredStdClassObjectFields(array $required_std_class_object_fields)
    {
        $this->_required_std_class_object_fields = $required_std_class_object_fields;
        return $this;
    }
    
    /**
     * Returns the translation helper
     *
     * @return Dunagan_Base_Helper_Data
     */
    protected function _getTranslationHelper()
    {
        if (is_null($this->_translationHelper))
        {
            $this->_translationHelper = Mage::helper('dunagan_base');
        }

        return $this->_translationHelper;
    }
}
