<?php

namespace Dev\ImportProduct\Controller\Adminhtml\Import;

use Magento\Backend\App\Action;

class Add extends \Magento\Backend\App\Action
{
    protected $_coreRegistry;

    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }
    

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();

        $resultPage->getConfig()->getTitle()
            ->prepend(__('Upload CSV File'));

        return $resultPage;
    }
}
