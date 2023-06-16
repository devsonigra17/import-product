<?php
 
namespace Dev\ImportProduct\Model\ResourceModel\File;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Dev\ImportProduct\Model\File','Dev\ImportProduct\Model\ResourceModel\File');
    }
}