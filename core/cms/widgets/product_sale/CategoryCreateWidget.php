<?php

/**
 * This is the Widget for Creating new Term
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package  cmswidgets.object
 *
 *
 */
class CategoryCreateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $model = new ProductSaleCategoryList;
        $guid = isset($_GET['guid']) ? strtolower(trim($_GET['guid'])) : '';

        $lang_exclude = array();

        //List of translated versions
        $versions = array();

        if ($guid != '') {
            $taxonomy_object = ProductSaleCategoryList::model()->with('language')->findAll('guid=:gid', array(':gid' => $guid));
            if (count($taxonomy_object) > 0) {
                foreach ($taxonomy_object as $obj) {
                    $lang_exclude[] = $obj->lang;
                    $versions[] = $obj->name . ' - ' . $obj->language->lang_desc;
                }
            }
            $model->guid = $guid;
        }

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['ProductSaleCategoryList'])) {
            $model->attributes = $_POST['ProductSaleCategoryList'];
            if ($model->validate()) {
                if (isset($_POST['parent_id']) && $_POST['parent_id'] !== 'empty') {
                    $parent = GxcHelpers::loadDetailModel('ProductSaleCategoryList', $_POST['parent_id']);
                    if ($model->appendTo($parent, false)) {
                        user()->setFlash('success', Yii::t('AdminTerm', 'New category has been successfully created'));
                        Yii::app()->controller->redirect(array('createcategory'));
                    }
                } else {
                    // if model is new root
                    if ($model->saveNode(false)) {
                        user()->setFlash('success', Yii::t('AdminTerm', 'New category has been successfully created'));
                        Yii::app()->controller->redirect(array('createcategory'));
                    }
                }
            }
        }


        $this->render('cmswidgets.views.product_sale.category_form_widget', array('model' => $model, 'lang_exclude' => $lang_exclude, 'versions' => $versions));
    }

}
