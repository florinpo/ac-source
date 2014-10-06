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
 * @property integer $duration_type
 * @property integer $items_num
 */
class MembershipItem extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MembershipItem the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{membership_item}}';
    }

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
            array('title, rolename, duration, duration_type, price, items_num', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'roles' => array(self::BELONGS_TO, 'AuthItem', 'rolename', 'on' => 't.rolename=roles.name'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => t('cms', 'ID'),
            'title' => t('cms', 'Title'),
            'description' => t('cms', 'Description'),
            'rolename' => t('cms', 'Role'),
            'price' => t('cms', 'Price'),
            'duration' => t('cms', 'Duration'),
            'items_num' => t('cms', 'Number of items'),
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('rolename', $this->rolename, true);
        $criteria->compare('price', $this->price);
        $criteria->compare('duration', $this->duration);
        $criteria->compare('items_num', $this->items_num);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    //$options = CHtml::listData(MembershipItem::model()->findAll(), 'id', 'title');

    public static function getMembershipOptions() {
        $option = MembershipItem::model()->findAll();
        $data = array('0' => t('cms', 'None'));
        if ($option && count($option) > 0) {
            foreach ($option as $t) {
                $data[$t->id] = $t->title;
            }
        }
        return $data;
    }

    public function getDuration() {
        if (!empty($this->duration)) {
            if ($this->duration_type == ConstantDefine::MEM_DURATION_YEARS) {
                return $this->duration . t('cms', ' year(s)');
            } else {
                return $this->duration . t('cms', ' day(s)');
            }
        }
    }
    
    

}