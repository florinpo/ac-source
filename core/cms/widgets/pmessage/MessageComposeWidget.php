<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MessageComposeWidget extends CWidget {

    public $visible = true;

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        $to = isset($_POST['to']) ? (int) $_POST['to'] : 0;
        $model = new PrivateMessage;
        if ($to != 0) {
            //first we check if we already sent message to this user to determine the spam
            $oldmessage = PrivateMessage::model()->find(array(
                'condition' => 'sender_id=:senderId AND receiver_id=:receiverId',
                'params' => array(':senderId' => user()->id, ':receiverId' => $to),
                'order' => 'update_time DESC'
                    ));
            
            $senderSpammed = $oldmessage != null ? $oldmessage->senderSpammed : 0;
            $receiver = User::model()->findByPk($to);
            $display_name = $receiver->full_name;
        } else {
           $display_name = '';
           $senderSpammed = 0;
        }
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'message-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['PrivateMessage'])) {
            $model->attributes = $_POST['PrivateMessage'];
            $model->sender_id = user()->id;
            $model->sender_name = User::model()->findByPk(user()->id)->full_name;
            $model->create_time = $model->update_time = time();
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::app()->controller->redirect(array('confirm'));
                }
            }
        }
        $this->render('cmswidgets.views.pmessage.message_compose_widget', array(
            'model' => $model,
            'to' => $to,
            'display_name'=>$display_name,
            'senderSpammed'=>$senderSpammed
            ));
    }

}
