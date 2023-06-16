<?php

namespace Dev\ImportProduct\Api\Data;

interface ImportFileInterface
{
    
    const ENTITY_ID = 'entity_id';
    const FILE_NAME = 'file_name';
    const TRIGGERED_TIME = 'triggered_time';
    const STATUS = 'status';


    public function getEntityId();

    public function setEntityId($entity_id);


    public function getFileName();

    public function setFileName($file_name);


    public function getTriggeredTime();

    public function setTriggeredTime($triggered_time);


    public function getStatus();

    public function setStatus($status);

}
