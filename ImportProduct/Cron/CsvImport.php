<?php

namespace Dev\ImportProduct\Cron;

use Dev\ImportProduct\Logger\Logger;

class CsvImport
{
    protected $logger;
    protected $model;
    protected $urlBuilder;
    protected $productRepository;
    protected $resource_connection;
    protected $collectionFactory;

    public function __construct(
        \Dev\ImportProduct\Model\FileFactory $model,
        Logger $logger,
        \Dev\ImportProduct\Model\ResourceModel\File\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\UrlInterface $urlBuilder
    )
    {
        $this->logger = $logger;
        $this->model = $model;
        $this->urlBuilder = $urlBuilder;
        $this->productRepository = $productRepository;
        $this->collectionFactory = $collectionFactory;
    }
    public function execute()
    {
        // $this->logger->info('Cron Run');
        date_default_timezone_set("Asia/Kolkata");
        $collection = $this->getTriggeredTime();
        $entity_ids = [];
        foreach($collection as $result)
        {
            $current_time = strtotime(date("Y-m-d H:i:s"));
            $triggered_time = strtotime($result['triggered_time']);
            if($current_time >= $triggered_time)
            {
                $entity_ids[] = $result['entity_id'];
            }
        }
        foreach($entity_ids as $entity_id)
        {
            $modelData = $this->getDataByEntityId($entity_id);
            $file_path = $modelData->getFilePath();
            $file_url = $this->urlBuilder->getBaseUrl()."media/".$file_path;
            
            $file = fopen($file_url,"r");
            $header = fgetcsv($file);
            $required_data_fields = 3;

            while (($row = fgetcsv($file)) !== FALSE) {
                $data_count = count($row);
                if($data_count < 1)
                {
                    continue;
                }
                
                $data = array();
                $data = array_combine($header, $row);
                $sku = $data['sku'];
                if ($data_count < $required_data_fields) {
                    $this->logger->info("Skipping product sku " . $sku . ", not all required fields are present to create the product.");
                    continue;
                }
                $price = trim($data['price']);
                $special_price = trim($data['special_price']);
                $special_price_from_date = $data['special_price_from_date'];
                $special_price_to_date = $data['special_price_to_date'];
    
                $product = $this->getProductBySku($sku);
                try{
                    $product->setPrice($price);
                    $product->setSpecialPrice($special_price);
                    $product->setSpecialFromDate($special_price_from_date);
                    $product->setSpecialToDate($special_price_to_date);
                    $importCsv = $product->save();
                    $modelData->setStatus(1);
                    $modelData->save();
                    $this->logger->info('Importing Successfully for product sku: '.$sku);
                }
                catch(\Exception $e){
                    $this->logger->info('Error importing stock for product sku: '.$sku.'. '.$e->getMessage());
                    continue;
                }
                unset($product);
            }
        }
    }
    public function getTriggeredTime()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('status', 0);
        return $collection;
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