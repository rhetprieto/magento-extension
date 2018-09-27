<?php
namespace Skuiq\SyncModule\Model;

use Skuiq\SyncModule\Api\AddSwatchInterface as ApiInterface;

// This endpoints is a workaround for Magento2 bug where the options aren't being added to the 'swatch'.

class ApiAddSwatch implements ApiInterface {

  /**
   * @var \Magento\Swatches\Model\SwatchFactory
   */
  protected $_swatchFactory;

  public function __construct(
    \Magento\Swatches\Model\SwatchFactory $swatchFactory
    )
    {
      $this->_swatchFactory = $swatchFactory;
    }

  public function add_swatch($option_id, $store_id, $type, $value ){
      try {
        $existingSwatchFactory = $this->_swatchFactory->create()->load($option_id,'option_id')->getData();
        if(empty($existingSwatchFactory)) {
          $swatchFactory = $this->_swatchFactory->create();
            $data = array(
                'option_id' => $option_id,
                'store_id'  => $store_id,
                'type'      => $type,
                'value'     => $value
            );
            $swatchFactory = $swatchFactory->setData($data);
            $swatchFactory->save();
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
