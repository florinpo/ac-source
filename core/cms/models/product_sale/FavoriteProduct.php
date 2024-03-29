<?php

/**
 * This is the model class for table "{{favorite_product}}".
 *
 * The followings are the available columns in table '{{favorite_product}}':
 * @property string $productId
 * @property string $userId
 * @property string $create_time
 * @property string $notification
 */
class FavoriteProduct extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return FavoriteProduct the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{favorite_product}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('productId, userId', 'length', 'max' => 20),
            array('create_time, notification', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('productId, userId, create_time', 'safe', 'on' => 'search'),
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
            'productId' => 'Product',
            'userId' => 'User',
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

        $criteria->compare('productId', $this->productId, true);
        $criteria->compare('userId', $this->userId, true);
        $criteria->compare('create_time', $this->create_time, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}