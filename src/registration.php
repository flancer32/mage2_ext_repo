<?php
/**
 * Script to register M2-module
 *
 * User: Alex Gusev <alex@flancer64.com>
 */
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    \Flancer32\Lib\Repo\Config::MODULE,
    __DIR__
);