<?php
 
namespace Dev\ImportProduct\Model\ResourceModel;
 
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
 
class File extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('custom_import_file', 'entity_id');
    }
}