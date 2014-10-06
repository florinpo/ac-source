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
class ProductSaleSection extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductSaleSection the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product_sale_section}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, shopId', 'required'),
            array('shopId, position', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 26, 'min'=>3),
            array('slug', 'length', 'max' => 120),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, shopId, name, slug, position', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'shop' => array(self::BELONGS_TO, 'UserCompanyShop', 'shopId'),
            'product' => array(self::HAS_MANY, 'ProductSale', 'sectionId'),
            'count' => array(self::STAT, 'ProductSale', 'sectionId'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'shopId' => 'Shop',
            'name' => 'Name',
            'slug' => 'Slug',
            'position' => 'Position',
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
        $criteria->compare('shopId', $this->shopId);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('position', $this->position);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            $this->slug = toSlug($this->name);
            if ($this->isNewRecord) {
                // If this is the new Menu Item, we will find the Max Value of Order of 

                $this->position =
                        $command = $this->dbConnection
                        ->createCommand("SELECT MAX(`position`)+1 FROM {{product_sale_section}} where shopId = :shopId")
                        ->bindValue(':shopId', $this->shop->id, PDO::PARAM_STR)
                        ->queryScalar();
            }
            return true;
        }
        else
            return false;
    }

    protected function afterDelete() {
        parent::afterDelete();
        
        $product = ProductSale::model()->find(array('condition'=>'sectionId=:sectionId', 'params'=>array(':sectionId'=>$this->id)));
        $product->sectionId = 0;
        $product->save(false);

        // to reorder sections if we delete one
        $sections = ProductSaleSection::model()->findAll(array('condition' => 'shopId=:shopId', 'order' => 'position ASC', 'params' => array(':shopId' => user()->id)));
        if (count($sections) > 0) {
            $k = 1;
            foreach ($sections as $section) {
                $section->position = $k;
                $section->save(false);
                $k++;
            }
        }
    }

}