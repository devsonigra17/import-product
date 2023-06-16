<?php

namespace Dev\ImportProduct\Ui\Listing\Import;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class ImportProduct extends Column
{
    
    const PRODUCT_IMPORT_URL = 'importcsv/import/importproduct';
  
    protected $_urlBuilder;
    private $_product_import_url;
    private $escaper;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = [],
        $product_import_url = self::PRODUCT_IMPORT_URL
    ) 
    {
        $this->_urlBuilder = $urlBuilder;
        $this->_product_import_url = $product_import_url;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                if (isset($item['entity_id'])) {
                    $item[$name]['edit'] = [
                        'href' => $this->_urlBuilder->getUrl(
                            $this->_product_import_url, 
                            ['entity_id' => $item['entity_id']]
                        ),
                        'label' => __('Import Product'),
                    ];
                }
            }
        }

        return $dataSource;
    }

    private function getEscaper()
    {
        if (!$this->escaper) {
            $this->escaper = ObjectManager::getInstance()->get(Escaper::class);
        }
        return $this->escaper;
    }
}