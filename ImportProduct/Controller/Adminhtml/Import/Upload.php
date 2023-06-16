<?php
 
namespace Dev\ImportProduct\Controller\Adminhtml\Import;
 
use Exception;
use Dev\ImportProduct\Model\FileUploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
 
class Upload extends Action implements HttpPostActionInterface
{
    protected $fileUploader;

    public function __construct(
        Context $context,
        FileUploader $fileUploader
    ) {
        parent::__construct($context);
        $this->fileUploader = $fileUploader;
    }

    public function execute()
    {
        $fileId = $this->_request->getParam('param_name', 'file_path');
 
        try {
            $result = $this->fileUploader->saveFileToTmpDir($fileId);
        } catch (Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}