<?php

namespace Dev\ImportProduct\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    public function getOptionArray()
    {
        $options = ['1' => __('Done'),'0' => __('Scheduled')];
        return $options;
    }

    public function getAllOptions()
    {
        $res = $this->getOptions();
        array_unshift($res, ['value' => '', 'label' => '']);
        return $res;
    }

    public function getOptions()
    {
        $res = [];
        foreach ($this->getOptionArray() as $index => $value) {
            $res[] = ['value' => $index, 'label' => $value];
        }
        return $res;
    }

    public function toOptionArray()
    {
        return $this->getOptions();
    }
}
