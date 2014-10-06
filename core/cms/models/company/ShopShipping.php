<?php

/**
 * This is the model class for table "{{shop_shipping}}".
 *
 * The followings are the available columns in table '{{shop_shipping}}':
 * @property integer $optionId
 * @property integer $shopId
 */
class ShopShipping extends CActiveRecord {
    

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopShipping the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_shipping}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('shopId, optionId', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('optionId, shopId', 'safe', 'on' => 'search'),
        );
    }



    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'optionId' => 'Option',
            'shopId' => 'Shop',
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

        $criteria->compare('optionId', $this->optionId);
        $criteria->compare('shopId', $this->shopId);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}