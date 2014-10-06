<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MembershipCreateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        $model = new MembershipItemForm;


        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'membership-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['MembershipItemForm'])) {
            $model->attributes = $_POST['MembershipItemForm'];
            if ($model->validate()) {
                $membership = new MembershipItem;
                $membership->title = $model->title;
                $membership->rolename = $model->rolename;
                $membership->description = $model->description;
                $membership->price = $model->price;
                $membership->duration = $model->duration;
                $membership->duration_type = $model->duration_type;
                $membership->items_num = $model->items_num;
                if ($membership->save()) {
                    //Start to save the Page Block
                    user()->setFlash('success', Yii::t('AdminMembershipItem','New membership item has been successfully created!'));
                    $model = new MembershipItemForm;
                    Yii::app()->controller->redirect(array('admin'));
                }
            }
        }

        $this->render('cmswidgets.views.membership.membership_create_widget', array('model' => $model));
    }

}
