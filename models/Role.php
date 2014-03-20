<?php

namespace yz\admin\models;

use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Manager;


/**
 * Class Role
 * @property Manager $authManager
 * @property Item $authItem
 * @property array $childRoles
 * @property array $childOperationsAndTasks
 * @package \yz\admin\models
 */
class Role extends AuthItem
{
    /**
     * @var Manager
     */
    protected $_authManager;
    /**
     * @var array
     */
    protected $_childRoles;
    /**
     * @var array
     */
    protected $_childOperationsAndTasks;

    public static function modelTitle()
    {
        return \Yii::t('admin/t', 'User Role');
    }

    public static function modelTitlePlural()
    {
        return \Yii::t('admin/t', 'User Roles');
    }

    public static function createQuery($config = [])
    {
        return parent::createQuery($config)->andWhere(['type' => Item::TYPE_ROLE]);
    }


    public function rules()
    {
        return array_merge(parent::rules(), [
            [['name', 'description'], 'required'],
            [['name', 'description'], 'unique'],
            [['name'], 'match', 'pattern' => '/[a-zA-Z0-9_\-]+/',
                'message' => \Yii::t('admin/t', 'Name of the role must contain only characters from the list: {chars}', [
                        'chars' => 'a-z, A-Z, 0-9, -'
                    ])],
            [['childRoles','childOperationsAndTasks'], 'safe'],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'childRoles' => \Yii::t('admin/t','Child roles'),
            'childOperationsAndTasks' => \Yii::t('admin/t','Child operations and tasks'),
        ]);
    }

    /**
     * @return Manager
     */
    public function getAuthManager()
    {
        if ($this->_authManager == null) {
            $this->_authManager = \Yii::$app->authManager;
        }
        return $this->_authManager;
    }

    /**
     * @param Manager $authManager
     */
    public function setAuthManager($authManager)
    {
        $this->_authManager = $authManager;
    }

    /**
     * @return Item
     */
    public function getAuthItem()
    {
        return $this->getAuthManager()->getItem($this->name);
    }

    /**
     * @param Item[] $items
     * @param integer|array $type
     * @return Item[]
     */
    protected static function filterItems($items, $type)
    {
        $type = (array)$type;
        return array_filter($items, function ($item) use ($type) {
            /** @var Item $item */
            return in_array($item->type, $type);
        });
    }

    /**
     * @param array $childRoles
     */
    public function setChildRoles($childRoles)
    {
        $this->_childRoles = $childRoles;
    }

    /**
     * @return array
     */
    public function getChildRoles()
    {
        if ($this->_childRoles === null && !$this->isNewRecord) {
            $this->_childRoles =
                ArrayHelper::getColumn(self::filterItems($this->getAuthManager()->getItemChildren($this->name), Item::TYPE_ROLE), 'name');
        }
        return $this->_childRoles;
    }

    /**
     * @return array
     */
    public function getChildRolesValues()
    {
        /** @var ActiveQuery $query */
        $query = AuthItem::find()->asArray()
            ->where(['type' => [Item::TYPE_ROLE]]);
        if (!$this->isNewRecord)
            $query->andWhere('name != :name', [':name' => $this->name]);
        return ArrayHelper::map($query->all(), 'name','description');
    }

    /**
     * @param array $childOperationsAndTasks
     */
    public function setChildOperationsAndTasks($childOperationsAndTasks)
    {
        $this->_childOperationsAndTasks = $childOperationsAndTasks;
    }

    /**
     * @return array
     */
    public function getChildOperationsAndTasks()
    {
        if ($this->_childOperationsAndTasks == null && !$this->isNewRecord) {
            $this->_childOperationsAndTasks =
                ArrayHelper::getColumn(self::filterItems($this->getAuthManager()->getItemChildren($this->name), [Item::TYPE_OPERATION, Item::TYPE_TASK]), 'name');
        }
        return $this->_childOperationsAndTasks;
    }

    /**
     * @return array
     */
    public function getChildOperationsAndTasksValues()
    {
        /** @var ActiveQuery $query */
        $query = AuthItem::find()->asArray()
            ->where(['type' => [Item::TYPE_OPERATION, Item::TYPE_TASK]]);
        if (!$this->isNewRecord)
            $query->andWhere('name != :name', [':name' => $this->name]);
        return ArrayHelper::map($query->all(), 'name','description');
    }

    public function beforeValidate()
    {
        $this->type = Item::TYPE_ROLE;

        return parent::beforeValidate();
    }

    public function afterSave($insert)
    {
        parent::afterSave($insert);

        if ($this->_childOperationsAndTasks !== null) {
            foreach ($this->_childOperationsAndTasks as $name)
                $this->getAuthManager()->addItemChild($this->name, $name);
        }

        if ($this->_childRoles !== null) {
            foreach ($this->_childRoles as $name)
                $this->getAuthManager()->addItemChild($this->name, $name);
        }
    }


}