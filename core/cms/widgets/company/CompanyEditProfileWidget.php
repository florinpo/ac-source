<?php

/**
 * This is the Widget for User update his own settings.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class CompanyEditProfileWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        $comp_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($comp_id != 0) {

            Yii::import("cms.extensions.xupload.models.XUploadForm");
            $files = new XUploadForm;
            $user = User::model()->findbyPk($comp_id);
            $cmodel = new UserCompanyProfileForm;
            $profile = UserCompanyProfile::model()->find(array('condition' => 'companyId=:companyId', 'params' => array(':companyId' => $user->user_id)));

            //Set basic info for Current user
            //Get the user by current Id
            if ($user) {
                $cmodel->description = $profile->description;
                $cmodel->website = $profile->website;
                $cmodel->services = $profile->services;
                $cmodel->certificate = $profile->certificate;
                $cmodel->marketplace = $profile->marketplace;
                $cmodel->selected_cats = !empty($user->categoryIds) ? implode(',', $user->categoryIds):'';
            } else {
                throw new CHttpException('503', Yii::t('error', 'User is not valid'));
            }

            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'company-profile-form') {
                echo CActiveForm::validate($cmodel);

                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['UserCompanyProfileForm'])) {

                $cmodel->attributes = $_POST['UserCompanyProfileForm'];
               

                if ($cmodel->validate()) {
                    $profile->description = $cmodel->description;
                    $profile->website = $cmodel->website;
                    $profile->services = $cmodel->services;
                    $profile->marketplace = $cmodel->marketplace;
                    $profile->certificate = $cmodel->certificate;
                    $profile->save();
                    $user->categories = explode(',', $cmodel->selected_cats);
                    $user->save(false);
                    user()->setFlash('success', Yii::t('AdminUser', 'Company profile was successfully updated!'));
                    Yii::app()->controller->redirect(array('companyprofile', 'id' => $user->user_id));
                }
            } else {
                //we clear the images from session if the form was no submitted
                if (Yii::app()->user->hasState('images')) {
                    Yii::app()->user->setState('images', null);
                }
            }

            $this->render('cmswidgets.views.company.company_edit_profile_widget', array('cmodel' => $cmodel, 'user' => $user, 'profile' => $profile, 'files' => $files));
        } else {
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        }
    }

}
