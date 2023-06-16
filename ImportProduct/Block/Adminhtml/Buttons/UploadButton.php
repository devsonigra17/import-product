<?php

namespace Dev\ImportProduct\Block\Adminhtml\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class UploadButton extends GenericButton implements ButtonProviderInterface
{
    public function getButtonData()
    {
        return [
            'label' => __('Upload'),
            'class' => 'upload primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'upload']],
                'form-role' => 'upload',
            ],
            'sort_order' => 90,
        ];
    }
}
