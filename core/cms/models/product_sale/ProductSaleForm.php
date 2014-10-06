<?php

/**
 * This is the model class for Changing User Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.user
 *
 */
class ProductSaleForm extends CFormModel {

    public $name;
    public $model;
    public $tags;
    public $description;
    public $price;
    public $has_discount;
    public $discount_rate;
    public $discount_price;
    public $discount_duration;
    public $min_quantity;
    public $slug;
    public $status;
    public $main_image;
    public $uploadimg;
    public $domain_id;
    public $category_id;
    public $selected_cats;
    public $section_store;

    public function rules() {

        $purifier = new CHtmlPurifier();
        //$purifier->options = array('HTML.Allowed' => '');
        $purifier->options = array('HTML.Allowed' => 'p,strong,ul,li');
        //$this->description = strip_tags($this->description); 

        return array(
            array('name, tags, description, price, domain_id, category_id', 'required'),
            array('name, model', 'length', 'max' => 60),
            //array('selected_cats', 'required', 'message' => t('cms', 'Please select at least one category')),
            array('description', 'filter', 'filter' => array($purifier, 'purify')),
            array('description', 'StringLengthValidator', 'min' => 100, 'max' => 2000),
            //array('description', 'checkLength', 'minChars'=>100, 'maxChars'=>4000),

            array('price', 'numerical', 'min' => 0, 'message' => t('cms', 'The {attribute} is not correct'), 'tooSmall' => 'The {attribute} must be a positive value'),
            array('price', 'length', 'max' => 10, 'tooLong' => 'The {attribute} is not correct'),
            array('main_image, slug, discount_price', 'safe'),
            array('uploadimg', 'file', 'allowEmpty' => true),
            array('tags', 'checkTags'),
            array('tags', 'normalizeTags'),
            //array('tags', 'length', 'max' => 35),
            array('section_store, discount_duration', 'numerical', 'integerOnly' => true),
            array('status', 'numerical', 'integerOnly' => true, 'message' => t('cms', 'Please select the {attribute}')),
            array('has_discount', 'in', 'range' => array('0', '1')),
            array('has_discount, min_quantity, discount_rate', 'YiiConditionalValidator',
                'if' => array(
                    array('has_discount', 'compare', 'compareValue' => 1),
                ),
                'then' => array(
                    array('min_quantity', 'numerical', 'integerOnly' => true, 'min'=>1, 'allowEmpty'=>false),
                    array('discount_rate', 'numerical', 'integerOnly' => true, 'min' => 10, 'max' => 90, 'allowEmpty'=>false)
                ),
            )
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'name' => t('cms', 'Product name'),
            'tags' => t('cms', 'Tags'),
            'description' => t('cms', 'Product description'),
            'price' => t('cms', 'Product price'),
            'has_discount' => t('cms', 'Discount'),
            'status' => t('cms', 'Status'),
            'uploadimg' => t('cms', 'Product image'),
            'selected_cats' => t('cms', 'Selected categories'),
            'section_store' => t('cms', 'section_store'),
            'discount_rate' => t('cms', 'Discount')
        );
    }

//    public function checkLength($attribute, $params) {
//        $length = strlen($this->description);
//        //var_dump($length);
//        if ($length < 100) {
//            $this->addError($attribute, t("cms", "is too short (minimum is 100 characters)"));
//            return false;
//        }
//    }

    /**
     * Normalize The Tags for the Object - Check Valid
     * @param type $attribute
     * @param type $params 
     */
    public function normalizeTags($attribute, $params) {
        $this->tags = Tag::array2string(array_unique(Tag::string2array($this->tags)));
    }

    /**
     * Check Tags Valid
     * @param type $attribute
     * @param type $params 
     */
    public function checkTags($attribute, $params) {
        $result = $this->tags;
        $regex = "/[\^\[\]\$\.\|\?\*\+\(\)\{\}\/\*\%\!\.\'\"\@\#\&\:\<\>\|\-\_\+\=\`\~\;]/";
        if (preg_match($regex, $result))
            $this->addError('tags', t('cms', 'Tags must contain characters only'));
    }

}