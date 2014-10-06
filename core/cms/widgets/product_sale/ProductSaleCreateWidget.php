<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class ProductSaleCreateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $comp_id = isset($_GET['comp_id']) ? (int) ($_GET['comp_id']) : 0;


        if ($comp_id != 0) {
            Yii::import("cms.extensions.xupload.models.XUploadForm");
            $files = new XUploadForm;
            $model = new ProductSaleForm;

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'product-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['ProductSaleForm'])) {
                $model->attributes = $_POST['ProductSaleForm'];

                // validate user input password
                if ($model->validate()) {
                    $product = new ProductSale;
                    $product->companyId = $comp_id;
                    $product->name = ucfirst($model->name);
                    $product->model = ucfirst($model->model);
                    $product->price = $model->price;
                    $product->status = $model->status;
                    $product->currency = $model->currency;
                    $product->description = $model->description;
                    $product->categories =  explode(',', $model->selected_cats);
                    $product->tags = $model->tags;
                    $product->slug = toSlug($model->name);

                    if ($product->save()) {
                        user()->setFlash('success', Yii::t('AdminUser', 'New product has been successfully created.'));
                    }

                    Yii::app()->controller->redirect(array('view', 'comp_id' => $product->companyId, 'id'=>$product->id));
                }
            }

            $this->render('cmswidgets.views.product_sale.product_sale_form_widget', array('model' => $model, 'files'=>$files));
        } else {
            throw new CHttpException(404, t('error', 'The requested page does not exist.'));
        }
    }

}
