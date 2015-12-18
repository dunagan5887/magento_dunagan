<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 12/18/15
 * Interface Dunagan_Io_Model_Import_Csv_Batched_Interface
 */
interface Dunagan_Io_Model_Import_Csv_Batched_Interface extends Dunagan_Io_Model_Import_Csv_Interface
{
    /**
     * Returns the number of rows which should be included in each batch
     *
     * @return int
     */
    public function getBatchSize();

    /**
     * Imports a batch of rows
     *
     * @param $rows_of_data_objects_array - Row Numbers of the file mapped to data objects containing the data in the file
     */
    public function importBatch(array $rows_of_data_objects_array);
}
