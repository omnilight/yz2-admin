<?php

namespace yz\admin\widgets;

use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\widgets\BaseListView;
use yz\admin\models\UserSetting;
use yz\icons\Icons;


/**
 * Class GridView extends base Yii's GridView. It provides such options as:
 *  - rendering all pages together (gives ability to reduce memory usage)
 *  - control execution time of the rendering (when we have a lot of rows, this could save us from ran out of time)
 *  - run in console mode (suppress all unsupported in console application options)
 * This is the main component for displaying grid view in the admin panel
 *
 * @property string $gridId Unique id of the grid that consists of the action id and grid id
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
     * @var bool When this property is true, settings button will be shown on the top of the grid view
     */
    public $showSettings = true;
    /**
     * @var array Allowed pagesize
     */
    public $allowedPageSizes = [20, 30, 50, 100, 200];
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

    public function init()
    {
        $this->setupGrid();
        parent::init();
    }

    protected function setupGrid()
    {
        $pageSize = UserSetting::get(Yii::$app->user->id, $this->getGridId() . '.pageSize');
        if ($pageSize !== null) {
            $this->dataProvider->getPagination()->pageSize = $pageSize;
        }
    }


    public function run()
    {
        if ($this->runInConsoleMode == false) {
            GridViewAsset::register($this->getView());
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

    /**
     * @return string
     */
    public function getGridId()
    {
        return Yii::$app->controller->action->getUniqueId() . '.' . $this->getId();
    }

    public function renderSettings()
    {
        if ($this->showSettings == false)
            return '';

        return Html::a(Icons::p('gears') . Yii::t('admin/gridview', 'Grid Settings'), ['#'], [
            'class' => 'pull-right btn btn-default btn-xs btn-grid-settings js-btn-admin-grid-settings',
            'data' => [
                'gridUniqueId' => $this->getGridId(),
                'currentPageSize' => $this->dataProvider->getPagination()->pageSize,
                'pageSizes' => $this->allowedPageSizes,
            ]
        ]);
    }


    protected function initColumns()
    {
        parent::initColumns();
        if ($this->runInConsoleMode) {
            array_map(function ($column) {
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