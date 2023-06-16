<?php
namespace Dev\ImportProduct\Block\Adminhtml\Buttons;

use Magento\Search\Controller\RegistryConstants;

class GenericButton
{
    protected $urlBuilder;

    protected $registry;

    protected $_objectManager;

    protected $id;

    protected $context;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $_objectManager
    ) {
        $this->urlBuilder = $context->getUrlBuilder();
        $this->registry = $registry;
        $this->_objectManager = $_objectManager;
        $this->context = $context;
    }

    public function getId()
    {
         $id = $this->context->getRequest()->getParam('id');
         return $id;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->urlBuilder->getUrl($route, $params);
    }

    public function getDataById($id)
    {
        return $this->model->create()->load($id);
    }
}