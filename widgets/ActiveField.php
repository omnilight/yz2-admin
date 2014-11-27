<?php

namespace yz\admin\widgets;
use yii\helpers\Html;


/**
 * Class ActiveField
 */
class ActiveField extends \yii\bootstrap\ActiveField
{
    /**
     * Renders a static control
     * This method will generate the "name" and "value" tag attributes automatically for the model attribute
     * unless they are explicitly specified in `$options`.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [[Html::encode()]].
     * @return static the field object itself
     */
    public function staticInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $value = Html::encode(Html::getAttributeValue($this->model, $this->attribute));
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::tag('p', $value, ['class' => 'form-control-static']);

        return $this;
    }
} 