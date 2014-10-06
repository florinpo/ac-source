<?php

/**
 * This is the model class for table "{{membership_info}}".
 *
 * The followings are the available columns in table '{{membership_info}}':
 * @property integer $id
 * @property integer $membership_id
 * @property integer $user_id
 * @property integer $payment_id
 * @property integer $order_date
 * @property integer $end_date
 * @property integer $payment_date
 */
class MembershipInfo extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MembershipInfo the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{membership_info}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('membership_id, user_id', 'required'),
            array('membership_id, user_id, payment_id, order_date, end_date, payment_date', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, membership_id, user_id, payment_id, order_date, end_date, payment_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'membership' => array(self::BELONGS_TO, 'MembershipItem', 'membership_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('MembershipInfo','ID'),
            'membership_id' => Yii::t('MembershipInfo','Membership'),
            'user_id' => Yii::t('MembershipInfo','User'),
            'payment_id' => Yii::t('MembershipInfo','Payment'),
            'order_date' => Yii::t('MembershipInfo','Order Date'),
            'end_date' => Yii::t('MembershipInfo','End Date'),
            'payment_date' => Yii::t('MembershipInfo','Payment Date'),
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
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('payment_id', $this->payment_id);
        $criteria->compare('order_date', $this->order_date);
        $criteria->compare('end_date', $this->end_date);
        $criteria->compare('payment_date', $this->payment_date);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}