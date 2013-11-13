<?php

namespace yz\admin\components;


use yii\base\View;
use Yii;
use yii\bootstrap\BootstrapAsset;
use yz\admin\AdminAsset;

/**
 * Class BackendView
 * @package yz\admin\components
 */
class BackendView extends View
{
    public function init()
    {
        parent::init();
        AdminAsset::register($this);
    }
}