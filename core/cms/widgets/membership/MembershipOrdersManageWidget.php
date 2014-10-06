<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MembershipOrdersManageWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $model_name = 'MembershipOrder';
        if ($model_name != '') {

            $model = new $model_name('search');
            $model->unsetAttributes();  // clear any default values
            if (isset($_GET[$model_name]))
                $model->attributes = $_GET[$model_name];
            $this->render('cmswidgets.views.membership.membership_orders_manage_widget', array('model' => $model));
        } else {
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        }
    }

}
