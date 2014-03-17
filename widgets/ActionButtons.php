<?php

namespace yz\admin\widgets;


use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\bootstrap\Button;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;
use yii\helpers\Url;
use yz\admin\helpers\AdminHelper;
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
    public $createUrl = ['create'];
    /**
     * @var array|string
     */
    public $updateUrl = ['update'];
    /**
     * @var array|string
     */
    public $deleteUrl = ['delete'];
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
     * Custom buttons
     * @var Button[]|callable[]
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
     * @throws \yii\base\InvalidConfigException
     */
    public function run()
    {
        ActionButtonsAsset::register($this->getView());

        $customButtons = $this->buttons;
        $standardButtons = ['index', 'create', 'update', 'delete', 'return', 'search'];
        // List of the buttons that will be done in the future
        $reservedButtons = ['export'];

        foreach ($this->order as $group) {
            $buttons = [];
            foreach ($group as $name) {
                if ($name == '@') {
                    // All other buttons
                    $buttons = array_merge($buttons, array_values($customButtons));
                } elseif (isset($customButtons[$name])) {
                    // One of the custom buttons
                    if (is_callable($customButtons[$name]))
                        $buttons[] = call_user_func($customButtons[$name]);
                    else
                        $buttons[] = $customButtons[$name];
                } elseif (in_array($name, $standardButtons)) {
                    if (($button = $this->{$name . 'Button'}) !== false)
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
    }

    /**
     * @param Button $returnButton
     */
    public function setReturnButton($returnButton)
    {
        $this->_returnButton = $returnButton;
    }

    /**
     * @return Button
     */
    public function getReturnButton()
    {
        if ($this->_returnButton === null) {
            if (AdminHelper::getReturnUrl() !== null) {
                $this->_returnButton = Button::widget([
                    'tagName' => 'a',
                    'label' => Icons::p('angle-left') . \Yii::t('yz/admin', 'Go Back'),
                    'encodeLabel' => false,
                    'options' => [
                        'href' => AdminHelper::getReturnUrl(),
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
     * @param \yii\bootstrap\Button $createButton
     */
    public function setCreateButton($createButton)
    {
        $this->_createButton = $createButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getCreateButton()
    {
        if ($this->_createButton === null) {
            $url = $this->createUrl + ($this->addReturnUrl ? AdminHelper::returnUrlRoute() : []);
            $attributes = $this->getModelAttributes();

            if ($attributes == []) {
                $this->_createButton = Button::widget([
                    'tagName' => 'a',
                    'label' => Icons::p('plus') . \Yii::t('yz/admin', 'Create'),
                    'encodeLabel' => false,
                    'options' => [
                        'href' => Url::to($url),
                        'class' => 'btn btn-success',
                    ],
                ]);;
            } else {
                $this->_createButton = Html::tag('div', ButtonDropdown::widget([
                    'tagName' => 'a',
                    'label' => Icons::p('plus') . \Yii::t('yz/admin', 'Create'),
                    'encodeLabel' => false,
                    'split' => true,
                    'dropdown' => [
                        'encodeLabels' => false,
                        'items' => [
                            [
                                'label' => Icons::p('plus') . \Yii::t('yz/admin', 'Create with default parameters'),
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
     * @param \yii\bootstrap\Button $deleteButton
     */
    public function setDeleteButton($deleteButton)
    {
        $this->_deleteButton = $deleteButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getDeleteButton()
    {
        if ($this->_deleteButton === null) {
            $url = $this->deleteUrl + ($this->addReturnUrl ? AdminHelper::returnUrlRoute() : []);
            $this->_deleteButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('trash-o') . \Yii::t('yz/admin', 'Delete Checked'),
                'encodeLabel' => false,
                'options' => [
                    'href' => Url::to($url),
                    'class' => 'btn btn-danger',
                    'id' => 'action-button-delete-checked',
                    'data-grid' => $this->gridId,
                    'data-confirm' => \Yii::t('yz/admin', 'Are you sure to delete this items?')
                ],
            ]);
        }
        return $this->_deleteButton;
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
    public function getIndexButton()
    {
        if ($this->_indexButton === null) {
            $url = $this->indexUrl + ($this->addReturnUrl ? AdminHelper::returnUrlRoute() : []);
            $this->_indexButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('list') . \Yii::t('yz/admin', 'List'),
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
     * @param \yii\bootstrap\Button $updateButton
     */
    public function setUpdateButton($updateButton)
    {
        $this->_updateButton = $updateButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getUpdateButton()
    {
        if ($this->_updateButton === null) {
            $url = $this->updateUrl + ($this->addReturnUrl ? AdminHelper::returnUrlRoute() : []);
            $this->_updateButton = Button::widget([
                'tagName' => 'a',
                'label' => Icons::p('pencil') . \Yii::t('yz/admin', 'Edit'),
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
     * @param \yii\bootstrap\Button $searchButton
     */
    public function setSearchButton($searchButton)
    {
        $this->_searchButton = $searchButton;
    }

    /**
     * @return \yii\bootstrap\Button
     */
    public function getSearchButton()
    {
        if ($this->_searchButton === null) {
            $this->_searchButton = Button::widget([
                'label' => Icons::p('search-plus') . \Yii::t('yz/admin', 'Search'),
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
}