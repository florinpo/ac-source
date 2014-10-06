<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MessageReplyWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $id = isset($_GET['m']) ? (int) $_GET['m'] : 0;
        $original = PrivateMessage::model()->findByPk($id);
        if (user()->id == $original->sender_id) {
            $receiver_id = $original->receiver_id;
        } else {
            $receiver_id = $original->sender_id;
        }
        $reply = new PrivateMessage;

        //first we check if we already sent message to this user to determine the spam
        $oldmessage = PrivateMessage::model()->find(array(
            'condition' => 'sender_id=:senderId AND receiver_id=:receiverId',
            'params' => array(':senderId' => user()->id, ':receiverId' => $receiver_id),
            'order' => 'update_time DESC'
                ));

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'message-form') {
            echo CActiveForm::validate($reply);
            Yii::app()->end();
        }
        if (substr($original->subject, 0, 3) != "Re:") {
            $reply->subject = 'Re: ' . $original->subject;
        } else {
            $reply->subject = 'Re: ' . substr($original->subject, 3);
        }
        $reply->body = $original->body;
        // collect user input data
        if (isset($_POST['PrivateMessage'])) {
            $reply->attributes = $_POST['PrivateMessage'];
            $reply->create_time = $reply->update_time = time();
            $reply->receiver_id = $receiver_id;
            $reply->sender_id = user()->id;
            $reply->sender_name = User::model()->findByPk(user()->id)->display_name;
            $reply->receiver_name = User::model()->findByPk($receiver_id)->display_name;
            $reply->senderSpammed = isset($oldmessage) ? $oldmessage->senderSpammed : '0';
            if ($reply->validate()) {
                if ($reply->save()) {
                    Yii::app()->controller->redirect(array('confirm'));
                    $reply = new PrivateMessage;
                }
            }
        }

        $this->render('cmswidgets.views.pmessage.message_reply_widget', array(
            'model' => $reply,
            'answer_to' => $id,
            'receiver_id' => $receiver_id,
            'original' => $original
        ));
    }

}
