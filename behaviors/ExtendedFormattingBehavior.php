<?php

namespace yz\admin\behaviors;

use yii\base\Behavior;
use yii\helpers\Html;
use yii\i18n\Formatter;


/**
 * Class ExtendedFormattingBehavior
 */
class ExtendedFormattingBehavior extends Behavior
{
    public function asBooleanColored($value)
    {
        /** @var Formatter $owner */
        $owner = $this->owner;

        if ($value === null) {
            return $owner->nullDisplay;
        }

        $label = $value ? 'label-success': 'label-danger';
        return Html::tag('span', $owner->asBoolean($value), ['class' => 'label '.$label]);
    }
}
