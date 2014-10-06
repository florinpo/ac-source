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
class MembershipActivateForm extends CFormModel {
    
    public $order_id;
    public $invoice_num;

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('order_id, invoice_num', 'required')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'order_id' => t('cms','Order'),
            'invoice_num' =>  t('cms','Invoice number'),
        );
    }

}