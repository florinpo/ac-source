<?php

/**
 * This is the model class for table "{{product_sale_comment}}".
 *
 * The followings are the available columns in table '{{product_sale_comment}}':
 * @property integer $id
 * @property integer $parent_id
 * @property string $comment
 * @property integer $user_id
 * @property integer $product_id
 * @property integer $create_time
 * @property integer $status
 * @property integer $score
 */
class ProductSaleComment extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductSaleComment the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product_sale_comment}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('product_id', 'required'),
            array('parent_id, user_id, product_id, status, score', 'numerical', 'integerOnly' => true),
            array('comment, create_time', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, parent_id, comment, user_id, product_id, create_time, status, score', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'product' => array(self::BELONGS_TO, 'ProductSale', 'product_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id')
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
            'product_id' => 'Product',
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
        $criteria->compare('product_id', $this->product_id);
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

}