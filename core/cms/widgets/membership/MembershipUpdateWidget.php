<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MembershipUpdateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
       
        $membership_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $membership = GxcHelpers::loadDetailModel('MembershipItem', $membership_id);
        $model = new MembershipItemForm;
        $model->title = $membership->title;
        $model->rolename = $membership->rolename;
        $model->description = $membership->description;
        $model->price = $membership->price;
        $model->duration = $membership->duration;
        $model->duration_type = $membership->duration_type;
        $model->items_num = $membership->items_num;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'membership-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if (isset($_POST['MembershipItemForm'])) {
            $model->attributes = $_POST['MembershipItemForm'];
            if ($model->validate()) {
                $membership->title = $model->title;
                $membership->rolename = $model->rolename;
                $membership->description = $model->description;
                $membership->price = $model->price;
                $membership->duration = $model->duration;
                $membership->duration_type = $model->duration_type;
                $membership->items_num = $model->items_num;
                if ($membership->save()) {
                    //Start to save the Page Block
                   user()->setFlash('success', t('cms','Membership item has been successfully updated!')); 
                }
            }
        }
     
        $this->render('cmswidgets.views.membership.membership_update_widget', array('model' => $model));
    }

}
