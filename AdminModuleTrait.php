<?php
/**
 * Created by PhpStorm.
 * User: Павел
 * Date: 29.10.2015
 * Time: 18:14
 */

namespace yz\admin;


/**
 * Trait AdminModuleTrait
 */
trait AdminModuleTrait
{
    /**
     * @return Module
     */
    public static function getAdminModule()
    {
        return \Yii::$app->getModule('admin');
    }
}