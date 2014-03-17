<?php

namespace yz\admin\widgets;

use yii\base\Widget;
use yii\bootstrap\Alert;
use yz\Yz;


/**
 * Class Flashes implements flash display for session's flashes
 * @package \yz\admin\widgets
 */
class Flashes extends Widget
{
    public $classes = [
        Yz::FLASH_INFO => 'alert-info',
        Yz::FLASH_ERROR => 'alert-danger',
        Yz::FLASH_WARNING => 'alert-warning',
        Yz::FLASH_SUCCESS => 'alert-success',
    ];

    public function run()
    {
        foreach (\Yii::$app->session->getAllFlashes() as $type => $message) {
            Alert::widget([
                'body' => $message,
                'options' => ['class' => 'alert ' . $this->classes[$type]],
                'closeButton' => [
                    'label' => '&times;',
                    'tag' => 'a',
                ],
            ]);
        }
    }

} 