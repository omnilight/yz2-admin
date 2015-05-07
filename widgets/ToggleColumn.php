<?php

namespace yz\admin\widgets;
use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\web\View;
use yz\icons\Icons;


/**
 * Class ToggleColumn
 * @property GridView $grid
 */
class ToggleColumn extends DataColumn
{
    /**
     * Toggle action that will be used as the toggle action in your controller
     * @var string
     */
    public $action = 'toggle';
    /**
     * Whether to use ajax or not
     * @var bool
     */
    public $enableAjax = true;
    /**
     * @var string
     */
    public $format = 'bool';
    /**
     * @var array
     */
    public $contentOptions = [
        'class' => 'grid-view-toggle-cell',
    ];

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        if ($this->enableAjax) {
            $this->registerJs();
        }
    }
    /**
     * Renders the data cell content.
     * @param mixed $model the data model
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]].
     * @return string the rendering result
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->grid->runInConsoleMode) {
            return parent::renderDataCellContent($model, $key, $index);
        }

        $attribute = $this->attribute;
        $value = $model->$attribute;
        $url = [$this->action, 'id' => $model->id, 'attribute' => $attribute];
        if ($value === null || $value == true) {
            $icon = 'toggle-on';
            $title = Yii::t('yii', 'Off');
        } else {
            $icon = 'toggle-off';
            $title = Yii::t('yii', 'On');
        }
        return Html::a(
            Icons::i($icon.' fa-lg'),
            $url,
            [
                'title' => $title,
                'class' => 'grid-view-toggle-cell-link',
                'data-method' => 'post',
                'data-pjax' => '0',
            ]
        );
    }
    /**
     * Registers the ajax JS
     */
    public function registerJs()
    {
        $js = <<< JS
            $("a.grid-view-toggle-cell-link").on("click", function(e) {
                e.preventDefault();
                $.post($(this).attr("href"), function(data) {
                  window.location = window.location;
                });
                return false;
            });
JS;
        $this->grid->view->registerJs($js, View::POS_READY, 'yz2admin-toggle-column');
    }
}