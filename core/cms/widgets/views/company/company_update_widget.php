<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'company-update-form',
        'enableAjaxValidation' => true,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username',  array('disabled'=>true)); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email',  array('disabled'=>true)); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status',ConstantDefine::getUserStatus()); ?>
        <?php echo $form->error($model,'status'); ?>                                  
    </div>

    <div class="row clearfix toHide" id="user_membership">
            <label>Membership</label>
            <?php
            echo CHtml::radioButtonList("membership_type", (isset($model->membership->membership_id)) ? $model->membership->membership_id : "0", MembershipItem::getMembershipOptions(), array('separator' => '',
                'class' => 'radio_btn'));
            ?>
            <span class="clear"></span>
        </div>

    <div class="row buttons">
       <?php echo CHtml::submitButton(Yii::t('Global','Save'), array('class' => 'bebutton')); ?>
    </div>
    
    

<?php $this->endWidget(); ?>

</div><!-- form -->


















