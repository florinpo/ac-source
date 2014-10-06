<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'message-form',
        'enableAjaxValidation' => true,
     ));
    ?>


    <?php
    echo $form->errorSummary($model);
    
    if ($to) {
        echo t('cms','This message will be sent to <b>{username}</b>', array(
            '{username}' => User::model()->findByPk($to)->full_name));
    } else {
       
    }
    ?>
    <?php 
    echo $form->hiddenField($model,'receiver_id',array('type'=>'hidden','value'=>$to));
    echo $form->hiddenField($model,'receiver_name',array('type'=>'hidden','value'=>$display_name));
    echo $form->hiddenField($model,'senderSpammed',array('type'=>'hidden','value'=>$senderSpammed));
    ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'subject'); ?>
        <?php echo $form->textField($model, 'subject', array('size' => 45, 'maxlength' => 45)); ?>
        <?php echo $form->error($model, 'subject'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'body'); ?>
        <?php echo $form->textArea($model, 'body', array('rows' => 6, 'cols' => 50)); ?>
        <?php echo $form->error($model, 'body'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(t('cms','Submit'), array('class' => 'bebutton')); ?>
    </div>

    <?php $this->endWidget(); ?>


</div><!-- form -->






