<?php

namespace Skuiq\SyncModule\Model\ResourceModel;

class OrmSettings extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * OrmSettings constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    ) {
        parent::__construct($context);
    }

    public function _construct()
    {
        $this->_init('skuiq_syncmodule_skuiq_settings', 'setting_id');
    }
}
