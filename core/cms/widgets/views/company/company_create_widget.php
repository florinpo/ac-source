<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'company-create-form',
        'enableAjaxValidation' => true,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username'); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email'); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>
   
    <div class="row">
        <?php echo $form->labelEx($model, 'companyname'); ?>
        <?php echo $form->textField($model, 'companyname'); ?>
        <?php echo $form->error($model, 'companyname'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'verifyPassword'); ?>
        <?php echo $form->passwordField($model, 'verifyPassword'); ?>
        <?php echo $form->error($model, 'verifyPassword'); ?>
    </div>

    <div class="row clearfix">
        <label>Membership</label>
        <?php
        echo $form->radioButtonList($model, 'membership_type', MembershipItem::getMembershipOptions(), array('separator' => '',
            'class' => 'radio_btn'));
        ?>
        <span class="clear"></span>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(t('cms','Save'), array('class' => 'bebutton')); ?>
    </div>

    <?php $this->endWidget(); ?>

    
</div><!-- form -->






