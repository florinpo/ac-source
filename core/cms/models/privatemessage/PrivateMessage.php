<?php

class PrivateMessage extends CActiveRecord {

    public $unreadMessagesCount;
    public $unreadSpamMessagesCount;
    public $unreadDeletedMessagesCount;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{private_message}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
// NOTE: you should only define rules for those attributes that
// will receive user inputs.
        return array(
            array('sender_id, receiver_id, body, subject', 'required'),
            array('sender_id, receiver_id, is_read, create_time, update_time, answered, senderSpammed, receiverSpammed, senderMarkDeleted, receiverMarkDeleted, senderDeleted, receiverDeleted', 'numerical', 'integerOnly' => true),
            array('subject, ', 'length', 'max' => 256),
            array('sender_name, receiver_name', 'length', 'max' => 100),
            //array('sentDate, modifiedDate', 'safe'),
// The following rule is used by search().
// Please remove those attributes that should not be searched.
            array('id, sender_id, receiver_id, sender_name, receiver_name, subject, body, create_time, update_time, is_read, answered, senderSpammed, receiverSpammed, senderMarkDeleted, receiverMarkDeleted, senderDeleted, receiverDeleted', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
// NOTE: you may need to adjust the relation name and the related
// class name for the relations automatically generated below.
        return array(
            'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
            'receiver' => array(self::BELONGS_TO, 'User', 'receiver_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'sender_id' => 'Sender',
            'receiver_id' => 'Receiver',
            'sender_name' => 'Sender Name',
            'receiver_name' => 'Receiver Name',
            'subject' => 'Subject',
            'body' => 'Body',
            'create_time' => 'Sent Date',
            'update_time' => 'Update Date',
            'is_read' => 'Is Read',
            'answered' => 'Answered',
            'senderSpammed' => 'Sender Spammed',
            'receiverSpammed' => 'Recipient Spammed',
            'senderMarkDeleted' => 'Sender Mark Deleted',
            'receiverMarkDeleted' => 'Recipient Mark Deleted',
            'senderDeleted' => 'Sender Deleted',
            'receiverDeleted' => 'Recipient Deleted',
        );
    }

    public function unreadTitle() {
        if ($this->is_read) {
            $title = $this->subject;
        } else {
            $title = '<strong>' . $this->subject . '</strong>';
        }
        return $title;
    }

// $this function check if the message sender is available
    public static function activeSender($user, $id) {
        $message = PrivateMessage::model()->findbyPk($id);
        if ($user != null) {
            if ($user->status == '1') {
                $display_name = $message->sender_name;
            } else {
                $display_name = '<p style="text-decoration:line-through;color:#F00; margin:0"><span style="color:#666;">' . $message->sender_name . '</span></p>';
            }
        } else {
            $display_name = '<p style="text-decoration:line-through;color:#F00; margin:0"><span style="color:#666;">' . $message->sender_name . '</span></p>';
        }
        return $display_name;
    }

// $this function check if the message receiver is available
    public static function activeReceiver($user, $id) {
        $message = PrivateMessage::model()->findbyPk($id);
        if ($user != null) {
            if ($user->status == '1') {
                $display_name = $message->receiver_name;
            } else {
                $display_name = '<p style="text-decoration:line-through;color:#F00; margin:0"><span style="color:#666;">' . $message->receiver_name . '</span></p>';
            }
        } else {
            $display_name = '<p style="text-decoration:line-through;color:#F00; margin:0"><span style="color:#666;">' . $message->receiver_name . '</span></p>';
        }
        return $display_name;
    }

//return unread row for deleted messages
    public static function deletedUnread($id) {
        $message = PrivateMessage::model()->findbyPk($id);
        if ($message->is_read == '0' && $message->receiver_id == user()->id) {
            return "unread";
        } else {
            return "";
        }
    }

//return unread row for spam messages
    public static function spamUnread($id) {
        $message = PrivateMessage::model()->findbyPk($id);
        if ($message->is_read == '0' && $message->receiver_id == user()->id) {
            return "unread";
        } else {
            return "";
        }
    }

    public function unreadMessages($userId) {
        if (!$this->unreadMessagesCount) {
            $c = new CDbCriteria();
            $c->addCondition('t.receiver_id = :receiverId');
            $c->addCondition('t.receiverMarkDeleted ="0" AND t.receiverDeleted="0"');
            $c->addCondition('t.is_read = "0" AND t.senderSpammed = "0"');
            $c->params = array(
                'receiverId' => $userId,
            );
            $count = self::model()->count($c);
            $this->unreadMessagesCount = $count;
        }

        return $this->unreadMessagesCount;
    }

    public function spamMessages($userId) {
        if (!$this->unreadMessagesCount) {
            $c = new CDbCriteria();
            $c->addCondition('t.receiver_id = :receiverId');
            $c->addCondition('t.receiverMarkDeleted ="0" AND t.receiverDeleted="0"');
            $c->addCondition('t.is_read = "0" AND t.senderSpammed = "1"');
            $c->params = array(
                'receiverId' => $userId,
            );
            $count = self::model()->count($c);
            $this->unreadSpamMessagesCount = $count;
        }

        return $this->unreadSpamMessagesCount;
    }

    public function deletedMessages($userId) {
        if (!$this->unreadMessagesCount) {
            $c = new CDbCriteria();
            $c->addCondition('t.receiver_id = :receiverId');
            $c->addCondition('t.receiverMarkDeleted ="1"');
            $c->addCondition('t.receiverDeleted ="0"');
            $c->addCondition('t.is_read = "0"');
            $c->params = array(
                'receiverId' => $userId,
            );
            $count = self::model()->count($c);
            $this->unreadDeletedMessagesCount = $count;
        }

        return $this->unreadDeletedMessagesCount;
    }

    protected function afterSave() {
        parent::afterSave();
         $this->updateCacheDependencies(); 
    }

    protected function afterDelete() {
        parent::afterDelete();
         $this->updateCacheDependencies();
    }

    protected function updateCacheDependencies()
        {
                //Update timestamps on related models so that view caches get updated
                $cacheUpdates = array();
                $cacheUpdates[] = 'Cache.'.$this->tableSchema->name;
                $relations = $this->relations();
                foreach($relations as $relation)
                        $cacheUpdates[] = 'Cache.'.strtolower($relation[1]);

                foreach($cacheUpdates as $cacheUpdate)
                {
                        Yii::app()->setGlobalState($cacheUpdate, microtime(true));
                        Yii::app()->saveGlobalState();
                }
        }


}