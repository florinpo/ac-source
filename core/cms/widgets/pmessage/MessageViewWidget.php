<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MessageViewWidget extends CWidget {

    public $visible = true;

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $id = isset($_GET['id']) ? (int) ($_GET['id']) : 0;

        $type = isset($_GET['mailbox']) ? ($_GET['mailbox']) : '';
        $model = PrivateMessage::model()->findByPk($id);
        if ($model != null) {
            if ($model->receiver_id == user()->id || $model->sender_id == user()->id) {
                $model = $model;
                if (!$model->is_read && $model->receiver_id == user()->id) {
                    $model->is_read = true;
                    $model->save(false, array('is_read'));
                }
            } else {
                $model = null;
            }
        }

        if (in_array($type, array('inbox', 'outbox', 'recyclebox', 'spambox'))) {
            $this->render('cmswidgets.views.pmessage.message_view_' . $type . '_widget', array('model' => $model));
        } else {
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        }
    }

}
