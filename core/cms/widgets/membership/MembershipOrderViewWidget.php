<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MembershipOrderViewWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
       
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $model = GxcHelpers::loadDetailModel('MembershipOrder', $id);
     
        $this->render('cmswidgets.views.membership.membership_order_view_widget', array('model' => $model));
    }

}
