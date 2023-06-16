<?php

namespace Dev\ImportProduct\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Dev\ImportProduct\Api\Data\ImportFileInterface;
 
class File extends AbstractModel implements ImportFileInterface , IdentityInterface
{
    const CACHE_TAG = 'id';

    protected function _construct()
    {
        $this->_init('Dev\ImportProduct\Model\ResourceModel\File');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }
        
    public function getEntityId()
    {
        return parent::getData(self::ENTITY_ID);
    }
    
    public function getFileName()
    {
        return $this->getData(self::FILE_NAME);
    }
    
    public function getTriggeredDate()
    {
        return $this->getData(self::TRIGGERED_DATE);
    }
    
    public function getTriggeredTime()
    {
        return $this->getData(self::TRIGGERED_TIME);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
    
    



    public function setEntityId($entity_id)
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
    }
    
    public function setFileName($file_name)
    {
        return $this->setData(self::FILE_NAME, $file_name);
    }
    
    public function setTriggeredDate($triggered_date)
    {
        return $this->setData(self::TRIGGERED_DATE, $triggered_date);
    }
    
    public function setTriggeredTime($triggered_time)
    {
        return $this->setData(self::TRIGGERED_TIME, $triggered_time);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
    
    
}