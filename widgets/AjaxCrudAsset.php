<?php

namespace yz\admin\widgets;

use yii\web\AssetBundle;


/**
 * Class AjaxCrudAsset
 */
class AjaxCrudAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@yz/admin/widgets/assets/ajaxCrud';

    public $js = [
        'ajax-crud.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);

        $view->registerJs('ajaxCrud.init();');
    }


} 