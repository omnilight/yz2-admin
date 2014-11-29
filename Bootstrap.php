<?php

namespace yz\admin;
use yii\base\Application;
use yii\base\BootstrapInterface;


/**
 * Class Bootstrap
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $app->i18n->translations['admin/t'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yz/admin/messages',
            'sourceLanguage' => 'en-US',
        ];
        $app->i18n->translations['admin/gridview'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yz/admin/messages',
            'sourceLanguage' => 'en-US',
        ];
        $app->i18n->translations['admin/menu'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yz/admin/messages',
            'sourceLanguage' => 'en-US',
        ];
        $app->i18n->translations['admin/sysinfo'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@yz/admin/messages',
            'sourceLanguage' => 'en-US',
        ];
    }


} 