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
     * @link https://github.com/omnilight/yz2-admin-tinymce
     * @param array $options
     * @return static the field object itself
     */
    public function tinyMce($options = [])
    {
        if (class_exists('yz\admin\tinymce\TinyMCE')) {
            $defaultOptions = [
                'plugins' => [
                    "advlist autolink lists link charmap anchor",
                    "searchreplace visualblocks code fullscreen",
                    "insertdatetime media table contextmenu paste"
                ],
                'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
            ];
            $options = ArrayHelper::merge($defaultOptions, $options);
            return $this->widget('yz\admin\tinymce\TinyMCE', [
                'clientOptions' => $options,
            ]);
        }
        return $this->textarea($options);
    }

    /**
     * Renders Select2 widget using https://github.com/vova07/yii2-select2-widget
     * If this extension is not installed,simple dropdown control will be shown instead
     * @link https://github.com/vova07/yii2-select2-widget
     * @param $items
     * @param $options
     * @return static
     */
    public function select2($items, $options)
    {

        $settings = ArrayHelper::remove($options, 'settings', []);
        if (class_exists('vova07\select2\Widget')) {
            $defaultSettings = [
                'width' => '100%',
            ];
            $settings = ArrayHelper::merge($defaultSettings, $settings);
            return $this->widget('vova07\select2\Widget', [
                'bootstrap' => true,
                'items' => $items,
                'options' => $options,
                'settings' => $settings,
            ]);
        }
        return $this->dropDownList($items, $options);
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