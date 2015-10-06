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
        $app->i18n->translations['admin/buttons'] = [
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

        // Setup for formatter
        $app->formatter->attachBehavior('extendedFormatting', [
            'class' => 'yz\admin\behaviors\ExtendedFormattingBehavior',
        ]);

        if ($app instanceof \yii\console\Application) {
            $app->params['yii.migrations'][] = '@yz/admin/migrations';
            if (!isset($app->controllerMap['admin-users'])) {
                $app->controllerMap['admin-users'] = 'yz\admin\commands\AdminUsersController';
            }
        }
    }


} 