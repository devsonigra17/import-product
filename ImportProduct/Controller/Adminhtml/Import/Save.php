<?php

namespace Dev\ImportProduct\Controller\Adminhtml\Import;

use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;

class Save extends \Magento\Backend\App\Action
{

    protected $importFactory;
    protected $adapterFactory;
    protected $messageManager;
    protected $filesystem;
    protected $uploaderFactory;
 
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Dev\ImportProduct\Model\FileFactory $importFactory,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        \Magento\Framework\Message\ManagerInterface $messageManager
    )
    {
        parent::__construct($context);
        $this->importFactory = $importFactory;
        $this->messageManager = $messageManager;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dev_ImportProduct::save');
    }
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $model = $this->importFactory->create();
        
        $entity_id = $data['entity_id'];
        $file_name = $data['file_name'];
        $file_path = $data['file_path'][0]['file_path'];

        $date_time = $data['triggered_time'];
        $date_time2 = str_replace("T"," ",$date_time);
        $triggered_time = str_replace(".000Z","",$date_time2);


        $month = substr($triggered_time,5,2);
        $date = substr($triggered_time,8,2);
        $hour = substr($triggered_time,11,2);
        $minute = substr($triggered_time,14,2);       

        try {

            if($entity_id!=NULL)
            {
                $model->addData([

                    'entity_id' => $entity_id,
                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'triggered_time' => $triggered_time,
        
                ]);
            }
            if($entity_id==null)
            {
                $model->addData([

                    'file_name' => $file_name,
                    'file_path' => $file_path,
                    'triggered_time' => $triggered_time,
        
                ]);
            }
            $saveData = $model->save();
    
            if($saveData)
            {
                // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                // $scheduleTime = $minute." ".$hour." ".$date." ".$month." *"; 
                // $cronExpr = $triggered_time;
                // $jobCode = strtolower($file_name);
                // echo "<pre>";
                // echo $cronExpr;
                // echo "<pre>";
                // echo $jobCode;
                // // exit("==================");
                // $cronManager = $objectManager->create(\Dev\ImportProduct\Model\CronManager::class);
                // $cronCreate = $cronManager->setCron($jobCode,$cronExpr);
                // exit('=====');
                // if($cronCreate)
                // {
                //     $this->messageManager->addSuccess( __('Cron Scheduled Successfully !') );
                // }
                // else{
                //     $this->messageManager->addError( __('Cron Not Scheduled !') );
                // }
                if($entity_id==null)
                {
                    $this->messageManager->addSuccess( __('File Uploaded Successfully !') );
                }
                if($entity_id!=null)
                {
                    $this->messageManager->addSuccess( __('Data Updated Successfully !') );
                }

            }
        }catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('*/*/index');
    }
}