<?php

namespace yz\admin\widgets;

use vova07\select2\Asset;
use vova07\select2\Select2Asset;
use vova07\select2\Widget;
use yii\helpers\Json;


/**
 * Class Select2
 */
class Select2 extends Widget
{
    public function registerClientScript()
    {
        $view = $this->getView();
        $selector = '#' . $this->options['id'];
        $settings = Json::encode($this->settings);

        // Register asset
        $asset = Asset::register($view);

        if ($this->language !== null) {
            $asset->language = $this->language;
        }

        if ($this->bootstrap === true) {
            Select2BootstrapAsset::register($view);
        } else {
            Select2Asset::register($view);
        }

        // Init widget
        $view->registerJs("jQuery('$selector').select2($settings);", $view::POS_READY, self::INLINE_JS_KEY . $this->options['id']);

        // Register events
        $this->registerEvents();
    }

}