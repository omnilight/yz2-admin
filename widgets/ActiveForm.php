<?php

namespace yz\admin\widgets;


/**
 * Class ActiveForm
 * @package yz\admin\widgets
 */
class ActiveForm extends \kartik\widgets\ActiveForm
{
    protected function initForm()
    {
        if (!isset($this->fieldConfig['class'])) {
            $this->fieldConfig['class'] = \yz\admin\widgets\ActiveField::className();
        }

        parent::initForm();
    }

} 