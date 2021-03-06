<?php

namespace yz\admin\helpers;


/**
 * Class AdminUrl
 * @package yz\admin\helpers
 * @deprecated
 */
class AdminUrl
{
    /**
     * Returns URL of the referencing page
     * @return string
     */
    public static function getReturnUrl()
    {
        return \Yii::$app->request->get(\Yii::$app->getModule('admin')->returnUrlVar, null);
    }

    /**
     * Returns array that could be used as a part of the route
     * @return array
     */
    public static function returnUrlRoute()
    {
        return [\Yii::$app->getModule('admin')->returnUrlVar => \Yii::$app->request->absoluteUrl];
    }
} 