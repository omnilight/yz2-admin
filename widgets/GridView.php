<?php

namespace yz\admin\widgets;
use yii\base\InvalidConfigException;
use yii\grid\Column;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\grid\GridViewAsset;
use yii\widgets\BaseListView;
use Yii;


/**
 * Class GridView extends base Yii's GridView. It provides such options as:
 *  - rendering all pages together (gives ability to reduce memory usage)
 *  - control execution time of the rendering (when we have a lot of rows, this could save us from ran out of time)
 *  - run in console mode (suppress all unsupported in console application options)
 * @package yz\admin\widgets
 */
class GridView extends \yii\grid\GridView
{
    /**
     * @inheritdoc
     */
    public $dataColumnClass = 'yz\admin\widgets\DataColumn';
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
    /**
     * @var bool If true gridview will not do things that are not available in the console application
     */
    public $runInConsoleMode = false;
    /**
     * @var string the layout that determines how different sections of the list view should be organized.
     * The following tokens will be replaced with the corresponding section contents:
     *
     * - `{summary}`: the summary section. See [[renderSummary()]].
     * - `{items}`: the list items. See [[renderItems()]].
     * - `{sorter}`: the sorter. See [[renderSorter()]].
     * - `{pager}`: the pager. See [[renderPager()]].
     */
    public $layout = "{settings}\n{summary}\n{items}\n{pager}";

    protected $_startExportTime;
    protected $_averageIterationTime;

    public function run()
    {
        if ($this->runInConsoleMode == false) {
            parent::run();
        } else {
            BaseListView::run();
        }
    }


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

            $pages[] = $this->renderSinglePage($page);

            $this->endExportIteration($page);
        }
        return '<tbody>' . implode('', $pages) . '</tbody>';
    }

    public function renderSection($name)
    {
        switch ($name) {
            case "{settings}":
                return $this->renderSettings();
            default:
                return parent::renderSection($name);
        }
    }

    public function renderSettings()
    {
        return '';
    }


    protected function initColumns()
    {
        parent::initColumns();
        if ($this->runInConsoleMode) {
            array_map(function($column){
                /** @var DataColumn $column */
                $column->enableSorting = false;
            }, $this->columns);
        }
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