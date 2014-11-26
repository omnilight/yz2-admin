<?php

namespace yz\admin\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;


/**
 * Class AjaxCrud
 */
class AjaxCrud extends Widget
{
    const TYPE_INDEX = 'index';
    const TYPE_CREATE = 'create';
    const TYPE_UPDATE = 'update';

    public $name;
    public $type = self::TYPE_INDEX;
    public $url;

    public function run()
    {
        $this->registerScripts();
        echo Html::tag('div', '', [
            'class' => 'ajax-crud-container ajax-crud-type-'.$this->type.' ajax-crud-name-'.$this->name,
            'id' => $this->getContainerId(),
            'data' => [
                'type' => $this->type,
                'name' => $this->name,
                'container-index' => '#'.$this->getContainerId(self::TYPE_INDEX),
                'container-create' => '#'.$this->getContainerId(self::TYPE_CREATE),
                'container-update' => '#'.$this->getContainerId(self::TYPE_UPDATE),
            ]
        ]);
    }

    protected function registerScripts()
    {
        AjaxCrudAsset::register($this->view);
        if ($this->type == self::TYPE_INDEX) {
            $this->registerIndexLoader();
        }
    }

    protected function registerIndexLoader()
    {
        $url = Url::to($this->url);
        $containerId = $this->getContainerId(self::TYPE_INDEX);
        $js =<<<JS
ajaxCrud.load('#{$containerId}', '{$url}');
JS;
        $this->view->registerJs($js, View::POS_READY, __CLASS__.'.'.$this->name.'.index_loader');
    }

    public function getContainerId($type = null)
    {
        $type = $type ?: $this->type;
        return 'ajax-crud-'.$this->name.'-'.$type;
    }

    /**
     * @param $name
     * @param $url
     * @return string
     */
    public static function index($name, $url)
    {
        return self::widget([
            'name' => $name,
            'url' => $url,
        ]);
    }

    /**
     * @param $name
     * @return string
     */
    public static function create($name)
    {
        return self::widget([
            'type' => self::TYPE_CREATE,
            'name' => $name,
        ]);
    }

    /**
     * @param $name
     * @return string
     */
    public static function update($name)
    {
        return self::widget([
            'type' => self::TYPE_UPDATE,
            'name' => $name,
        ]);
    }
} 