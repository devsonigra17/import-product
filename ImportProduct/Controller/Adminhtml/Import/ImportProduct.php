<?php

namespace Dev\ImportProduct\Controller\Adminhtml\Import;

use Magento\Framework\UrlInterface;
use Exception;

class ImportProduct extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $model;
    protected $urlBuilder;
    protected $productRepository;
    protected $logger;
    protected $messageManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Dev\ImportProduct\Model\FileFactory $model,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Dev\ImportProduct\Logger\Logger $logger,
        UrlInterface $urlBuilder,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->model = $model;
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        $this->messageManager = $messageManager;
    }

    public function execute()
    {
        
        
        $entity_id = $this->getRequest()->getParam('entity_id');
        $modelData = $this->getDataByEntityId($entity_id);
        $file_path = $modelData->getFilePath();
        $file_url = $this->urlBuilder->getBaseUrl()."media/".$file_path;
        
        $file = fopen($file_url,"r");
        $header = fgetcsv($file);
        $required_data_fields = 3;
        
        $messageArray = [];
        while (($row = fgetcsv($file)) !== FALSE) {
            $data_count = count($row);
            if($data_count < 1)
            {
                continue;
            }
            
            $data = array();
            $data = array_combine($header, $row);
            
            if((isset($data['sku'])))
            {
                $sku = $data['sku'];
                $product = $this->getProductBySku($sku);
                $product_name = $data['name'];
                $price = $data['price'];
                $weight = $data['weight'];
                $special_price = $data['special_price'];
                $special_price_from_date = $data['special_price_from_date'];
                $special_price_to_date = $data['special_price_to_date'];
                $updated_at = $data['updated_at'];

                $product = $this->getProductBySku($sku);
                try{
                    $product->setName($product_name);
                    $product->setPrice($price);
                    $product->setWeight($weight);
                    $product->setUpdatedAt($updated_at);
                    $product->setSpecialPrice($special_price);
                    $product->setSpecialPriceFromDate($special_price_from_date);
                    $product->setSpecialPriceToDate($special_price_to_date);
                    $importCsv = $product->save();

                    
                    $this->logger->info('Importing Successfully for product sku: '.$sku);
                    echo '<pre>'; 
                    print_r($product->debug());
                }
                catch(\Exception $e){
                    $this->logger->info('Error importing stock for product sku: '.$sku.'. '.$e->getMessage());
                    $this->messageManager->addError( __('Error importing stock for product sku: '.$sku));
                    continue;
                }
                if($importCsv)
                {
                    $messageArray[] = 1;
                }
                else{
                    $messageArray[] = 0;
                }
                unset($product);
                $message = array_flip($messageArray);
                if(($message[1]>=0) && (!isset($message[0])))
                {
                    $this->messageManager->addSuccess( __('File Name: <b>%1</b> . Imported Successfully !',$modelData->getFileName()));
                    $modelData->setStatus(1);
                    $modelData->save();
                }
                else{
                    $this->messageManager->addError( __('File Name: %1 . Not Imported !',$modelData->getFileName()));
                }
                fclose($file);
            }
        }
        exit;
        $this->_redirect('*/*/index');
    }
    
    public function getDataByEntityId($entity_id)
    {
        return $this->model->create()->load($entity_id);
    }
    public function getProductBySku($sku)
    {
        return $this->productRepository->get($sku);
    }
}