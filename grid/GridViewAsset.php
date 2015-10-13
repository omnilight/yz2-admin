<?php

namespace yz\admin\grid;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;


/**
 * Class GridViewAsset
 */
class GridViewAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@yz/admin/grid/assets/grid-view';

    public $js = [
        'grid-view.js',
    ];

    public $depends = [
        JqueryAsset::class,
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);

        $view->registerJs('adminGridView.init();');
    }
}