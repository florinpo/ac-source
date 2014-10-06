<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class StoreUpdateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        $storeId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($storeId != 0) {

            $model = CompanyStore::model()->findByPk($storeId);
            Yii::import("cms.extensions.xupload.models.XUploadForm");
            $photos = new XUploadForm;

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'store-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['CompanyStore'])) {
                $model->attributes = $_POST['CompanyStore'];
                $model->name= ucfirst($model->name);
                $model->slug = toSlug($model->name);
                // validate user input password
                if ($model->validate()) {
                   
                    $model->save();
                    user()->setFlash('success', Yii::t('AdminUser', 'New company has beed successfully created.'));


                    //Yii::app()->controller->redirect(array('view', 'id' => $storeId));
                }
            } else {
                //we clear the images from session if the form was no submitted
                if (Yii::app()->user->hasState('images')) {
                    Yii::app()->user->setState('images', null);
                }
            }

            $this->render('cmswidgets.views.store.store_form_widget', array('model' => $model, 'photos' => $photos));
        } else {
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        }
    }

}
