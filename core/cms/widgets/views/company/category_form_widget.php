<?php $this->render('cmswidgets.views.notification'); ?>

<div class="form">
<?php $this->render('cmswidgets.views.notification'); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'category-form',
        'enableAjaxValidation'=>true,       
        )); 
?>

<?php echo $form->errorSummary($model); ?>
    
    
    
    
<div id="language-zone">
<?php if($model->isNewRecord) : ?>
    <?php if(count($versions)>0) : ?>
    <div class="row">
            <?php echo "<strong style='color:#DD4B39'>".t("Translated Version of :")."</strong><br />" ?>    

                <?php foreach($versions as $version) :?>
                <?php  echo "<br /><b>- ".$version."</b>"; ?>
                <?php endforeach; ?>


            <br />
    </div>
     <?php endif; ?>
     <?php if((int)settings()->get('system','language_number')>1) : ?>
    <div class="row">
            <?php echo $form->labelEx($model,'lang'); ?>	    
            <?php echo $form->dropDownList($model,'lang',Language::items($lang_exclude),
                    array('options' => array(array_search(Yii::app()->language,Language::items($lang_exclude,false))=>array('selected'=>true)))
                    ); ?>
            <?php echo $form->error($model,'lang'); ?>
            <div class="clear"></div>
    </div>
    <?php else : ?>
        <?php echo $form->hiddenField($model,'lang',array('value'=>Language::mainLanguage())); ?>
    <?php endif; ?>
<?php endif; ?>
</div>
<div class="row">
        <?php echo $form->labelEx($model, 'parent_id'); ?>
        <?php if ($model->isNewRecord): ?>
            <?php
            echo CHtml::listBox('parent_id', '', CompanyCats::getOptions(), array('size' => 7,'style' => 'width: 250px'));
            ?>

        <?php else: ?>
    
            <?php
            $category = CompanyCats::model()->findByPk($model->id);
            $parent = $category->parent;
            $select = array();
            if (isset($parent)) {
                array_push($select, $parent->id, $parent->name);
            }

            $children = $category->descendants()->findAll();

            echo CHtml::listBox('parent_id', $select, CompanyCats::getOptions($model->id, $children), array('size' => 7,'style' => 'width: 250px'));
            ?>

        <?php endif; ?>
       
</div>
    
    
 <div class="row">
        <?php echo $form->labelEx($model,'order_id'); ?>
        <?php if ($model->isNewRecord):
            echo "<p>Ordering will be available after saving</p>";
        elseif($model->isRoot()):
            echo "<p>Root Items are ordered by ID</p>";
        ?>
         
        <?php else: ?>

            <?php
               echo CHtml::dropDownList('order_id', $model->id, CompanyCats::getSiblings($model->id, false));
            ?>

        <?php endif; ?>
        
</div>   
        
    
<div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
</div>
<div class="row">
        <?php echo $form->labelEx($model,'slug'); ?>
        <?php echo $form->textField($model,'slug'); ?>
        <?php echo $form->error($model,'slug'); ?>
</div>
<div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textArea($model,'description'); ?>
        <?php echo $form->error($model,'description'); ?>
</div>
<div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('Global','Save'),array('class'=>'button')); ?>
    
        
    
</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
<?php if($model->isNewRecord) : ?>
<script type="text/javascript">
CopyString('#CompanyCats_name','#CompanyCats_slug','slug');
</script>
<?php endif; ?>