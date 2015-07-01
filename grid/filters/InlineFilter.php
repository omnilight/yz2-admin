<?php

namespace yz\admin\grid\filters;
use yii\base\Object;


/**
 * Class InlineFilter
 */
class InlineFilter extends BaseFilter
{
    /**
     * @var string
     */
    public $content;

    /**
     * Renders the filter content
     * @return string
     */
    public function render()
    {
        return $this->content;
    }
}