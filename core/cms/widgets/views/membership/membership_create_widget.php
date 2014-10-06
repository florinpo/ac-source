<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'membership-form',
        'enableAjaxValidation' => true,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'title'); ?>
        <?php echo $form->textField($model, 'title', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'title'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textField($model, 'description', array('size' => 60, 'maxlength' => 255)); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'items_num'); ?>
        <?php echo $form->textField($model, 'items_num', array()); ?>
        <?php echo $form->error($model, 'items_num'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'rolename'); ?>
        <?php
        $roles = AuthItem::model()->findAll(array('condition'=>'type=2'));
       
        echo $form->dropdownList($model, 'rolename', CHtml::listData($roles, 'name', 'name'),array('prompt' => '-- None--'));
        ?>
       <?php echo $form->error($model, 'rolename'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model,'price'); ?>
        <?php echo $form->textField($model, 'price',array('class'=>'price1')); ?>
        <?php echo CHtml::error($model, 'price'); ?>

    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'duration'); ?>
        <?php echo $form->textField($model, 'duration', array('class' => 'days')); ?>
        <?php echo $form->dropDownList($model,'duration_type',  ConstantDefine::getMDurationType(),array()); ?>
        <?php echo $form->error($model, 'duration'); ?>
    </div>

    <div class="row buttons">
<?php echo CHtml::submitButton(Yii::t('Global','Save'), array('class' => 'bebutton')); ?>
    </div>

<?php $this->endWidget(); ?>


</div><!-- form -->

