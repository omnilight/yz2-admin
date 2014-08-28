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
    /**
     * @var bool When this property is true and [[$renderAllPages]] is also true, than GridView will control execution
     * time to prevent error
     */
    public $controlExecutionTime = true;

    protected $_startExportTime;
    protected $_averageIterationTime;

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
        $this->startExportCycle();
        for ($page = 0; $page < $totalPages; $page++) {

            if ($this->checkIfResumeExport() == false)
                break;

            $pages = $this->renderSinglePage($page);

            $this->endExportIteration($page);
        }
        return '<tbody>' . implode('', $pages) . '</tbody>';
    }

    /**
     * Marks start of exporting
     */
    protected function startExportCycle()
    {
        $this->_startExportTime = time();
        $this->_averageIterationTime = 0;
    }

    /**
     * @return bool
     */
    protected function checkIfResumeExport()
    {
        if ($this->controlExecutionTime == false)
            return true;
        if (self::getMaxExecutionTime() == 0)
            return true;
        return (time() - $this->_startExportTime) < (self::getMaxExecutionTime() - $this->_averageIterationTime);
    }

    protected static function getMaxExecutionTime()
    {
        return ini_get('max_execution_time');
    }

    protected function renderSinglePage($page)
    {
        $this->dataProvider->getPagination()->page = $page;
        $this->dataProvider->prepare(true);
        $pageContent = parent::renderTableBody();
        $pageContent = str_replace('<tbody>', '', $pageContent);
        $pageContent = str_replace('</tbody>', '', $pageContent);
        return $pageContent;
    }

    /**
     * @param $iteration integer
     */
    protected function endExportIteration($iteration)
    {
        $this->_averageIterationTime = (time() - $this->_startExportTime) / ($iteration + 1);
    }
} 