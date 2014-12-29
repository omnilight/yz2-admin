<?php

namespace yz\admin\widgets;

use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;


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
        if ($this->type == self::TYPE_INDEX) {
            Pjax::begin([
                'enablePushState' => false,
                'enableReplaceState' => false,
            ]);
        }
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
        if ($this->type == self::TYPE_INDEX) {
            Pjax::end();
        }
    }

    protected function registerScripts()
    {
        AjaxCrudAsset::register($this->view);
        $this->registerAjaxCrudStyles();
        if ($this->type == self::TYPE_INDEX) {
            $this->registerIndexLoader();
        }
    }

    protected function registerAjaxCrudStyles()
    {
        $cancel = \Yii::t('admin/t', 'Cancel');

        $title = \Yii::t('admin/t', 'Add record');
        $html =<<<HTML
<div class="box box-primary ajax-crud-item">
    <div class="box-header">
        <h4 class="box-title">{$title}</h4>
        <div class="box-tools pull-right">
            <button class="btn btn-danger btn-xs js-btn-ajax-crud-cancel"><i class="fa fa-times"></i> {$cancel}</button>
        </div>
    </div>
    <div class="box-body">{content}</div>
</div>
HTML;
        $createWrapper = Json::encode($html);

        $title = \Yii::t('admin/t', 'Edit record');
        $html =<<<HTML
<div class="box box-success ajax-crud-item">
    <div class="box-header">
        <h4 class="box-title">{$title}</h4>
        <div class="box-tools pull-right">
            <button class="btn btn-primary btn-xs js-btn-ajax-crud-cancel"><i class="fa fa-times"></i> {$cancel}</button>
        </div>
    </div>
    <div class="box-body">{content}</div>
</div>
HTML;
        $updateWrapper = Json::encode($html);
        $this->view->registerJs(implode("\n", [
            "ajaxCrud.createItemWrapper = {$createWrapper};",
            "ajaxCrud.updateItemWrapper = {$updateWrapper};",
        ]), View::POS_READY, __CLASS__.'.ajax-crud-styles');
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