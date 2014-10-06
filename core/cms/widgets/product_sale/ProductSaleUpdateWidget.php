<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class ProductSaleUpdateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $id = isset($_GET['id']) ? (int) ($_GET['id']) : 0;

        Yii::import("cms.extensions.xupload.models.XUploadForm");
        $files = new XUploadForm;
        $model = new ProductSaleForm;
        if ($id != 0) {
            $product = GxcHelpers::loadDetailModel('ProductSale', $id);
            if ($product) {
                $model->name = $product->name;
                $model->model = $product->model;
                $model->price = $product->price;
                $model->status = $product->status;
                $model->currency = $product->currency;
                $model->description = $product->description;
                $model->selected_cats = implode(',', $product->categoryIds);
                $model->tags = $product->_oldTags;
            } else {
                throw new CHttpException('503', Yii::t('error', 'ProductSale is not valid'));
            }

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'product-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            if (isset($_POST['ProductSaleForm'])) {
                $model->attributes = $_POST['ProductSaleForm'];

                if ($model->validate()) {
                    $product->isNewRecord = false;
                    $product->scenario = 'updateWithTags';
                    $product->tags = $model->tags;
                    $product->name = ucfirst($model->name);
                    $product->price = $model->price;
                    $product->status = $model->status;
                    $product->currency = $model->currency;
                    $product->description = $model->description;
                    $product->categories = explode(',', $model->selected_cats);
                    $product->slug = toSlug($model->name);

                    //Yii:: app () ->cache->flush() 

                    

                    if ($product->save()) {
//                        $key = 'products_r_sale';
//                        //Send Post Request to Frontend
//                        $timeout = 30;
//                        $curl = curl_init();
//                        $pvars = array('key' => $key);
//                        curl_setopt($curl, CURLOPT_URL, FRONT_SITE_URL . '/site/deletecache');
//                        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
//                        curl_setopt($curl, CURLOPT_POST, 1);
//                        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//                        curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
//                        $result = curl_exec($curl);
//                        curl_close($curl);
                        

                        user()->setFlash('success', Yii::t('AdminUser', 'Company product has been successfully updated!'));
                        //Yii::app()->controller->redirect(array('manageproducts', 'comp_id' => $product->companyId));
                    }
                }
            } else {
                //we clear the images from session if the form was no submitted
                if (Yii::app()->user->hasState('images')) {
                    Yii::app()->user->setState('images', null);
                }
            }

            $this->render('cmswidgets.views.product_sale.product_sale_form_widget', array('model' => $model, 'product' => $product, 'files' => $files));
        } else {
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        }
    }

}
