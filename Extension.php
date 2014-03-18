<?php

namespace yz\admin;


/**
 * Class Extension
 */
class Extension extends \yii\base\Extension
{
    public static function bootstrap()
    {
        parent::bootstrap();

        \Yii::$app->i18n->translations['yz/admin'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yz/admin/messages',
            'sourceLanguage' => 'en-US',
        ];
    }

} 