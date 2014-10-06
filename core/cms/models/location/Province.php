<?php

/**
 * This is the model class for table "{{province}}".
 *
 * The followings are the available columns in table '{{province}}':
 * @property integer $id
 * @property string $name
 * @property string $regionId
 */
class Province extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Province the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{province}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, regionId', 'required'),
            array('name', 'length', 'max' => 52),
            array('regionId', 'length', 'max' => 3),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, regionId', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
//    public function relations() {
//        // NOTE: you may need to adjust the relation name and the related
//        // class name for the relations automatically generated below.
//         $relations = array(
//            'shops' => array(self::MANY_MANY, 'UserCompanyShop', 'gxc_shop_province(provinceId, shopId)')
//        );
//        return $relations;
//    }
//    
//    public function behaviors() {
//        return array(
//            'CAdvancedArBehavior' => array(
//                'class' => 'cms.extensions.behaviors.CAdvancedArBehavior',
//            )
//        );
//    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'name' => 'Name',
            'regionId' => 'Region',
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('regionId', $this->regionId, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }
    
    /***Dynamic Drop Downs***/

    public static function getRegion() {
        $region = Region::model()->findAll(array('order' => 'name ASC'));
         //$data = array('empty' => t('cms', '--Select region--'));
        $data = array('empty' => t('cms', '--Select region--'));
      
        if ($region && count($region) > 0) {
            foreach ($region as $t) {
                $data[$t->id] = $t->name;
            }
        }
        return $data;
    }

    public static function getProvinceFromRegion($region_id, $render = true) {
        $provinces = Province::model()->findAll(array('order' => 'name ASC', 'condition' => 'regionId = :id', 'params' => array(':id' => $region_id)));
        //$data = array('empty' => t('cms', '--Select province--'));
        $data = array();
        if ($provinces && count($provinces) > 0) {
            $data = CMap::mergeArray($data, CHtml::listData($provinces, 'id', 'name'));
        }
        if ($render) {
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            return $data;
        }
    }

}