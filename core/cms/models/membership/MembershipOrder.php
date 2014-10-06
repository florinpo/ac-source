<?php

/**
 * This is the model class for table "{{membership_order}}".
 *
 * The followings are the available columns in table '{{membership_order}}':
 * @property integer $id
 * @property integer $membership_id
 * @property integer $company_id
 * @property integer $payment_id
 * @property integer $order_date
 * @property integer $end_date
 * @property integer $payment_date
 * @property integer $payment_due
 * @property integer $status
 */
class MembershipOrder extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MembershipOrder the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{membership_order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('membership_id, company_id, payment_id, order_date, order_num', 'required'),
            array('membership_id, company_id, payment_id, order_date, payment_due, status', 'numerical', 'integerOnly' => true),
            array('payment_date, end_date, invoice_num', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, membership_id, company_id, payment_id, order_num, order_date, end_date, payment_date, payment_due, status, invoice_num', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        $relations = array(
            'user' => array(self::BELONGS_TO, 'User', 'company_id'),
            'payment' => array(self::BELONGS_TO, 'PaymentInfo', 'payment_id'),
            'product' => array(self::BELONGS_TO, 'MembershipItem', 'membership_id'),
        );

        return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'order_num' => t('cms', 'Nr. Invoice'),
            'membership_id' => t('cms', 'Membership'),
            'company_id' => t('cms', 'Company'),
            'payment_id' => t('cms', 'Payment'),
            'order_date' => t('cms', 'Order Date'),
            'end_date' => t('cms', 'End Date'),
            'payment_date' => t('cms', 'Payment Date'),
            'status' => t('cms', 'Status'),
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
        $criteria->compare('membership_id', $this->membership_id);
        $criteria->compare('company_id', $this->company_id);
        $criteria->compare('payment_id', $this->payment_id);
        $criteria->compare('order_num', $this->order_num);
        $criteria->compare('order_date', $this->order_date);
        $criteria->compare('end_date', $this->end_date);
        $criteria->compare('payment_date', $this->payment_date);
        $criteria->compare('payment_due', $this->payment_due);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function afterDelete() {
        parent::afterDelete();
        PaymentInfo::model()->deleteByPk($this->payment_id);
    }
    
    public function getStatus() {
        if ($this->status == ConstantDefine::ORDER_STATUS_PAID) {
            $output = t('cms', 'Paid');
        } else if ($this->status == ConstantDefine::ORDER_STATUS_UNPAID) {
            $output = t('cms', 'Unpaid');
        } else if ($this->status == ConstantDefine::ORDER_STATUS_EXPIRED) {
            $output = t('cms', 'Expired');
        }
        return $output;
    }
    
    public function confirmPayment() {
        $this->status = ConstantDefine::ORDER_STATUS_PAID;
        $this->payment_date = time();
        $this->end_date = $this->calculateEndDate($this->payment_date);
        if ($this->save(false, array('payment_date', 'end_date', 'status'))) {
            $this->user->has_membership = 1;
            $this->user->save(false, array('has_membership'));
            return true;
            //here we will send the message to the user email to confirm the payment
        } else
            return false;
    }
    
    public function calculateEndDate($startDate){
        if($this->product->duration_type==ConstantDefine::MEM_DURATION_YEARS){
            $endDate = strtotime('+'.$this->product->duration.' year',$startDate);
        } else if($this->product->duration_type==ConstantDefine::MEM_DURATION_DAYS){
            $endDate = $startDate + ($this->product->duration * 86400); // duration * 1 day
        }
        return $endDate;
    }
}