<?php

namespace yz\admin\widgets;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\MaskedInput;


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
        $this->adjustLabelFor($options);
        $value = isset($options['value']) ? $options['value'] : Html::getAttributeValue($this->model, $this->attribute);
        if (!array_key_exists('id', $options)) {
            $options['id'] = Html::getInputId($this->model, $this->attribute);
        }
        $format = ArrayHelper::remove($options, 'format');
        if ($format !== null) {
            $value = \Yii::$app->formatter->format($value, $format);
        } else {
            $value = Html::encode($value);
        }
        $this->parts['{input}'] = Html::tag('p', $value, ['class' => 'form-control-static']);

        return $this;
    }

    /**
     * Renders TinyMCE widget using https://github.com/omnilight/yz2-admin-tinymce
     * If this extension is not installed, simple textarea will be shown instead
     * @see https://github.com/omnilight/yz2-admin-tinymce
     * @param array $options
     * @return static the field object itself
     */
    public function tinyMce($options = [])
    {
        if (class_exists('yz\admin\tinymce\TinyMCE')) {
            return $this->widget('yz\admin\tinymce\TinyMCE', [
                'clientOptions' => $options,
            ]);
        }
        return $this->textarea($options);
    }

    /**
     * Outputs masked input
     * @param string $mask
     * @param array $options
     * @return static the field object itself
     */
    public function maskedInput($mask = '', $options = [])
    {
        return $this->widget(MaskedInput::className(), array_merge([
            'mask' => $mask,
        ], $options));
    }
} 