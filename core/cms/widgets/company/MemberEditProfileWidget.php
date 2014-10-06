<?php

/**
 * This is the Widget for User update his own settings.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MemberEditProfileWidget extends CWidget {

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

            $comp_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            $user = User::model()->findbyPk($comp_id);
            $umodel = new MemberProfileForm;
            $profile = $user->cprofile;

            //Set basic info for Current user
            //Get the user by current Id
            if ($user) {
                $umodel->firstname = $profile->firstname;
                $umodel->lastname = $profile->lastname;
                $umodel->companyposition = $profile->companyposition;
                $umodel->companyname = $profile->companyname;
                $umodel->companytype = $profile->companytype;
                $umodel->vat_code = $profile->vat_code;
                $umodel->region_id = $profile->region_id;
                $umodel->province_id = $profile->province_id;
                $umodel->location = $profile->location;
                $umodel->adress = $profile->adress;
                $umodel->postal_code = $profile->postal_code;
                $umodel->phone = $profile->phone;
                $umodel->fax = $profile->fax;
                $umodel->mobile = $profile->mobile;
              
                //$umodel->selected_cats = $profile->categories;
            } else {
                throw new CHttpException('503', Yii::t('error', 'User is not valid'));
            }
            
            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'member-profile-form') {
                echo CActiveForm::validate($umodel);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['MemberProfileForm'])) {
                $umodel->attributes = $_POST['MemberProfileForm'];
                if ($umodel->validate()) {
                    $profile->firstname = $umodel->firstname;
                    $profile->lastname = $umodel->lastname;
                    $profile->companyposition = $umodel->companyposition;
                    $profile->companyname = $umodel->companyname;
                    $profile->companytype = $umodel->companytype;
                    $profile->vat_code = $umodel->vat_code;
                    $profile->region_id = $umodel->region_id;
                    $profile->province_id = $umodel->province_id;
                    $profile->location = $umodel->location;
                    $profile->adress = $umodel->adress;
                    $profile->postal_code = $umodel->postal_code;
                    $profile->phone = $umodel->phone;
                    $profile->fax = $umodel->fax;
                    $profile->mobile = $umodel->mobile;
                    $profile->save();
                    
                    $user->display_name = $umodel->companyname;
                    $user->save(false);
                    
                    user()->setFlash('success', Yii::t('AdminUser', 'Company profile was successfully updated!'));
                    Yii::app()->controller->redirect(array('memberprofile', 'id' => $user->user_id));
                }
            }

            $this->render('cmswidgets.views.company.member_edit_profile_widget', array('umodel' => $umodel, 'user' => $user, 'profile' => $profile));
        } else {
            //Yii::app()->request->redirect(user()->returnUrl);
        }
    }

}
