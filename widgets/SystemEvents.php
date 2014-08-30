<?php

namespace yz\admin\widgets;
use yii\base\Widget;
use yii\di\Instance;
use yii\log\Logger;
use yz\admin\models\SystemEvent;
use yz\admin\models\User;


/**
 * Class SystemEvents
 * @property SystemEvent[] $items
 * @package \yz\admin\widgets
 */
class SystemEvents extends Widget
{
    /**
     * @var SystemEvent[]
     */
    protected $_items = [];

    public function init()
    {
        if (\Yii::$app->user->isGuest)
            return;
        if (!Instance::ensure(\Yii::$app->user->identity, User::className()))
            return;

        /** @var SystemEvent[] $events */
        $this->_items = SystemEvent::find()
            ->where(['is_viewed' => '0', 'user_id' => \Yii::$app->user->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

    }

    /**
     * @return SystemEvent[]
     */
    public function getItems()
    {
        return $this->_items;
    }
}