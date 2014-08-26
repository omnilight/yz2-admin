<?php

namespace yz\admin\assets;
use yii\web\AssetBundle;


/**
 * Class AdminLTEAsset
 * @package \yz\admin\assets
 */
class AdminLTEAsset extends AssetBundle
{
    public $publishOptions = [
        'forceCopy' => YII_DEBUG,
    ];
    /**
     * @inheritdoc
     */
    public $sourcePath = '@yz/admin/assets/adminLteAsset';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/AdminLTE.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/adminLTE.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
} 