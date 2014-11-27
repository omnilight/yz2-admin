<?php

namespace yz\admin\widgets;

use Yii;
use yii\base\Model;


/**
 * Class ActiveForm
 * @method ActiveField field(Model $model, string $attribute, array $options = [])
 */
class ActiveForm extends \yii\bootstrap\ActiveForm
{
    public $layout = 'horizontal';

    /**
     * @var string the default field class name when calling [[field()]] to create a new field.
     * @see fieldConfig
     */
    public $fieldClass = 'yz\admin\widgets\ActiveField';

    public $fieldConfig = [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-2',
            'wrapper' => 'col-sm-8',
            'error' => '',
            'hint' => 'col-sm-offset-2 col-sm-8',
        ],
    ];
}