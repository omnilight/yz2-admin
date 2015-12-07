<?php

namespace yz\admin\helpers;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * Class AdminHtml
 * @package \yz\admin\helpers
 */
class AdminHtml
{
    const ACTION_BUTTON_NAME = '__action';
    const ACTION_SAVE_AND_LEAVE = 'save_and_leave';
    const ACTION_SAVE_AND_STAY = 'save_and_stay';
    const ACTION_SAVE_AND_CREATE = 'save_and_create';

    /**
     * Generates crud action button
     * @param string $action
     * @param bool|null $isNewRecord
     * @param array $options
     * @return string
     */
    public static function actionButton($action = self::ACTION_SAVE_AND_LEAVE, $isNewRecord = null, $options = [])
    {
        $content = ArrayHelper::getValue($options, 'content') ?: ArrayHelper::getValue(static::getActionButtonContent($isNewRecord), $action, 'Submit');
        ArrayHelper::remove($options, 'content');

        $options = array_merge(ArrayHelper::getValue(static::getActionButtonOptions(), $action, ['class' => 'btn btn-primary']), [
            'name' => self::ACTION_BUTTON_NAME,
            'value' => $action,
        ],  $options);
        return Html::submitButton($content, $options);
    }

    public static function getActionButtonOptions()
    {
        return [
            self::ACTION_SAVE_AND_LEAVE => ['class' => 'btn btn-primary'],
            self::ACTION_SAVE_AND_STAY => ['class' => 'btn btn-primary'],
            self::ACTION_SAVE_AND_CREATE => ['class' => 'btn btn-primary'],
        ];
    }

    /**
     * @param bool $isNewRecord
     * @return array
     */
    public static function getActionButtonContent($isNewRecord)
    {
        return [
            self::ACTION_SAVE_AND_LEAVE => $isNewRecord ? \Yii::t('admin/t', 'Create & Exit') : \Yii::t('admin/t', 'Update & Exit'),
            self::ACTION_SAVE_AND_STAY => $isNewRecord ? \Yii::t('admin/t', 'Create') : \Yii::t('admin/t', 'Update'),
            self::ACTION_SAVE_AND_CREATE => $isNewRecord ? \Yii::t('admin/t', 'Create & Then Create Another One') : \Yii::t('admin/t', 'Update & Then Create New One'),
        ];
    }
} 