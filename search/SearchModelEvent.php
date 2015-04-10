<?php

namespace yz\admin\search;
use yii\base\Event;
use yii\data\DataProviderInterface;
use yii\db\ActiveQuery;


/**
 * Class SearchModelEvent represents the parameters needed by all search model in backend, see
 * [[SearchModelInterface]] for list of triggered events
 */
class SearchModelEvent extends Event
{
    /**
     * @var ActiveQuery
     */
    public $query;
    /**
     * @var DataProviderInterface
     */
    public $dataProvider;
}