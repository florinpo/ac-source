<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MessageInboxWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $criteria = new CDbCriteria;
        $criteria->addCondition('t.receiver_id=:receiverId');
        $criteria->addCondition('t.receiverMarkDeleted=0 AND t.receiverDeleted=0 AND t.senderSpammed=0');
        $criteria->order = 't.create_time DESC';
        $criteria->params = array(
            ':receiverId' => user()->id,
        );

        $model = new CActiveDataProvider('PrivateMessage', array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageVar' => 'page'
                    ),
                ));

        $this->render('cmswidgets.views.pmessage.message_inbox_widget', array('model' => $model));
    }

}
