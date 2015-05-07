<?php

namespace yz\admin\widgets;

use yii\bootstrap\Alert;
use yii\bootstrap\Widget;
use yz\icons\Icons;
use yz\Yz;


/**
 * Class Alerts implements flash display for session's flashes
 * @package \yz\admin\widgets
 */
class Alerts extends Widget
{
    /**
     * @var array the alert types configuration for the flash messages.
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the bootstrap alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        Yz::FLASH_ERROR => 'alert-danger',
        Yz::FLASH_SUCCESS => 'alert-success',
        Yz::FLASH_INFO => 'alert-info',
        Yz::FLASH_WARNING => 'alert-warning'
    ];
    /**
     * @var array the alert icons
     */
    public $alertIcons = [
        Yz::FLASH_ERROR => 'ban',
        Yz::FLASH_SUCCESS => 'info',
        Yz::FLASH_INFO => 'warning',
        Yz::FLASH_WARNING => 'check'
    ];

    /**
     * @var string
     */
    public $template = '<div class="alerts">{alerts}</div>';

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    public function init()
    {
        parent::init();

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();
        $appendCss = isset($this->options['class']) ? ' ' . $this->options['class'] : '';

        $alerts = '';

        foreach ($flashes as $type => $message) {
            if (isset($this->alertTypes[$type])) {
                /* initialize css class for each alert box */
                $this->options['class'] = $this->alertTypes[$type] . $appendCss;

                /* assign unique id to each alert box */
                $this->options['id'] = $this->getId() . '-' . $type;

                $body = Icons::p($this->alertIcons[$type], ['class' => 'icon']) . $message;

                $alerts .= Alert::widget([
                    'body' => $body,
                    'closeButton' => $this->closeButton,
                    'options' => $this->options,
                ]);

                $session->removeFlash($type);
            }
        }

        if ($alerts)
            echo strtr($this->template, [
                '{alerts}' => $alerts
            ]);
    }

} 