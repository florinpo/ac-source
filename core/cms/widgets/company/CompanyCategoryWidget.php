<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class CompanyCategoryWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

   protected function renderContent() {
        if (!user()->isGuest) {

            $user_id=isset($_GET['id']) ? (int)$_GET['id'] : 0;
            $user = User::model()->findbyPk($user_id);
            $model = new CompanyCategoryForm;
            $profile = UserCompanyProfile::model()->findByAttributes(array('companyId'=>$user_id));
            $categories = $profile->categories;

            //Set basic info for Current user
            //Get the user by current Id
            if ($user) {
                $model->selected_categories = $profile->categories;
            } else {
                throw new CHttpException('503', Yii::t('error','User is not valid'));
            }


            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-create-form') {
                echo CActiveForm::validate($model);
               
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['CompanyCategoryForm'])) {

                $model->attributes = $_POST['CompanyCategoryForm'];
                
                if ($model->validate()) {
                    $profile->categories = $model->selected_categories;
                    //$profile->selected_categories = $_POST['Portfolio']['serviceIds'];
                    $profile->save(false);
                     Yii::app()->controller->redirect(array('addcategory', 'id' => $user_id));
                    user()->setFlash('success', Yii::t('AdminUser','Company categories have been successfully updated!'));
                }
            }

            $this->render('cmswidgets.views.company.category_create_widget', array('model' => $model, 'profile'=>$profile, 'user'=>$user));
        } else {
            //Yii::app()->request->redirect(user()->returnUrl);
        }
    }

}
