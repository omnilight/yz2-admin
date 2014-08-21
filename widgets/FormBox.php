<?php

namespace yz\admin\widgets;
use yii\bootstrap\ActiveForm;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * Class FormBox
 * @package \yz\admin\widgets
 */
class FormBox extends Box
{
    public $actionsOptions = [
        'class' => 'col-sm-offset-2',
    ];

    public function beginFooter($options = [])
    {
        $actionsOptions = ArrayHelper::getValue($options, 'actionsOptions', []);
        ArrayHelper::remove($options, 'actionsOptions');
        parent::beginFooter($options);
        echo Html::beginTag('div', array_merge($this->actionsOptions, $actionsOptions));
    }

    public function endFooter()
    {
        echo Html::endTag('div');
        parent::endFooter();
    }


    /**
     * Renders all actions of the form
     * @param array|string $actions
     * @param array $options
     */
    public function actions($actions, $options = [])
    {
        $actions = (array)$actions;
        $this->beginFooter($options);
        echo implode("\n", $actions);
        $this->endFooter();
    }
} 