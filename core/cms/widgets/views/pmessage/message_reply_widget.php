<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'message-form',
        'enableAjaxValidation' => true,
     ));
    ?>

    <?php echo $form->errorSummary($model); ?>
   
   <?php
    if ($receiver_id) {
        echo CHtml::hiddenField('PrivateMessage[answered]', $answer_to);
        echo Yii::t('PrivateMessage','This message will be sent to <b>{username}</b>', array(
            '{username}' => User::model()->findByPk($receiver_id)->display_name));
    }
    ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'subject'); ?>
        <?php echo $form->textField($model, 'subject', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'subject'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'body'); ?>
        <?php 
        $message = $model->body;
        //$info = $model->from_user->display_name;
        $header = "\n\n"."--------------------------------------------"."\n";
        $header.= t('cms', 'Date: {date}', array('{date}'=> niceDate($original->create_time)))."\n" ;
        $header.= t('cms', 'From: {user}', array('{user}'=> $original->sender_name))."\n";
        $header.= t('cms', 'Sent to: {user}', array('{user}'=> $original->receiver_name))."\n";
        $header.= t('cms', 'Subject: {subject}', array('{subject}'=>$model->subject))."\n";
        $header.="\n";
        $model->body = $header . $message."\n";
        
        echo $form->textArea($model, 'body', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'body'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('Global','Reply'), array('class' => 'bebutton')); ?>
    </div>

    <?php $this->endWidget(); ?>


</div><!-- form -->






