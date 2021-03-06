<?php

namespace yz\admin\widgets;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;
use yz\admin\helpers\Rbac;
use yz\admin\helpers\RouteNormalizer;
use yz\icons\Icon;
use yz\icons\Icons;

/**
 * Class ActionButtons is the widget that renders buttons on the top of index, create and update pages
 * @property Button $indexButton
 * @property Button $createButton
 * @property Button $updateButton
 * @property Button $deleteButton
 * @property Button $returnButton
 * @property Button $searchButton
 * @package yz\admin\widgets
 */
class ActionButtons extends Widget
{
    /**
     * @var array|string
     */
    public $indexUrl = ['index'];
    /**
     * @var array|string
     */
    public $indexViewUrl = ['index-view'];
    /**
     * @var array|string
     */
    public $createUrl = ['create'];
    /**
     * @var array|string
     */
    public $createAjaxUrl = ['create'];
    /**
     * @var array|string
     */
    public $updateUrl = ['update'];
    /**
     * @var array|string
     */
    public $deleteUrl = ['delete'];
    /**
     * @var array|string
     */
    public $exportUrl = ['export'];
    /**
     * @var array|string
     */
    public $importUrl = ['import'];
    /**
     * Buttons order. Format:
     * ~~~
     * [ // Button group
     *    ['create', 'your_button', 'return', '@'] // @ other custom buttons
     * ]
     * ~~~
     * Default value is not button at all
     * @var array
     */
    public $order = [];
    /**
     * Custom buttons. If it has a form of array, than the following format could be used:
     * ~~~
     * [
     *  'customButton' => [
     *      'label' => 'My label',
     *      'icon' => Icons::o('some-icon'),
     *      'route' => ['my/route'],
     *      'class' => 'btn btn-default',
     *  ],
     * ]
     * ~~~
     * @var Button[]|callable[]|array
     */
    public $buttons = [];
    /**
     * @var bool Whether to add return URL to the links
     */
    public $addReturnUrl = true;
    /**
     * ID of the grid that buttons should interact with
     * @var string
     */
    public $gridId = null;
    /**
     * Instance of search model for current table. Used to create special parameters for
     * 'create' action
     * @var Model
     */
    public $searchModel = null;
    /**
     * Model class itself to find what class to change name to
     * @var string
     */
    public $modelClass = null;
    /**
     * @var Button
     */
    protected $_importButton;
    /**
     * @var Button
     */
    protected $_returnButton = null;
    /**
     * @var Button
     */
    protected $_indexButton = null;
    /**
     * @var Button
     */
    protected $_indexViewButton = null;
    /**
     * @var Button
     */
    protected $_createButton = null;
    /**
     * @var Button
     */
    protected $_updateButton = null;
    /**
     * @var Button
     */
    protected $_deleteButton = null;
    /**
     * @var Button
     */
    protected $_searchButton = null;
    /**
     * @var Button
     */
    protected $_exportButton = null;
    /**
     * @var Button
     */
    protected $_createAjaxButton = null;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        ActionButtonsAsset::register($this->getView());

        $customButtons = $this->buttons;
        $standardButtons = [
            'index', 'index-view', 'create', 'create-ajax',
            'update', 'delete', 'return', 'search', 'export',
            'import',
        ];
        // List of the buttons that will be done in the future
        $reservedButtons = [];

        echo Html::beginTag('div', ['class' => 'action-buttons']);
        foreach ($this->order as $group) {
            $buttons = [];
            foreach ($group as $name) {
                if ($name == '@') {
                    // All other buttons
                    $buttons = array_merge($buttons, array_values($customButtons));
                } elseif (isset($customButtons[$name])) {
                    $buttons[] = $this->createCustomButton($customButtons[$name]);
                } elseif (in_array($name, $standardButtons)) {
                    if (($button = $this->{Inflector::id2camel($name) . 'Button'}) !== false)
                        $buttons[] = $button;
                } elseif (in_array($name, $reservedButtons)) {
                    continue;
                } else {
                    throw new InvalidConfigException('Button "' . $name . '" defined in the $order parameter does not not exist');
                }
            }

            echo ButtonGroup::widget([
                'buttons' => $buttons,
            ]);
        }
        echo Html::endTag('div');
    }

    /**
     * Creates custom button
     * @param Button|array|callable $buttonConfig
     * @return string
     */
    protected function createCustomButton($buttonConfig)
    {
        if (is_callable($buttonConfig))
            return call_user_func($buttonConfig);
        elseif (is_array($buttonConfig)) {
            $icon = (isset($buttonConfig['icon']) && ($buttonConfig['icon'] instanceof Icon)) ? $buttonConfig['icon']->app(' ') : '';
            return Button::widget([
                'tagName' => 'a',
                'label' => $icon . $buttonConfig['label'],
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($buttonConfig['route']),
                    'class' => isset($buttonConfig['class']) ? $buttonConfig['class'] : 'btn btn-default',
                ],
            ]);
        } else
            return $buttonConfig;
    }

    /**
     * @return Button
     */
    public function getReturnButton()
    {
        if ($this->_returnButton === null) {

            $returnUrl = \Yii::$app->request->get('return');
            $indexUrl = Url::previous('__indexUrlParam');

            if ($returnUrl !== null) {
                $url = $returnUrl;
            } elseif ($indexUrl !== null) {
                $url = $indexUrl;
            } else {
                $url = null;
            }

            if ($url !== null) {
                $this->_returnButton = Button::widget([
                    'tagName' => 'a',
                    'label' => Icons::p('angle-left') . \Yii::t('admin/t', 'Go Back'),
                    'encodeLabel' => false,
                    'options' => [
                        'href' => Url::to(['/admin/main/return', 'url' => $url]),
                        'class' => 'btn btn-default',
                    ],
                ]);
            } else {
                $this->_returnButton = false;
            }
        }
        return $this->_returnButton;
    }

    /**
     * @param Button $returnButton
     */
    public function setReturnButton($returnButton)
    {
        $this->_returnButton = $returnButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getCreateButton()
    {
        if ($this->_createButton === null) {
            $url = $this->createUrl;
            if ($this->checkAccess($url) == false) {
                return null;
            }
            $attributes = $this->getModelAttributes();

            if ($attributes == []) {
                $this->_createButton = Button::widget([
                    'tagName' => 'a',
                    'label' => Icons::p('plus') . \Yii::t('admin/buttons', 'Create'),
                    'encodeLabel' => false,
                    'options' => [
                        'href' => Url::to($url),
                        'class' => 'btn btn-success',
                    ],
                ]);;
            } else {
                $this->_createButton = Html::tag('div', ButtonDropdown::widget([
                    'tagName' => 'a',
                    'label' => Icons::p('plus') . \Yii::t('admin/buttons', 'Create'),
                    'encodeLabel' => false,
                    'split' => true,
                    'dropdown' => [
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label' => Icons::p('plus') . \Yii::t('admin/t', 'Create with default parameters'),
                                'url' => Url::to($url),
                            ]
                        ]
                    ],
                    'options' => [
                        'class' => 'btn btn-success',
                        'href' => Url::to($url + $attributes),
                    ]
                ]), ['class' => 'btn-group']);
            }
        }
        return $this->_createButton;
    }

    /**
     * @param \yii\bootstrap\Button $createButton
     */
    public function setCreateButton($createButton)
    {
        $this->_createButton = $createButton;
    }

    /**
     * @return array
     */
    protected function getModelAttributes()
    {
        if ($this->searchModel === null || $this->modelClass === null)
            return [];

        $attributes = [];
        $model = new $this->modelClass;
        foreach ($this->searchModel->getAttributes() as $name => $value) {
            if ($value !== null)
                $attributes[Html::getInputName($model, $name)] = $value;
        }

        return $attributes;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getCreateAjaxButton()
    {
        if ($this->_createAjaxButton === null) {
            $url = $this->createAjaxUrl;
            if ($this->checkAccess($url) == false) {
                return null;
            }
            $this->_createAjaxButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('plus') . \Yii::t('admin/t', 'Add'),
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($url),
                    'class' => 'btn btn-success js-btn-ajax-crud-create',
                ],
            ]);;

        }
        return $this->_createAjaxButton;
    }

    /**
     * @param \yii\bootstrap\Button $createButton
     */
    public function setCreateAjaxButton($createAjaxButton)
    {
        $this->_createAjaxButton = $createAjaxButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getDeleteButton()
    {
        if ($this->_deleteButton === null) {
            $url = $this->deleteUrl;
            if ($this->checkAccess($url) == false) {
                return null;
            }
            $this->_deleteButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('trash-o') . \Yii::t('admin/t', 'Delete Checked'),
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($url),
                    'class' => 'btn btn-danger',
                    'id' => 'action-button-delete-checked',
                    'data-grid' => $this->gridId,
                    'data-confirm' => \Yii::t('admin/t', 'Are you sure to delete this items?')
                ],
            ]);
        }
        return $this->_deleteButton;
    }

    /**
     * @param \yii\bootstrap\Button $deleteButton
     */
    public function setDeleteButton($deleteButton)
    {
        $this->_deleteButton = $deleteButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getIndexButton()
    {
        if ($this->_indexButton === null) {
            $url = $this->indexUrl;
            if ($this->checkAccess($url) == false) {
                return null;
            }
            $this->_indexButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('list') . \Yii::t('admin/t', 'List'),
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($url),
                    'class' => 'btn btn-info',
                ],
            ]);
        }
        return $this->_indexButton;
    }

    /**
     * @param \yii\bootstrap\Button $indexButton
     */
    public function setIndexButton($indexButton)
    {
        $this->_indexButton = $indexButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getIndexViewButton()
    {
        if ($this->_indexViewButton === null) {
            $url = $this->indexViewUrl;
            if ($this->checkAccess($url) == false) {
                return null;
            }
            $this->_indexButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('list') . \Yii::t('admin/t', 'List'),
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($url),
                    'class' => 'btn btn-info',
                ],
            ]);
        }
        return $this->_indexViewButton;
    }

    /**
     * @param $indexViewButton
     */
    public function setIndexViewButton($indexViewButton)
    {
        $this->_indexViewButton = $indexViewButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getUpdateButton()
    {
        if ($this->_updateButton === null) {
            $url = $this->updateUrl;
            if ($this->checkAccess($url) == false) {
                return null;
            }
            $this->_updateButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('pencil') . \Yii::t('admin/t', 'Edit'),
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($url),
                    'class' => 'btn btn-success',
                ],
            ]);
        }
        return $this->_updateButton;
    }

    /**
     * @param \yii\bootstrap\Button $updateButton
     */
    public function setUpdateButton($updateButton)
    {
        $this->_updateButton = $updateButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getSearchButton()
    {
        if ($this->_searchButton === null) {
            $this->_searchButton = Button::widget([
                'label' => Icons::p('search-plus') . \Yii::t('admin/t', 'Search'),
                'encodeLabel' => false,
                'options' => [
                    'class' => 'btn btn-default',
                    'id' => 'action-button-search',
                ],
            ]);
        }
        return $this->_searchButton;
    }

    /**
     * @param \yii\bootstrap\Button $searchButton
     */
    public function setSearchButton($searchButton)
    {
        $this->_searchButton = $searchButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getExportButton()
    {
        if ($this->_exportButton === null) {
            $params = \Yii::$app->request->getQueryParams();
            $url = $this->exportUrl + $params;
            if ($this->checkAccess($url) == false) {
                return null;
            }
            $this->_exportButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('file-excel-o') . \Yii::t('admin/t', 'Экспорт в Excel'),
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($url),
                    'class' => 'btn btn-default',
                    'id' => 'action-button-export',
                ],
            ]);
        }
        return $this->_exportButton;
    }

    /**
     * @param \yii\bootstrap\Button $exportButton
     */
    public function setExportButton($exportButton)
    {
        $this->_exportButton = $exportButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getImportButton()
    {
        if ($this->_importButton === null) {
            $url = $this->importUrl;
            if ($this->checkAccess($url) == false) {
                return null;
            }
            if ($this->checkAccess($url) == false) {
                return null;
            }
            $this->_importButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('upload') . \Yii::t('admin/t', 'Импорт'),
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($url),
                    'class' => 'btn btn-default',
                    'id' => 'action-button-import',
                ],
            ]);
        }
        return $this->_importButton;
    }

    /**
     * @param string|array $route
     * @return bool
     */
    protected function checkAccess($route)
    {
        if (is_array($route)) {
            $route = reset($route);
        }
        $operation = Rbac::routeToOperation(RouteNormalizer::normalizeRoute($route));
        return \Yii::$app->user->can($operation);
    }
}