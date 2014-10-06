<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MessageSpamWidget extends CWidget {

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
        $criteria->addCondition('t.receiver_id = :receiverId');
        $criteria->addCondition('t.senderSpammed=1');
        $criteria->addCondition('t.receiverMarkDeleted=0 AND t.receiverDeleted=0');
        $criteria->params = array(
            ':receiverId' => user()->id,
        );

        $messages_sender = PrivateMessage::model()->findAll($criteria);

        $rcriteria = new CDbCriteria;
        $rcriteria->addCondition('t.sender_id = :senderId');
        $rcriteria->addCondition('t.receiverSpammed=1');
        $rcriteria->addCondition('t.receiverMarkDeleted=0 AND t.receiverDeleted=0');
        $rcriteria->params = array(
            ':senderId' => user()->id,
        );

        $messages_receiver = PrivateMessage::model()->findAll($rcriteria);

        $model = new CArrayDataProvider(array_merge($messages_sender, $messages_receiver),
                        array('sort' => array(
                                'defaultOrder' => 'create_time DESC',
                            ),
                            'pagination' => array(
                                'pageVar' => 'page'
                            ),
                ));

        $this->render('cmswidgets.views.pmessage.message_spam_widget', array('model' => $model));
    }

}
