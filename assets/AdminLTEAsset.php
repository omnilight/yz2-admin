<?php

namespace yz\admin\assets;
use yii\web\AssetBundle;


/**
 * Class AdminLTEAsset
 * @package \yz\admin\assets
 */
class AdminLTEAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@yz/admin/assets/admin-lte-asset/dist';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/AdminLTE.css',
        'css/skins/_all-skins.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/app.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
} 