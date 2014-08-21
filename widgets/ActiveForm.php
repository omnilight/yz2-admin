<?php

namespace yz\admin\widgets;

use Yii;
use yii\helpers\Html;


/**
 * Class ActiveForm
 * @package yz\admin\widgets
 */
class ActiveForm extends \yii\bootstrap\ActiveForm
{


    public $layout = 'horizontal';

    public $fieldConfig = [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-2',
            'wrapper' => 'col-sm-8',
            'error' => '',
            'hint' => '',
        ],
    ];
}