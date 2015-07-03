<?php

namespace yz\admin\behaviors;

use omnilight\datetime\DateTimeBehavior;
use omnilight\datetime\DateTimeRangeBehavior;
use yii\base\Behavior;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yz\admin\search\SearchModelEvent;
use yz\admin\search\SearchModelInterface;
use yz\db\ActiveRecord;


/**
 * Class DateRangeFilteringBehavior
 */
class DateRangeFilteringBehavior extends Behavior
{
    /**
     * List of attributes that should be available for filtering with date time range
     * Formats:
     * ```php
     * [
     *  'attribute', // Model attribute
     *  'attribute' => 'dbAttribute', // Model attribute and database attribute
     * ]
     * ```
     * @var array
     */
    public $attributes;

    /**
     * @var array
     */
    private $_rangeAttributes = [];

    public function events()
    {
        if ($this->owner instanceof SearchModelInterface) {
            return [
                SearchModelInterface::EVENT_AFTER_PREPARE_QUERY => 'afterPrepareQuery'
            ];
        }

        return [];
    }

    public function attach($owner)
    {
        parent::attach($owner);

        $this->attachSupportBehaviors($owner);
    }

    protected function attachSupportBehaviors(Component $owner)
    {
        $rangeAttributes = [];

        foreach ($this->attributes as $attribute => $dbAttribute) {
            if (is_int($attribute)) {
                $attribute = $dbAttribute;
            }
            $rangeAttributes[] = $attribute . '_start';
            $rangeAttributes[] = $attribute . '_end';

            $owner->attachBehavior(0, [
                'class' => DateTimeRangeBehavior::class,
                'startAttribute' => $attribute . '_start_local',
                'endAttribute' => $attribute . '_end_local',
                'targetAttribute' => $attribute . '_range',
            ]);
        }

        $owner->attachBehavior(0, [
            'class' => DateTimeBehavior::class,
            'originalFormat' => ['date', 'yyyy-MM-dd'],
            'targetFormat' => ['date', 'dd.MM.yyyy'],
            'attributes' => $rangeAttributes,
        ]);
    }

    public function canGetProperty($name, $checkVars = true)
    {
        return $this->hasAttribute($name) || parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return $this->hasAttribute($name) || parent::canSetProperty($name, $checkVars);
    }

    public function __get($name)
    {
        if ($this->hasAttribute($name)) {
            return $this->getAttribute($name);
        }

        return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($this->hasAttribute($name)) {
            $this->setAttribute($name, $value);
        }
        parent::__set($name, $value);
    }

    public function hasAttribute($name)
    {
        foreach ($this->attributes as $attribute => $dbAttribute) {
            if (is_int($attribute)) {
                $attribute = $dbAttribute;
            }
            switch (true) {
                case $attribute . '_start' === $name:
                case $attribute . '_end' === $name:
                    return true;
            }
        }
        return false;
    }

    public function setAttribute($name, $value)
    {
        $this->_rangeAttributes[$name] = $value;
    }

    public function getAttribute($name)
    {
        $value = ArrayHelper::getValue($this->_rangeAttributes, $name);

        if ($value === null) {
            $this->initAttribute($this->getOriginalAttribute($name));
            $value = ArrayHelper::getValue($this->_rangeAttributes, $name);
        }

        return $value;
    }

    public function initAttribute($name)
    {
        if ($this->owner instanceof ActiveRecord) {
            $this->setAttribute($name.'_start', $this->owner->find()->min($name));
            $this->setAttribute($name.'_end', $this->owner->find()->max($name));
        }
    }

    public function getOriginalAttribute($name)
    {
        return preg_replace('/(_start|_end)$/', '', $name);
    }

    public function __isset($name)
    {
        return $this->hasAttribute($name) || parent::__isset($name);
    }

    public function afterPrepareQuery(SearchModelEvent $e)
    {
        $query = $e->query;

        foreach ($this->attributes as $attribute => $dbAttribute) {
            if (is_int($attribute)) {
                $attribute = $dbAttribute;
            }
            $query->andFilterWhere([
                'between',
                "DATE({$dbAttribute})",
                $this->getAttribute($attribute . '_start'),
                $this->getAttribute($attribute . '_end')
            ]);
        }
    }


}