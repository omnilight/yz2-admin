<?php

namespace yz\admin\controllers;


/**
 * Class GeneralController
 * @package \yz\admin\controllers
 */
class GeneralController extends Controller
{
    public function actionInfo()
    {
        return $this->render('info');
    }
} 