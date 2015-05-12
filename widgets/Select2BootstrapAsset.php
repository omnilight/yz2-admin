<?php

namespace yz\admin\widgets;
use yii\web\AssetBundle;


/**
 * Class Select2BootstrapAsset
 */
class Select2BootstrapAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@yz/admin/widgets/assets/select2Bootstrap';
    /**
     * @inheritdoc
     */
    public $css = [
        'select2-bootstrap.min.css'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'vova07\select2\Asset',
        'yii\bootstrap\BootstrapAsset'
    ];
}