<?php

/**
 * This is the model class for Changing FrontendUser Profile.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.models.FrontendUser
 *
 */
class ProductSaleCategoryForm extends CFormModel {

    public $domain_id;
    public $category_id;
    public $subcategory_id;
    public $selected_categories;
  

    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            array('selected_categories', 'required', 'on' => 'save-cat'),
            array('selected_categories', 'countSelected', 'max'=>3, 'on' => 'select-cat'),
            array('domain_id', 'required', 'on' => 'select-cat'),
            array('category_id', 'checkCategory', 'on' => 'select-cat'),
            array('subcategory_id', 'checkSubcategory', 'on' => 'select-cat'),
                // array('selected_categories', 'numerical', 'integerOnly' => true, 'message' => Yii::t('FrontendUser', 'Please select the {attribute}')),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'domain_id' => Yii::t('AdminCompany', 'Select Domain'),
            'category_id' => Yii::t('AdminCompany', 'Select Category'),
            'subcategory_id' => Yii::t('AdminCompany', 'Select Subcategory'),
            'selected_categories' => Yii::t('AdminCompany', 'Selected categories'),
        );
    }

    public function countSelected($attribute, $params) {
         if (!empty($this->selected_categories)) {
             $selected = explode( ',', $this->selected_categories);
             
             if(count($selected)>=$params['max']){
                  $this->addError($attribute, t("cms", "Only {$params['max']} categories are alowed!"));
                  return false;
             }
        }
    }

    public function checkCategory($attribute, $params) {

        if (!empty($this->domain_id)) {
            $category = ProductSaleCategoryList::model()->findByPk($this->domain_id);
            $children = $category->children()->findAll();

            if (!empty($children) && empty($this->category_id)) {
                $this->addError($attribute, t('cms', 'Please select a category'));
                return false;
            }
        }
    }

    public function checkSubcategory($attribute, $params) {

        if (!empty($this->category_id)) {
            $category = ProductSaleCategoryList::model()->findByPk($this->category_id);
            $children = $category->children()->findAll();

            if (!empty($children) && empty($this->subcategory_id)) {
                $this->addError($attribute, t('cms', 'Please select a subcategory'));
                return false;
            }
        }
    }

}