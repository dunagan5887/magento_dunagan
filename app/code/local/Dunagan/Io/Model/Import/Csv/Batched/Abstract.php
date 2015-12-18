<?php
/**
 * Author: Sean Dunagan (https://github.com/dunagan5887)
 * Created: 12/18/15
 * Class Dunagan_Io_Model_Import_Csv_Batched_Abstract
 */
abstract class Dunagan_Io_Model_Import_Csv_Batched_Abstract
    extends Dunagan_Io_Model_Import_Csv_Abstract
    implements Dunagan_Io_Model_Import_Csv_Batched_Interface
{
    const ERROR_DUPLICATE_ROW_NUMBER = 'Row number %s was already set in a batch, and was attempted to be set again';

    abstract public function getBatchSize();

    abstract public function importBatch(array $rows_of_data_objects_array);

    protected $_current_batch_size = 0;
    protected $_current_batch_data_objects = array();

    public function importDataRow($rowData, $row_num)
    {
        if (!isset($this->_current_batch_data_objects[$row_num]))
        {
            $this->_current_batch_data_objects[$row_num] = $rowData;
        }
        else
        {
            // We don't expect this to occur, but handle it if it does
            $error_message = sprintf(self::ERROR_DUPLICATE_ROW_NUMBER, $row_num);
            $this->logError($error_message);
            // Add the data row as a generic index
            $this->_current_batch_data_objects[] = $rowData;
        }
        $this->_current_batch_size++;
        if ($this->getBatchSize() == $this->_current_batch_size)
        {
            $this->_processBatch();
        }
    }

    public function readFile($ioAdapter, $file, $file_path)
    {
        parent::readFile($ioAdapter, $file, $file_path);

        // If there were any rows in the final batch which weren't processed, process them now
        if ($this->_current_batch_size > 0)
        {
            $this->_processBatch();
        }
    }

    protected function _processBatch()
    {
        $this->importBatch($this->_current_batch_data_objects);
        $this->_resetBatch();
    }

    protected function _resetBatch()
    {
        $this->_current_batch_size = 0;
        $this->_current_batch_data_objects = array();
    }
}