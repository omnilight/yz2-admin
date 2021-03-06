<?php

namespace yz\admin\grid;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\db\Query;
use yii\grid\Column;
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
    /** This event is triggered on grid setup */
    const EVENT_SETUP_GRID = 'setupGrid';
    /** This event is triggered after page rendering */
    const EVENT_AFTER_RENDER_PAGE = 'afterRenderPage';

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
     * If true, total row will be displayed all the time. If false - it will not be shown.
     * If null (default) - it will be shown in the case when any of the data column will have total property
     * @var bool | null
     */
    public $showTotal;
    /**
     * If true, total label row will be added before to the total row (only if total row is visible)
     * @var bool
     */
    public $showTotalLabel = true;
    /**
     * Total condition that will be added to the total query. Could be string or a function in the form of:
     * ```
     * function (Query $query) {
     *
     * }
     * ```
     * @var string | callable
     */
    public $totalCondition;
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
        $this->trigger(self::EVENT_SETUP_GRID);
        if ($this->runInConsoleMode == false) {
            $pageSize = UserSetting::get(Yii::$app->user->id, $this->getGridId() . '.pageSize');
            if ($pageSize !== null) {
                $this->dataProvider->getPagination()->pageSize = $pageSize;
            }
        }
    }

    /**
     * @return string
     */
    public function getGridId()
    {
        return Yii::$app->controller->action->getUniqueId() . '.' . $this->getId();
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
        $this->dataProvider->getPagination()->pageSize = 200;
        $this->dataProvider->prepare(true);
        $totalPages = $this->dataProvider->getPagination()->pageCount;
        $pages = [];
        $this->startExportCycle();

        $tmpFile = tmpfile();

        for ($page = 0; $page < $totalPages; $page++) {

            if ($this->checkIfResumeExport() == false)
                break;

            fwrite($tmpFile, $this->renderSinglePage($page));

            $this->trigger(self::EVENT_AFTER_RENDER_PAGE);

            $this->endExportIteration($page);
        }

        fseek($tmpFile, 0);

        $content = '';
        while (!feof($tmpFile)) {
            $content .= fread($tmpFile, 1024);
        }

        fclose($tmpFile);

        return '<tbody>' . $content . '</tbody>';
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

    public function renderTableHeader()
    {
        $cells = [];
        foreach ($this->columns as $column) {
            /* @var $column Column */
            $cells[] = $column->renderHeaderCell();
        }
        $content = Html::tag('tr', implode('', $cells), $this->headerRowOptions);
        if ($this->filterPosition == self::FILTER_POS_HEADER) {
            $content = $this->renderFilters() . $content;
        } elseif ($this->filterPosition == self::FILTER_POS_BODY) {
            $content .= $this->renderFilters();
        }

        $content .= $this->renderTotal();

        return "<thead>\n" . $content . "\n</thead>";
    }

    public function renderTotal()
    {
        if ($this->showTotal === null) {
            $showTotal = false;
            foreach ($this->columns as $column) {
                if ($column instanceof \yz\admin\grid\columns\DataColumn) {
                    if ($column->total !== null) {
                        $showTotal = true;
                        break;
                    }
                }
            }
        } else {
            $showTotal = $this->showTotal;
        }

        if ($showTotal) {
            $cells = [];
            foreach ($this->columns as $column) {
                if ($column instanceof \yz\admin\grid\columns\DataColumn) {
                    $cells[] = $column->renderTotalCell();
                } else {
                    $cells[] = Html::tag('td', '', ['class' => 'grid-view-cell-total-empty']);
                }
            }
            if ($this->showTotalLabel) {
                $rows[] = Html::tag('tr', Html::tag('td', Yii::t('admin/gridview', 'Total:'), [
                    'colspan' => count($this->columns),
                    'class' => 'grid-view-row-total-label',
                ]));
            }
            $rows[] = Html::tag('tr', implode('', $cells), ['class' => 'grid-view-row-total']);
            return implode("\n", $rows);
        } else {
            return '';
        }
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

    protected $_totalData;

    /**
     * @return array|null
     * @TODO Refactor
     */
    public function getTotalData()
    {
        if ($this->dataProvider instanceof ActiveDataProvider) {
            if ($this->_totalData === null) {
                $query = $this->dataProvider->query;
                $totalQuery = (new Query())
                    ->from(['t' => $query]);
                if (is_callable($this->totalCondition)) {
                    call_user_func($this->totalCondition, $totalQuery);
                } elseif (is_string($this->totalCondition) || is_array($this->totalCondition)) {
                    $totalQuery->andWhere($this->totalCondition);
                }
                $select = [];
                foreach ($this->columns as $column) {
                    /* @var $column Column */
                    if ($column->canGetProperty('total')) {
                        $total = $column->total;
                        if (is_array($total)) {
                            $select = array_merge($select, $total);
                        }
                    }
                }
                $totalQuery->select($select);
                $this->_totalData = $totalQuery->one();
            }
            return $this->_totalData;
        } elseif ($this->dataProvider instanceof SqlDataProvider) {
            if ($this->_totalData === null) {
                $sql = $this->dataProvider->sql;
                $totalQuery = (new Query())
                    ->from(['t' => "({$sql})"])
                    ->params($this->dataProvider->params);
                if (is_callable($this->totalCondition)) {
                    call_user_func($this->totalCondition, $totalQuery);
                } elseif (is_string($this->totalCondition) || is_array($this->totalCondition)) {
                    $totalQuery->andWhere($this->totalCondition);
                }
                $select = [];
                foreach ($this->columns as $column) {
                    /* @var $column Column */
                    if ($column->canGetProperty('total')) {
                        $total = $column->total;
                        if (is_array($total)) {
                            $select = array_merge($select, $total);
                        }
                    }
                }
                $totalQuery->select($select);
                $this->_totalData = $totalQuery->one();
            }
            return $this->_totalData;
        } else {
            return null;
        }
    }
} 