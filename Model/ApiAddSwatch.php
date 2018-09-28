<?php

// This endpoints is a workaround for Magento2 bug where the options aren't being added to the 'swatch'.
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\AddSwatchInterface as ApiInterface;

class ApiAddSwatch implements ApiInterface
{

    /**
     * @var \Magento\Swatches\Model\SwatchFactory
     */
    protected $swatchFactory;

    /**
     * ApiAddSwatch constructor.
     * @param \Magento\Swatches\Model\SwatchFactory $swatchFactory
     */
    public function __construct(
        \Magento\Swatches\Model\SwatchFactory $swatchFactory
    ) {
        $this->swatchFactory = $swatchFactory;
    }

    public function addSwatch($option_id, $store_id, $type, $value)
    {
        try {
            $existing_swatch = $this->_swatchFactory->create()->load($option_id, 'option_id')->getData();
            if (empty($existing_swatch)) {
                $swatch_factory = $this->_swatchFactory->create();
                $data = [
                    'option_id' => $option_id,
                    'store_id'  => $store_id,
                    'type'      => $type,
                    'value'     => $value
                ];
                $swatch = $swatch_factory->setData($data);
                $swatch->save();
                $response = ['success' => "Added correctly!!"];
                return json_encode($response);
            } else {
                $response = ['success' => "It's already there!!"];
                return json_encode($response);
            }
        } catch (\Exception $exception) {
            $response = ['Error' => $exception->getMessage()];
            return json_encode($response);
        }
    }
}
