<?php

class ButtonActionBehavior extends CBehavior {

    public $controller;
    public $buttons = array();

    /*
     * Actions for mailbox
     */
    public function actionMailbox($box = 'inbox', $buttonset = 'mailbox') {
        if (!$_POST['button']) {
            if ($_GET['ajax'])
                die('{"error":"Action not found?"}');
            Yii::app()->user->setFlash('error', "Action not found?");
            $this->controller->redirect(array('page/render', 'slug' => $box));
        }
        $action = key($_POST['button']);
        if (!array_key_exists($action, $this->buttons[$buttonset]))
            throw new Exception('Button action not found?');

        $partialmsg = $this->buttons[$buttonset][$action];

        $count = 0;
        foreach ($_POST['convs'] as &$conversation_id) {
            /*
             * None of the following errors should happen unless the user 
             * tampers with the input vars, so we ignore them and continue
             */
            if (!is_int($conversation_id = (int) $conversation_id))
                continue;
            $conv = Mailbox::model()->findByPk($conversation_id);

            if (!$conv->belongsTo(user()->id))
                continue;
            if (!$conv->$action(user()->id) || !$conv->validate())
                continue;
            if ($conv->save())
                $count++;
        }

        if ($count) {
            $title = ucfirst($partialmsg);
            $message = $count . " message(s) have been {$partialmsg}!";
            if (isset($_GET['ajax'])) {
                $tinydesc = isset($_GET['dragdrop']) ? ',"tinydesc":"+' . $count . ' deleted!","dragdrop":"' . $_GET['dragdrop'] . '"' : null;
                die('{"success":"' . $message . '"' . $tinydesc . ',"title":"' . $title . '"}');
            }
            Yii::app()->user->setFlash('success', $message);
            $this->controller->redirect(array('page/render', 'slug' => $box));
        } else {
            $title = "Error occured?";
            $message = "Message could not be {$partialmsg}!";
            if (isset($_GET['ajax'])) {
                $tinydesc = isset($_GET['dragdrop']) ? ',"tinydesc":"Error deleting?","dragdrop":"' . $_GET['dragdrop'] . '"' : null;
                die('{"error":"Error deleting?"' . $tinydesc . '}');
                die('{"error":"' . $message . '"}');
            }
            Yii::app()->user->setFlash('error', $message);
            $this->controller->redirect(array('page/render', 'slug' => $box));
        }
    }

}