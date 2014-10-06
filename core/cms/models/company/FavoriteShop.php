<?php

/**
 * This is the model class for table "{{favorite_shop}}".
 *
 * The followings are the available columns in table '{{favorite_shop}}':
 * @property string $shopId
 * @property string $userId
 * @property integer $create_time
 * @property integer $notification
 */
class FavoriteShop extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FavoriteShop the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{favorite_shop}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('shopId, userId', 'length', 'max' => 20),
            array('create_time, notification', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('shopId, userId, create_time, notification', 'safe', 'on' => 'search'),
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
            'shopId' => 'Shop',
            'userId' => 'User',
            'create_time' => 'Create Time',
            'notification' => 'Notification',
        );
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->create_time = time();
            }

            return true;
        }
        else
            return false;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('shopId', $this->shopId, true);
        $criteria->compare('userId', $this->userId, true);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('notification', $this->notification);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}