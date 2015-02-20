<?php

namespace yz\admin\actions;

use yii\base\Action;
use Yii;
use yii\data\DataProviderInterface;
use yz\admin\widgets\GridView;


/**
 * Class ExportAction
 * @property callable $gridColumns Function that provides columns of the grid that should be exported
 * @package \yz\admin\actions
 */
class ExportAction extends Action
{
    /**
     * Callback that provides data provider for exporting. Example:
     * ~~~
     * function($params) {
     *  $searchModel = new SearchModel();
     *  $dataProvider = $searchModel->search($params);
     *  $dataProvider->pagination = false;
     *  $dataProvider->sort = false;
     *  return $dataProvider;
     * }
     * ~~~
     * @var callable
     */
    public $dataProvider = null;
    /**
     * @var string
     */
    public $reportName = 'report';
    /**
     * @var callable
     */
    protected $_gridColumns = null;


    /**
     * @param callable $gridColumns
     */
    public function setGridColumns($gridColumns)
    {
        $this->_gridColumns = $gridColumns;
    }

    /**
     * @return callable
     */
    public function getGridColumns()
    {
        if ($this->_gridColumns == null) {
            $this->_gridColumns = [$this->controller, 'getGridColumns'];
        }
        return $this->_gridColumns;
    }

    public function run()
    {
        /** @var DataProviderInterface $dataProvider */
        $dataProvider = call_user_func($this->dataProvider, Yii::$app->request->getQueryParams());
        /** @var array $gridColumns */
        $gridColumns = call_user_func($this->getGridColumns());

        $grid = GridView::widget([
            'renderAllPages' => true,
            'layout' => "{items}",
            'showSettings' => false,
            'showTotal' => false,
            'tableOptions' => ['class' => '', 'border' => 1],
            'dataProvider' => $dataProvider,
            'columns' => $gridColumns,
        ]);

        $content = strtr(self::EXPORT_TEMPLATE, [
            '{name}' => $this->reportName,
            '{grid}' => $grid,
        ]);
        return Yii::$app->response->sendContentAsFile($content, $this->reportName . '.xls', 'application/msexcel');
    }

    const EXPORT_TEMPLATE = <<<HTML
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">

<head>
    <meta http-equiv=Content-Type content="text/html; charset=utf-8"/>
    <meta name=ProgId content=Excel.Sheet/>
    <meta name=Generator content="Microsoft Excel 11"/>

    <!--[if gte mso 9]>
    <xml>
        <x:excelworkbook>
            <x:excelworksheets>
                <x:excelworksheet>
                    <x:name>{name}</x:name>
                    <x:WorksheetOptions>
                        <x:DisplayGridlines/>
                    </x:WorksheetOptions>
                </x:excelworksheet>
            </x:excelworksheets>
        </x:excelworkbook>
    </xml>
    <![endif]-->
</head>
<body>
{grid}
</body>
</html>
HTML;
}