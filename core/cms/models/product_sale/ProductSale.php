<?php

class ProductSale extends CActiveRecord {

    public $companyname_sort;
    public $categoryIds;
    //The old Tags
    public $_oldTags;

    const MAX_SELECTED = 4; // max number for main page products

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductSale the static model class
     */

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product_sale}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('shop_id, section_store, domain_id, lang, status, min_quantity, ', 'numerical', 'integerOnly' => true),
            array('name', 'length', 'max' => 60),
            array('model', 'length', 'max' => 60),
            array('tags', 'length', 'max' => 40),
            array('price, discount_price', 'length', 'max' => 10),
            array('description, main_image, slug, create_time, update_time, expire_time, currency, categoryIds, visible_home', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, shop_id, domain_id, companyname_sort, name, slug, description, price, lang, create_time, update_time, status, main_image', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'pimages' => array(self::HAS_MANY, 'ProductSaleImage', 'productId'),
            'imagescount' => array(self::STAT, 'ProductSaleImage', 'productId'),
            'shop' => array(self::BELONGS_TO, 'UserCompanyShop', 'shop_id'),
            //'company' => array(self::HAS_ONE, 'User', array('companyId' => 'user_id'), 'through' => 'shop'),
            //'cprofile' => array(self::HAS_ONE, 'UserCompanyProfile', array('user_id' => 'companyId'), 'through' => 'company'),
            'section' => array(self::BELONGS_TO, 'ProductSaleSection', 'section_store'),
            'tags' => array(self::MANY_MANY, 'ProductSaleTag', 'gxc_producttag_sale(productId, tagId)'),
            'categories' => array(self::MANY_MANY, 'ProductSaleCategoryList', 'gxc_product_sale_category(product_id, category_id)'),
            'favusers' => array(self::MANY_MANY, 'User', 'gxc_favorite_product(productId, userId)')
        );
    }

    public function behaviors() {
        return array(
            'CAdvancedArBehavior' => array(
                'class' => 'cms.extensions.behaviors.CAdvancedArBehavior',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => t('cms', 'ID'),
            'shop_id' => t('cms', 'Shop'),
            'name' => t('cms', 'Name'),
            'slug' => t('cms', 'Slug'),
            'description' => t('cms', 'Description'),
            'price' => t('cms', 'Price'),
            'discount_price' => t('cms', 'Promotional Price'),
            'lang' => t('cms', 'Language'),
            'status' => t('cms', 'Status'),
            'main_image' => t('cms', 'Image'),
            'companyname_sort' => t('cms', 'Company'),
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

        if (isset($_GET['comp_id'])) {
            $criteria->condition = 'shop_id=' . $_GET['shop_id'];
        } else {
            $criteria->condition = '';
        }
        $criteria->with = array('shop');
        $criteria->together = true;

        $criteria->compare('id', $this->id);
        $criteria->compare('shop_id', $this->shop_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('price', $this->price, true);
        $criteria->compare('discount_price', $this->discount_price, true);
        $criteria->compare('lang', $this->lang);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('update_time', $this->expire_time);
        $criteria->compare('status', $this->status);

        $sort = new CSort;
        $sort->attributes = array(
            'id',
            'status',
            'name',
//            'companyname_sort' => array(
//                'asc' => 'company.display_name',
//                'desc' => 'company.display_name DESC',
//            ),
        );
        $sort->defaultOrder = 't.id DESC';
        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    public function belongsTo($userid) {
        return ($this->shop->companyId == $userid);
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {

            // if we dont have any image then the default main_image will be 0
            if ($this->imagescount == 0) {
                $this->main_image = 0;
            }

            if ($this->isNewRecord) {
                $this->create_time = $this->update_time = time();
            } else {
                $this->update_time = time();
            }
            return true;
        }
        else
            return false;
    }

    protected function afterSave() {

        $this->addImages();
//        if (!empty($this->categories)) {
//            $this->lang = $this->categories[0]['lang'];
//        }

        self::extraAfterSave($this);
        parent::afterSave();
    }

    //After Save excucte update Tag Relationship
    public static function extraAfterSave($model) {
        //Check the scenairo if tags updated needed
        if (($model->isNewRecord) || ($model->scenario = 'updateWithTags'))
            self::UpdateTagRelationship($model);
        return;
    }

    /**
     * Update Tag Relationship of the model
     * @param type $model
     */
    public static function UpdateTagRelationship($model) {

        ProductSaleTag::model()->updateFrequency($model->_oldTags, $model->tags);

        //Start to DElete All the Tag Relationship
        ProductsaleTagRelation::model()->deleteAll('productId = :id', array(':id' => $model->id));

        //Start to re Insert
        $explode = explode(',', trim($model->tags));

        foreach ($explode as $ex) {
            $tag = ProductSaleTag::model()->find('slug = :s', array(':s' => ProductSaleTag::model()->stripVietnamese(strtolower($ex))));
            if ($tag) {
                $tag_relationship = new ProductsaleTagRelation;
                $tag_relationship->tagId = $tag->id;
                $tag_relationship->productId = $model->id;
                $tag_relationship->save();
            }
        }
    }

    /**
     * Get Tags of the Object
     * @param type $object_id
     * @return type 
     */
    public static function getTags($product_id) {
        $req = Yii::app()->db->createCommand(
                "SELECT t.name FROM gxc_product_sale_tag t,  gxc_productsale_tag_relation r, gxc_product_sale o
                 WHERE t.id = r.tagId AND r.productId = o.id AND o.id = " . $product_id
        );
        $tags_name = $req->queryAll();
        $result = array();
        if ($tags_name != null) {
            foreach ($tags_name as $tag_name) {
                $result[] = $tag_name['name'];
            }
        }
        return $result;
    }

    protected function afterDelete() {
        parent::afterDelete();
        ProductsaleTagRelation::model()->deleteAll('productId = :pid', array(':pid' => $this->id));
        ProductSaleCategory::model()->deleteAll('productId = :pid', array(':pid' => $this->id));
        ProductSaleImage::model()->deleteAll('productId = :pid', array(':pid' => $this->id));
    }

    public function afterFind() {
        if (!empty($this->categories)) {
            foreach ($this->categories as $n => $category)
                $this->categoryIds[] = $category->id;
        }

        $this->_oldTags = $this->tags;

        parent::afterFind();
    }

    public function addImages() {
        //If we have pending images
        if (Yii::app()->user->hasState('imagesProduct')) {
            $userImages = Yii::app()->user->getState('imagesProduct');
            $folder = 'sale';
            $pname = $this->slug;
            //Now lets create the corresponding models and move the files

            foreach ($userImages as $image) {
                $unique_key = substr(md5(rand(0, 1000000)), 0, 8);
                $filename = $pname . '_' . gen_uuid() . '_' . $unique_key;

                if (is_file($image["path"])) {
                    $img = new ProductSaleImage();
                    $img->name = $filename . '.' . strtolower($image["extension"]);
                    $img->path = $folder . '/' . $filename . '.' . strtolower($image["extension"]);
                    $img->size = $image["size"];
                    $img->type = $image["mime"];
                    $img->extension = $image["extension"];
                    $img->shopId = $this->shop->id;
                    $img->productId = $this->id;
                    $img->created_date = $img->update_date = time();
                    if (!$img->save()) {
                        //Its always good to log something
                        Yii::log("Could not save Image:\n" . CVarDumper::dumpAsString(
                                        $img->getErrors()), CLogger::LEVEL_ERROR);
                        //this exception will rollback the transaction
                        throw new Exception('Could not save Image');
                    }

                    @unlink($image["path"]);
                }

                $sizes = ImageSize::getProductSizes();

                foreach ($sizes as $size) {

                    if (is_file($image[$size['size']])) {

                        $path = IMAGES_FOLDER . DIRECTORY_SEPARATOR . $size['id'] . DIRECTORY_SEPARATOR . $folder;


                        if (!(file_exists($path) && ($path))) {
                            mkdir($path, 0777, true);
                        }

                        if (!(file_exists($path . DIRECTORY_SEPARATOR . 'index.html'))) {
                            $fp = fopen($path . DIRECTORY_SEPARATOR . 'index.html', 'w'); // open in write mode.
                            fclose($fp); // close the file.
                        }

                        if (rename($image[$size['size']], $path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]))) {
                            chmod($path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]), 0777);
                        }
                    }
                }
            }
            Yii::app()->user->setState('imagesProduct', null);
        }
    }

    public static function convertProductState($data) {
        if ($data->status == ConstantDefine::PRODUCT_STATUS_ACTIVE) {
            $image = 'active';
        } else if ($data->status == ConstantDefine::PRODUCT_STATUS_PENDING) {
            $image = 'inactive';
        }
        return bu() . '/images/' . $image . '.png';
    }

    public function getStatusString() {

        if ($this->status == ConstantDefine::PRODUCT_STATUS_ACTIVE) {
            $string = t('site', 'Active');
        } else if ($this->status == ConstantDefine::PRODUCT_STATUS_PENDING) {
            $string = t('site', 'Pending');
        } else if ($this->status == ConstantDefine::PRODUCT_STATUS_REDIT) {
            $string = t('site', 'Edit requested');
        } else if ($this->status == ConstantDefine::PRODUCT_STATUS_REJECTED) {
            $string = t('site', 'Rejected');
        }

        return $string;
    }

    public function getStringCategories() {

        $data = array();

        foreach ($this->categories as $category) {

            $data[] = ProductSaleCategoryList::model()->getCategoryParents($category->id, true);
        }

        if (count($data) > 0)
            return implode("<br />", $data);
    }

    public static function getSelectedCategories($id) {
        $data = array();
        $current_product = ProductSale::model()->findByPk($id);
        $selected_categories = $current_product->categories;
        //$data = CHtml::listData($selected_categories, 'id', 'name');
        foreach ($selected_categories as $category)
        //$data[$category->id] = ProductSaleCategoryList::model()->getCategoryParents($category->id, true);
            $data[] = array('id' => $category->id, 'label' => ProductSaleCategoryList::model()->getCategoryParents($category->id, true));

        return $data;
    }
    

    public static function countCompProducts($compId) {
        if (isset($compId)) {
            $products = ProductSale::model()->findAll(array('condition' => 'shop_id=:shopId', 'params' => array(':shopId' => $shopId)));
            $countProducts = count($products);
            return $countProducts;
        }
    }

    /*
     * * function to generate drop down sections in cgridview
     */

    public function getSections($id) {
        $sections = ProductSaleSection::model()->findAll(array('condition' => 'shopId=:shopId', 'params' => array(':shopId' => $this->shop->id)));

        $data = array('empty' => '');

        if ($sections && count($sections) > 0) {
            $data = CMap::mergeArray($data, CHtml::listData($sections, 'id', 'name'));
        }
        return CHtml::dropDownList('section[' . $id . ']', $this->section, $data);
    }

    /*
     * * function to generate visible home buttons
     */

    public function getVbuttons($id, $visible) {

        $checked = ($this->visible_home == 1) ? "checked='checked'" : "";

        $output = "<input type='checkbox' id='ic-" . $id . "' class='icheck' " . $checked . " />";


        return $output;
    }

    /*
     * * function used for drop down form
     */

    public static function getSectionsForm($shopId) {

        $sections = ProductSaleSection::model()->findAll(array('condition' => 'shopId=:shopId', 'params' => array(':shopId' => $shopId)));

        $data = array('0' => Yii::t('CompanyStore', '-Select Section-'));

        if ($sections && count($sections) > 0) {
            $data = CMap::mergeArray($data, CHtml::listData($sections, 'id', 'name'));
        }
        return $data;
    }

    /*
     * * function to check if current product has main image selected
     */

    public function selectedImage($size) {

        $layout_asset = GxcHelpers::publishAsset(Yii::getPathOfAlias('common.layouts.default.assets'));
        if (empty($this->imagescount)) {
            return CHtml::image($layout_asset . "/images/entries/products/empty-" . $size . ".png", "", array());
        } else {
            if ($this->main_image != 0 && $this->main_image != null) {
                $mainImgId = $this->main_image;
                $image = ProductSaleImage::model()->findByPk($mainImgId);
                if ($image) {
                    $path = $image->path;
                    if ($path && file_exists(IMAGES_FOLDER . "/img" . $size . "/" . $path)) {
                        return CHtml::image(IMAGES_URL . "/img" . $size . "/" . $path, "", array());
                    } else {
                        return CHtml::image($layout_asset . "/images/entries/products/empty-" . $size . ".png", "", array());
                    }
                } else {
                    return CHtml::image($layout_asset . "/images/entries/products/empty-" . $size . ".png", "", array());
                }
            } else {
                $imagesKeys = array_keys($this->pimages);
                $mainImg = $this->pimages[$imagesKeys[0]];
                $path = $mainImg->path;
                if ($path && file_exists(IMAGES_FOLDER . "/img" . $size . "/" . $path)) {
                    return CHtml::image(IMAGES_URL . "/img" . $size . "/" . $path, "", array());
                } else {
                    return CHtml::image($layout_asset . "/images/entries/products/empty-" . $size . ".png", "", array());
                }
            }
        }
    }

    public function selectedImageObj() {
        if (empty($this->imagescount)) {
            return null;
        } else {
            if ($this->main_image != 0 && $this->main_image != null) {
                $main_image = ProductSaleImage::model()->findByPk($this->main_image);
                return $main_image;
            } else {
                $imagesKeys = array_keys($this->pimages);
                return $this->pimages[$imagesKeys[0]];
            }
        }
    }

    public function getListImages($size) {
        $list = "";
        if (!empty($this->imagescount)) {
            $list .= "<ul class='items-" . $size . "'>";
            foreach ($this->pimages as $image) {
                if ($image->path != null && $image->path != '') {
                    $list .= "<li style='float:left; padding:5px; width:" . $size . "'>";
                    $list .= CHtml::image(IMAGES_URL . "/img" . $size . "/" . $image->path, "", array());
                    $list .= "</li>";
                }
            }
            $list .="</ul>";
        }

        return $list;
    }

}