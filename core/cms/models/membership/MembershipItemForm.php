<?php

/**
 * This is the model class for table "{{membership_item}}".
 *
 * The followings are the available columns in table '{{membership_item}}':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property string $rolename
 * @property double $price
 * @property integer $duration
 */
class MembershipItemForm extends CFormModel {

    public $title;
    public $description;
    public $rolename;
    public $duration;
    public $price;
    public $duration_type;
    public $items_num;
    

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title, rolename', 'required'),
            array('duration, items_num', 'numerical', 'integerOnly' => true),
            array('price', 'numerical'),
            array('price', 'length', 'max' => 10),
            array('duration', 'length', 'max' => 4),
            array('title, description', 'length', 'max' => 255),
            array('rolename', 'length', 'max' => 64),
            array('duration_type', 'in', 'range' => array('0', '1')),
            
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'title' => Yii::t('cms','Title'),
            'description' => Yii::t('cms','Description'),
            'rolename' => Yii::t('cms','Role'),
            'price' => Yii::t('cms','Price'),
            'duration' => Yii::t('cms','How long membership will be'),
            'items_num' => Yii::t('cms','Number of items'),
        );
    }

}