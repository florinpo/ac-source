<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MessageConfirmWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
            $to= isset($_GET['to']) ? (int)$_GET['to'] : 0;
            $this->render('cmswidgets.views.pmessage.message_confirm_widget', array('to' => $to));
    }

}
