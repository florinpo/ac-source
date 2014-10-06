<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class ProductCategorySelectWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $product_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $model = new ProductSaleCategoryForm;

        if (isset($_POST['save-cat'])) {
            $model->setScenario('save-cat');
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {

                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        } else if (isset($_POST['select-cat'])) {
            $model->setScenario('select-cat');
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {

                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }


        if ($product_id !=0)
            $product = ProductSale::model()->findByPk($product_id);
        else
            $product = null;


        $this->render('cmswidgets.views.category_select.product_category', array('model' => $model, 'product' => $product));
    }

}
