<?php

/**
 * This is the model class for table "{{shop_review}}".
 *
 * The followings are the available columns in table '{{shop_review}}':
 * @property integer $id
 * @property integer $parent_id
 * @property string $comment
 * @property integer $user_id
 * @property integer $shop_id
 * @property integer $create_time
 * @property integer $status
 * @property integer $score
 */
class ShopReview extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopReview the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_review}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('comment', 'required'),
            array('user_id, shop_id, status', 'numerical', 'integerOnly' => true),
            array('score, create_time, parent_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, parent_id, comment, user_id, shop_id, create_time, status, score', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'shop' => array(self::BELONGS_TO, 'UserCompanyShop', 'shop_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'rating' => array(self::HAS_ONE, 'ShopReviewRating', 'review_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'parent_id' => 'Parent',
            'comment' => 'Comment',
            'user_id' => 'User',
            'shop_id' => 'Shop',
            'create_time' => 'Create Time',
            'status' => 'Status',
            'score' => 'Score',
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
        $criteria->compare('parent_id', $this->parent_id);
        $criteria->compare('comment', $this->comment, true);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('shop_id', $this->shop_id);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('status', $this->status);
        $criteria->compare('score', $this->score);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
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
     * This is invoked after the record is saved.
     */
    public function afterSave() {
        $this->addNotification();
        parent::afterSave();
    }

    public function addNotification() {
        //if is normal user
        $favUsers = $this->shop->favusers;
        foreach ($favUsers as $user) {
            $notification = new Notification;
            $notification->user_id = $user->user_id;
            $notification->type = ConstantDefine::NOTIFICATION_REVIEW;
            $notification->body = t('site', ':user ha pulicato una recesione a negozio di :company', array(
                ':user' => CHtml::link($this->user->full_name, '#'),
                ':company' => CHtml::link($this->shop->company->cprofile->companyname, '#')
             ));
            $notification->save();
        }
    }

}