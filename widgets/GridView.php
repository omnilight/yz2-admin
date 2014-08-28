<?php

namespace yz\admin\widgets;


/**
 * Class GridView
 * @package yz\admin\widgets
 */
class GridView extends \yii\grid\GridView
{
    /**
     * @var bool When this property is true, all pages that DataProvider reports, will be rendered
     * in this current page. The difference between using this option and setting pagination property of DataProvider
     * to false is that this thing allows to render very large amount of data on the single page
     */
    public $renderAllPages = false;

    public function renderTableBody()
    {
        if ($this->renderAllPages) {
            return $this->renderAllPages();
        }
        return parent::renderTableBody();
    }

    public function renderAllPages()
    {
        $totalPages = $this->dataProvider->getPagination()->pageCount;
        $pages = [];
        for ($page = 0; $page < $totalPages; $page++) {
            $this->dataProvider->getPagination()->page = $page;
            $this->dataProvider->prepare(true);
            $pageContent = parent::renderTableBody();
            $pageContent = str_replace('<tbody>', '', $pageContent);
            $pageContent = str_replace('</tbody>', '', $pageContent);
            $pages[] = $pageContent;
        }
        return  '<tbody>'.implode('', $pages).'</tbody>';
    }
} 