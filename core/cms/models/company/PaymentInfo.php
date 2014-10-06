<?php

/**
 * This is the model class for table "{{payment_info}}".
 *
 * The followings are the available columns in table '{{payment_info}}':
 * @property string $id
 * @property string $company_id
 * @property string $product_id
 * @property string $product_type
 * @property string $last_name
 * @property string $first_name
 * @property string $email
 * @property string $company_name
 * @property integer $company_position
 * @property string $vat_code
 * @property string $bank_name
 * @property string $bank_number
 * @property integer $region_id
 * @property integer $province_id
 * @property string $location
 * @property string $adress
 * @property string $postal_code
 * @property string $phone
 * @property string $fax
 * @property string $mobile
 */
class PaymentInfo extends CActiveRecord {
    
   
    public $order_date;
    

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return PaymentInfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{payment_info}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('company_id, product_id, email', 'required'),
            array('company_position, region_id, province_id', 'numerical', 'integerOnly' => true),
            array('company_id, product_id, vat_code', 'length', 'max' => 11),
            array('product_type, bank_name, phone, fax, mobile', 'length', 'max' => 20),
            array('last_name, first_name, company_name, location', 'length', 'max' => 50),
            array('bank_number', 'length', 'max' => 60),
            array('adress', 'length', 'max' => 255),
            array('postal_code', 'length', 'max' => 5),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, company_id, product_id, product_type, last_name, first_name,email, company_name, company_position,
                vat_code, bank_name, bank_number, region_id, province_id, location, adress, postal_code, phone, fax, mobile,
                order_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        $relations = array(
            'company' => array(self::BELONGS_TO, 'User', 'company_id')
        );
       
        return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => t('cms','ID'),
            'company_id' => t('cms','Company id'),
            'product_id' => t('cms','Product'),
            'product_type' => t('cms','Product type'),
            'last_name' => t('cms','Last name'),
            'first_name' =>t('cms','First name'),
            'company_name' => t('cms','Company'),
            'company_position' => t('cms','Role'),
            'vat_code' => t('cms','VAT Code'),
            'bank_name' => t('cms','Bank name'),
            'bank_number' =>t('cms','Bank Code'),
            'region_id' => t('cms','Region'),
            'province_id' =>t('cms','Province'),
            'location' => t('cms','Location'),
            'adress' => t('cms','Adress'),
            'postal_code' => t('cms','Postal Code'),
            'phone' => t('cms','Phone'),
            'fax' => t('cms','Fax'),
            'mobile' => t('cms','Mobile'),
            'order_date' => t('cms','Order date'),
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('company_id', $this->company_id, true);
        $criteria->compare('product_id', $this->product_id, true);
        $criteria->compare('product_type', $this->product_type, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('company_name', $this->company_name, true);
        $criteria->compare('company_position', $this->company_position);
        $criteria->compare('vat_code', $this->vat_code, true);
        $criteria->compare('bank_name', $this->bank_name, true);
        $criteria->compare('bank_number', $this->bank_number, true);
        $criteria->compare('region_id', $this->region_id);
        $criteria->compare('province_id', $this->province_id);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('adress', $this->adress, true);
        $criteria->compare('postal_code', $this->postal_code, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('mobile', $this->mobile, true);
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                   
        ));
  
    }
    protected function afterSave() {
        parent::afterSave();
        if($this->product_type=='premium'){
            $membOrder = new MembershipOrder;
            $membOrder->membership_id = $this->product_id;
            $membOrder->company_id = user()->id;
            $membOrder->payment_id = $this->id;
            $membOrder->order_num = ConstantDefine::ORDER_PREFIX.$this->id;
            $membOrder->order_date = time();
            $membOrder->payment_due = time() + (60 * 60 * 24 * ConstantDefine::PAYMENT_TIME_DUE); // 7 days
            $membOrder->save();
        }
    }
    
    public function getProductName(){
        if($this->product_type == ConstantDefine::PAYMENT_ITEM_PREMIUM) {
            $itemName = MembershipItem::model()->findByPk($this->product_id)->title;
        } else if($this->product_type == ConstantDefine::PAYMENT_ITEM_BANNER) {
            
        }
        return $itemName;
    }
    public function getProductType(){
        if($this->product_type == ConstantDefine::PAYMENT_ITEM_PREMIUM) {
            $itemName = t('cms', 'Premium');
        } else if($this->product_type == ConstantDefine::PAYMENT_ITEM_BANNER) {
            $itemName = t('cms', 'Banner');
        }
        return $itemName;
    }
    
    private function getOrderDate(){
        if($this->product_type == ConstantDefine::PAYMENT_ITEM_PREMIUM) {
            $mItem = MembershipOrder::model()->find(array(
                'condition'=>'membership_id=:membershipId',
                'params'=>array(':membershipId'=>$this->product_id)));
            return $mItem->order_date;
        }
    }
}