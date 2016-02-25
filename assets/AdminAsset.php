<?php

namespace yz\admin\assets;

use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\AssetBundle;


/**
 * Class AdminAsset
 * @package yz\admin\assets
 */
class AdminAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@yz/admin/assets/admin-asset';
    /**
     * @inheritdoc
     */
    public $css = [
        'css/admin.css',
    ];
    /**
     * @inheritdoc
     */
    public $js = [
        'js/admin.js',
    ];
    /**
     * @inheritdoc
     */
    public $depends = [
        'yz\admin\assets\AdminLTEAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yz\assets\YzAsset'
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);

        $url = Json::encode(Url::to(['/admin/general/route-to-url'], true));
        $js =<<<JS
yii.yz.admin.settings.routeToUrl = $url;
JS;
        $view->registerJs($js);
    }


}
