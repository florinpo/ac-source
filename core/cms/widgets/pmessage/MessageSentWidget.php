<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MessageSentWidget extends CWidget {

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
        $criteria->addCondition('t.sender_id=:senderId');
        $criteria->addCondition('t.senderMarkDeleted=0 AND t.senderDeleted=0');
        $criteria->addCondition('t.receiverSpammed=0');
        $criteria->order = 't.create_time DESC';
        $criteria->params = array(
            ':senderId' => user()->id
        );

        $model = new CActiveDataProvider('PrivateMessage', array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageVar' => 'page'
                    ),
                ));

        $this->render('cmswidgets.views.pmessage.message_sent_widget', array('model' => $model));
    }

}
