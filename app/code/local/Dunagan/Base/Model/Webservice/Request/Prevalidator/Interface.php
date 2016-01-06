<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 1/5/16
 *
 * Interface Dunagan_Base_Model_Webservice_Request_Prevalidator_Interface
 */
interface Dunagan_Base_Model_Webservice_Request_Prevalidator_Interface
{
    /**
     * Validates that all of the necessary data points in the data array have been populated
     *
     * @param array $data_to_post
     * @return array
     */
    public function preValidatePostData(array $data_to_post);

    /**
     * Validates that the required stdClass object field is populated
     *
     * @param array $data_to_post
     * @param string $stdClass_field
     * @return array
     */
    public function validateRequiredStdClassObjectField(array $data_to_post, $stdClass_field);

    /**
     * Validates that the required array field is populated
     *
     * @param array $data_to_post
     * @param string $array_field
     * @return array
     */
    public function validateRequiredArrayField(array $data_to_post, $array_field);

    /**
     * Required Fields mutator
     *
     * @param array $required_fields
     * @return $this
     */
    public function setRequiredFields(array $required_fields);

    /**
     * Fields allowed to be empty but required to be declared mutator
     *
     * @param array $required_fields_allowed_to_be_empty
     * @return $this
     */
    public function setFieldsAllowedToBeEmpty(array $required_fields_allowed_to_be_empty);

    /**
     * Fields allowed to be empty but required to be declared mutator
     *
     * @param array $required_array_fields
     * @return $this
     */
    public function setRequiredArrayFields(array $required_array_fields);

    /**
     * Fields required to be stdClass Objects mutator
     *
     * @param array $required_std_class_object_fields
     * @return $this
     */
    public function setRequiredStdClassObjectFields(array $required_std_class_object_fields);
}
