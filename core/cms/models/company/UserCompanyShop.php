<?php

/**
 * This is the model class for table "{{user_company_shop}}".
 *
 * The followings are the available columns in table '{{user_company_shop}}':
 * @property string $id
 * @property string $companyId
 * @property integer $storeId
 * @property string $website
 * @property string $description
 * @property string $services
 * @property string $certificate
 * @property string $logo
 * @property integer $shipping_available
 * @property string $shipping_option
 * @property string $shipping_description
 * @property integer $delivery_type
 */
class UserCompanyShop extends CActiveRecord {

    //public $shipoptsIds;
    public $provinceIds;
    public $categoryIds;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserCompanyShop the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_company_shop}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('companyId', 'required'),
            array('storeId, shipping_available, delivery_type', 'numerical', 'integerOnly' => true),
            array('companyId', 'length', 'max' => 11),
            array('website', 'length', 'max' => 512),
            array('services, certificate, logo', 'length', 'max' => 255),
            array('description, provinceIds, categoryIds, shipping_description', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, companyId, storeId, website, description, services, certificate, logo, shipping_available,  shipping_description, delivery_type', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        $relations = array(
            'products' => array(self::HAS_MANY, 'ProductSale', 'shopId'),
            'company' => array(self::BELONGS_TO, 'User', 'companyId'),
            'ship_options' => array(self::HAS_MANY, 'ShopShipping', 'shopId'),
            'provinces' => array(self::MANY_MANY, 'Province', 'gxc_shop_province(shopId, provinceId)'),
            'categories' => array(self::MANY_MANY, 'CompanyCats', 'gxc_shop_category(shopId, categoryId)'),
            'sections' => array(self::HAS_MANY, 'ProductSaleSection', 'shopId'),
            'favusers' => array(self::MANY_MANY, 'User', 'gxc_favorite_shop(shopId, userId)'),
            'reviews' => array(self::HAS_MANY, 'ShopReview', 'shop_id'),
            'countReviews' => array(self::STAT, 'ShopReview', 'shop_id', 'condition' => 't.status=1'),
            'countRating1' => array(self::STAT, 'ShopReview', 'shop_id',
                'join' => 'LEFT JOIN gxc_shop_review_rating AS rating ON t.id=rating.review_id',
                'condition' => 't.status=1 AND rating.rate=1'),
            'countRating2' => array(self::STAT, 'ShopReview', 'shop_id',
                'join' => 'LEFT JOIN gxc_shop_review_rating AS rating ON t.id=rating.review_id',
                'condition' => 't.status=1 AND rating.rate=2'),
            'countRating3' => array(self::STAT, 'ShopReview', 'shop_id',
                'join' => 'LEFT JOIN gxc_shop_review_rating AS rating ON t.id=rating.review_id',
                'condition' => 't.status=1 AND rating.rate=3'),
            'countRating4' => array(self::STAT, 'ShopReview', 'shop_id',
                'join' => 'LEFT JOIN gxc_shop_review_rating AS rating ON t.id=rating.review_id',
                'condition' => 't.status=1 AND rating.rate=4'),
            'countRating5' => array(self::STAT, 'ShopReview', 'shop_id',
                'join' => 'LEFT JOIN gxc_shop_review_rating AS rating ON t.id=rating.review_id',
                'condition' => 't.status=1 AND rating.rate=5')
        );
        return $relations;
    }

    public function behaviors() {
        return array(
            'CAdvancedArBehavior' => array(
                'class' => 'cms.extensions.behaviors.CAdvancedArBehavior',
            )
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'companyId' => 'Company',
            'storeId' => 'Store',
            'website' => 'Website',
            'description' => 'Description',
            'services' => 'Services',
            'certificate' => 'Certificate',
            'logo' => 'Logo',
            'shipping_available' => 'Shipping Available',
            'provinceIds' => 'Provinces',
            'shipping_description' => 'Shipping Description',
            'delivery_type' => 'Delivery Type'
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
        $criteria->compare('companyId', $this->companyId, true);
        $criteria->compare('storeId', $this->storeId);
        $criteria->compare('website', $this->website, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('services', $this->services, true);
        $criteria->compare('certificate', $this->certificate, true);
        $criteria->compare('logo', $this->logo, true);
        $criteria->compare('shipping_available', $this->shipping_available);
        $criteria->compare('shipping_description', $this->shipping_description, true);
        $criteria->compare('delivery_type', $this->delivery_type);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function afterFind() {
        if (!empty($this->provinces)) {
            foreach ($this->provinces as $n => $province)
                $this->provinceIds[] = $province->id;
        }
        if (!empty($this->categories)) {
            foreach ($this->categories as $n => $category)
                $this->categoryIds[] = $category->id;
        }
        parent::afterFind();
    }

    public function getSelectedProvinces($render = true) {
        $data = array();
        if ($this->provinces && count($this->provinces) > 0) {
            $data = CHtml::listData($this->provinces, 'id', 'name');
            foreach ($this->provinces as $province) {
                $data[$province->id] = $province->name;
            }
        } else {
            $data = array();
        }
        if ($render) {
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {

            return $data;
        }
    }

    protected function afterSave() {
        parent::afterSave();
    }

    protected function afterDelete() {
        parent::afterDelete();
        if ($this->logo != null && $this->logo != '') {
            $old_path = $this->logo;
            //Delete old file Sizes
            $sizes = ImageSize::getSizes();
            foreach ($sizes as $size) {
                if (file_exists(IMAGES_FOLDER . '/' . $size['id'] . '/' . $old_path))
                    @unlink(IMAGES_FOLDER . '/' . $size['id'] . '/' . $old_path);
            }
        }
    }

    public function getStringCategories() {
        $data = array();
        foreach ($this->categories as $category) {
            $data[] = CompanyCats::model()->getCategoryParents($category->id, true);
        }
        if (count($data) > 0)
            return implode("<br />", $data);
    }

    public function getSelectedCategories($render = true) {
        $data = array();
        if ($this->categories && count($this->categories) > 0) {
            $data = CHtml::listData($this->categories, 'id', 'name');
            foreach ($this->categories as $category) {
                $data[$category->id] = CompanyCats::model()->getCategoryParents($category->id, true);
            }
        } else {
            $data = array();
        }
        if ($render) {
            foreach ($data as $value => $name) {
                $n = CompanyCats::model()->getCategoryParents($value, true);
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($n), true);
            }
        } else {

            return $data;
        }
    }

    public function selectedImage($size, $loc = '') {
        $path = $this->logo;
        $layout_asset = GxcHelpers::publishAsset(Yii::getPathOfAlias('common.layouts.default.assets'));
        if (empty($path)) {
            if ($loc == 'frontend') {
                if ($this->company->has_membership == 1)
                    return CHtml::image($layout_asset . "/images/entries/shops/" . $size . "/empty-premium.png", $this->company->cprofile->companyname);
                else
                    return CHtml::image($layout_asset . "/images/entries/shops/" . $size . "/empty-free.png", $this->company->cprofile->companyname);
            }
            else {
                return CHtml::image($layout_asset . "/images/entries/shops/" . $size . "/empty.png", $this->company->cprofile->companyname);
            }
        } else {
            if ($path && file_exists(IMAGES_FOLDER . "/img" . $size . "/" . $path)) {
                return CHtml::image(IMAGES_URL . "/img" . $size . "/" . $path, $this->company->cprofile->companyname);
            } else {
                return CHtml::image($layout_asset . "/images/entries/shops/" . $size . "/empty.png", $this->company->cprofile->companyname);
            }
        }
    }

    public function getServiceLocations() {
        if (count($this->provinces) > 0) {
            if (count($this->provinces) > 1) {
                $provinces = array();
                foreach ($this->provinces as $province) {
                    $provinces[] = $province->name;
                }
                return implode(', ', $provinces);
            } if (count($this->provinces) == 1) {
                return $this->provinces[0]->name;
            }
        } else {
            return t('site', 'Tutta Italia');
        }
    }

    public function getSelectedProducts() {
        $limit = 4;
        $products = ProductSale::model()->findAll(array(
            'condition' => 'visible_home=1 AND status=1 AND shop_id=:shopId',
            'params' => array(':shopId' => $this->id)));
        $products_count = count($products);
        if ($products_count < $limit) {
            $new_limit = $limit - $products_count;
            $products_unselected = ProductSale::model()->findAll(array(
                'condition' => 'visible_home=0 AND status=1 AND shop_id=:shopId',
                'params' => array(':shopId' => $this->id),
                'order' => 'create_time ASC',
                'limit' => $new_limit));
            $selected_products = array_merge($products, $products_unselected);
            return $selected_products;
        } else if ($products_count == $limit) {
            return $products;
        } else {
            return null;
        }
    }

    public function getAverageRating() {
        if ($this->countReviews > 0) {
            $averageRating = ($this->countRating1 * 1 + $this->countRating2 * 2 + $this->countRating3 * 3
                    + $this->countRating4 * 4 + $this->countRating5 * 5) / $this->countReviews;
        } else {
            $averageRating = 0;
        }
        return $averageRating;
    }

}