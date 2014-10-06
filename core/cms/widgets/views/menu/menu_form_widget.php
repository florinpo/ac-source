<div class="form">
<?php $this->render('cmswidgets.views.notification'); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'menu-form',
        'enableAjaxValidation'=>true,       
        )); 
?>

<?php echo $form->errorSummary($model); ?>
<div id="language-zone">
<?php if($model->isNewRecord) : ?>
    <?php if(count($versions)>0) : ?>
    <div class="row">
            <?php echo "<strong style='color:#DD4B39'>".t("cms","Translated Version of :")."</strong><br />" ?>    

                <?php foreach($versions as $version) :?>
                <?php  echo "<br /><b>- ".$version."</b>"; ?>
                <?php endforeach; ?>


            <br />
    </div>
     <?php endif; ?>
     <?php $lang_number= GxcHelpers::getAvailableLanguages() ; 
     if(count($lang_number)>1) :  ?>
    <div class="row">    
            <?php echo $form->labelEx($model,'lang'); ?>	    
            <?php echo $form->dropDownList($model,'lang',GxcHelpers::loadLanguageItems($lang_exclude),
                    array('options' => array(array_search(Yii::app()->language,GxcHelpers::loadLanguageItems($lang_exclude,false))=>array('selected'=>true)))
                    ); ?>
            <?php echo $form->error($model,'lang'); ?>
            <div class="clear"></div>
    </div>
    <?php else : ?>
        <?php echo $form->hiddenField($model,'lang',array('value'=>GxcHelpers::mainLanguage())); ?>
    <?php endif; ?>
<?php endif; ?>
</div>
<div class="row">
        <?php echo $form->labelEx($model,'menu_name'); ?>
        <?php echo $form->textField($model,'menu_name'); ?>
        <?php echo $form->error($model,'menu_name'); ?>
</div>
<div class="row">
        <?php echo $form->labelEx($model,'menu_description'); ?>
        <?php echo $form->textField($model,'menu_description'); ?>
        <?php echo $form->error($model,'menu_description'); ?>
</div>

    
       
<div class="row buttons">
        <?php echo CHtml::submitButton(t('cms','Save'),array('class'=>'bebutton')); ?>
</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
