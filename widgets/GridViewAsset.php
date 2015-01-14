<?php

namespace yz\admin\widgets;
use yii\web\AssetBundle;


/**
 * Class GridViewAsset
 */
class GridViewAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@yz/admin/widgets/assets/gridView';

    public $js = [
        'grid-view.js',
    ];

    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);

        $view->registerJs('adminGridView.init();');
    }
}