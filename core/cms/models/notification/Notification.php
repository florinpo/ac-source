<?php

/**
 * This is the model class for table "{{notification}}".
 *
 * The followings are the available columns in table '{{notification}}':
 * @property integer $id
 * @property integer $user_id
 * @property integer $type
 * @property string $body
 * @property integer $create_time
 */
class Notification extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Notification the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{notification}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id', 'required'),
            array('user_id, type, create_time', 'numerical', 'integerOnly' => true),
            array('body, create_time', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, user_id, type, body, create_time', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'type' => 'Type',
            'body' => 'Body',
            'create_time' => 'Create Time',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('type', $this->type);
        $criteria->compare('body', $this->body, true);
        $criteria->compare('create_time', $this->create_time);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->create_time = strtotime('today');
                // we create the notification
            }
            return true;
        }
        else
            return false;
    }
    
    public function generateIcon() {
        $layout_asset = GxcHelpers::publishAsset(Yii::getPathOfAlias('common.layouts.default.assets'));
        if ($this->type == ConstantDefine::NOTIFICATION_OFFER) {
            $image = 'tag';
        } else if ($this->type == ConstantDefine::NOTIFICATION_FAVORITE) {
            $image = 'heart';
        } else if ($this->type == ConstantDefine::NOTIFICATION_REVIEW) {
            $image = 'chat';
        }
        return $layout_asset . '/images/icons/notification/' . $image . '.png';
    }
    
    

}