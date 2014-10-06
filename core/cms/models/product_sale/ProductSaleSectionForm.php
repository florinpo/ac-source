<?php

/**
 * This is the model class for table "{{product_sale_section}}".
 *
 * The followings are the available columns in table '{{product_sale_section}}':
 * @property integer $id
 * @property integer $shopId
 * @property string $name
 * @property string $slug
 * @property integer $position
 */
class ProductSaleSectionForm extends CFormModel {

    public $name;
    public $shopId;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, shopId', 'required'),
            array('name', 'length', 'max' => 26, 'min'=>3),
            array('shopId', 'numerical', 'integerOnly' => true),
           
        );
    }

   
    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'shopId' => Yii::t('ProductSaleSection', 'Company'),
            'name' => Yii::t('ProductSaleSection', 'Name'),
        );
    }
}