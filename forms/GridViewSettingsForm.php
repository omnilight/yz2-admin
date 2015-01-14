<?php

namespace yz\admin\forms;
use yii\base\Model;
use yz\admin\models\UserSetting;


/**
 * Class GridViewSettingsForm
 */
class GridViewSettingsForm extends Model
{
    /**
     * @var string
     */
    public $gridId;
    /**
     * @var integer
     */
    public $userId;
    /**
     * @var array
     */
    public $pageSizeValues;
    /**
     * @var string
     */
    public $pageSize;

    public function rules()
    {
        return [
            [['pageSize'], 'integer'],
            [['pageSize'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'pageSize' => \Yii::t('admin/gridview', 'Elements on page')
        ];
    }


    public function save()
    {
        if ($this->validate()) {

            UserSetting::set($this->userId, $this->gridId.'.pageSize', $this->pageSize);

            return true;
        } else {
            return false;
        }
    }

    public function getPageSizeValues()
    {
        return array_combine($this->pageSizeValues, $this->pageSizeValues);
    }
}