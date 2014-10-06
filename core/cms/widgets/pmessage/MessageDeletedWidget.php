<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MessageDeletedWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        //deleted as messages sent
        $criteria = new CDbCriteria;
        $criteria->addCondition('t.sender_id = :senderId');
        $criteria->addCondition('t.senderMarkDeleted=1');
        $criteria->addCondition('t.senderDeleted=0');
        $criteria->addCondition('t.senderSpammed=0');
        $criteria->params = array(
            ':senderId' => user()->id,
        );
        $messages_sender = PrivateMessage::model()->findAll($criteria);

        //deleted as messages received
        $rcriteria = new CDbCriteria;
        $rcriteria->addCondition('t.receiver_id = :receiverId');
        $rcriteria->addCondition('t.receiverMarkDeleted=1');
        $rcriteria->addCondition('t.receiverDeleted=0');
        $rcriteria->addCondition('t.receiverSpammed=0');
        $rcriteria->params = array(
            ':receiverId' => user()->id,
        );
        $messages_receiver = PrivateMessage::model()->findAll($rcriteria);

        $model = new CArrayDataProvider(array_merge($messages_sender, $messages_receiver),
                        array(
                            'sort' => array(
                                'defaultOrder' => 'create_time DESC',
                            ),
                            'pagination' => array(
                                'pageVar' => 'page'
                            ),
                ));


        $this->render('cmswidgets.views.pmessage.message_deleted_widget', array('model' => $model));
    }

}
