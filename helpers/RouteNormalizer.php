<?php

namespace yz\admin\helpers;
use yii\bootstrap\BaseHtml;
use yii\helpers\BaseUrl;


/**
 * Class RouteNormalizer is used only to access protected method normalizeRoute of the BaseUrl
 */
class RouteNormalizer extends BaseUrl
{
    public static function normalizeRoute($route)
    {
        return parent::normalizeRoute($route);
    }
}