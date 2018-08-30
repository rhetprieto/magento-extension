<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Skuiq_SyncModule',
    isset($file) ? dirname($file) : __DIR__
);

// The below code is the one that should be used for production.\

//
// <?php
// \Magento\Framework\Component\ComponentRegistrar::register(
// 	\Magento\Framework\Component\ComponentRegistrar::MODULE,
// 	'Skuiq_SyncModule',
// 	__DIR__
// );
