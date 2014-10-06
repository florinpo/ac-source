<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'profile-form',
        'enableAjaxValidation' => true,
      ));
    ?>
    
    <?php echo $form->errorSummary($model); ?>
    
    <div class="row">
        <?php echo $form->labelEx($user, 'username'); ?>
        <?php echo CHtml::activeTextField($user, 'username', array('disabled'=>true)); ?>
       
    </div>
    <div class="row">
        <?php echo $form->labelEx($user, 'email'); ?>
        <?php echo CHtml::activeTextField($user, 'email', array('disabled'=>true)); ?>
        
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'firstname'); ?>
        <?php echo $form->textField($model, 'firstname'); ?>
        <?php echo $form->error($model, 'firstname'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'lastname'); ?>
        <?php echo $form->textField($model, 'lastname'); ?>
        <?php echo $form->error($model, 'lastname'); ?>
    </div>

    <div class="row clearfix">
        <?php echo $form->labelEx($model, 'gender'); ?>
        <?php echo $form->radioButtonList($model, 'gender', array('male' => Yii::t('AdminUser', 'Male'), 'female' => Yii::t('AdminUser', 'Female')), array('separator' => '', 'class' => 'radio_btn')); ?>
        <span class="clear"></span>
        <?php echo $form->error($model, 'gender'); ?>
    </div>
    
    <div class="row">
       <?php echo $form->labelEx($model, 'birthday'); ?>
        <?php
        $this->widget('CMaskedTextField', array(
            'model' => $model,
            'attribute' => 'birthday',
            'mask' => '99/99/9999',
            'htmlOptions' => array('size' => 20, 'placeholder' => 'dd/mm/aaaa')
        ));
        ?>
        <?php echo $form->error($model, 'birthday'); ?>     
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'region_id'); ?>	        
        <?php
        echo $form->dropDownList($model, 'region_id', Province::getRegion(), array(
            'ajax' => array(
                'type' => 'POST', //request type
                'url' => $this->getController()->createUrl('provinceFromRegion'),
                'data'=>array('region_id'=>'js:this.value', 'YII_CSRF_TOKEN'=>Yii::app()->getRequest()->getCsrfToken()),
                'update'=>'#'.CHtml::activeId($model,'province_id'),
                ))
        );
        ?>
        <?php echo $form->error($model, 'region_id'); ?>
    </div>

     <?php $get_province_id = isset($_GET['province_id']) ? (int) ($_GET['province_id']) : null ?>
    <?php if ($get_province_id === null) : ?>
    <div class="row">
         <label for="region_id"><?php echo Yii::t('FrontendUser', 'Province'); ?></label>	 
        <?php echo $form->dropDownList($model, 'province_id', Province::getProvinceFromRegion($model->region_id, false), array('options' => array($model->province_id => array('selected' => true)))); ?>
        <?php echo $form->error($model, 'province_id'); ?>
    </div>
    <?php else : ?>
        <?php echo $form->hiddenField($model, 'province_id', array('value' => $get_province_id)); ?>
    <?php endif; ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'location'); ?>
        <?php echo $form->textField($model, 'location'); ?>
        <?php echo $form->error($model, 'location'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'adress'); ?>
        <?php echo $form->textField($model, 'adress'); ?>
        <?php echo $form->error($model, 'adress'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($model, 'phone'); ?>
        <?php echo $form->textField($model, 'phone'); ?>
        <?php echo $form->error($model, 'phone'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('Global', 'Save'), array('class' => 'bebutton')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->