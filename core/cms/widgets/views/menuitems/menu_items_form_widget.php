
<div class="form">
<?php $this->render('cmswidgets.views.notification'); ?>
<?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'menuitems-form',
        'enableAjaxValidation'=>true,       
        )); 
?>

<?php echo $form->errorSummary($model); ?>
    
<div class="row">
        <?php echo $form->labelEx($model,'name'); ?>
        <?php echo $form->textField($model,'name'); ?>
        <?php echo $form->error($model,'name'); ?>
</div>
<div class="row">
        <?php echo $form->labelEx($model,'description'); ?>
        <?php echo $form->textField($model,'description'); ?>
        <?php echo $form->error($model,'description'); ?>
</div>
 <div class="row">
        <?php echo $form->labelEx($model, 'parent_id'); ?>
        <?php if ($model->isNewRecord): ?>
            <?php
            echo CHtml::listBox('parent_id', '', MenuItems::getOptions((int)($_GET['menu'])), array('size' => 7,'style' => 'width: 250px'));
            ?>

        <?php else: ?>
    
            <?php
            $category = MenuItems::model()->findByPk($model->id);
            $parent = $category->parent;
            $select = array();
            if (isset($parent)) {
                array_push($select, $parent->id, $parent->name);
            }

            $children = $category->descendants()->findAll();

            echo CHtml::listBox('parent_id', $select, MenuItems::getOptions((int)($_GET['menu']),$model->id, $children), array('size' => 7,'style' => 'width: 250px'));
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
               echo CHtml::dropDownList('order_id', $model->id, MenuItems::getSiblings($model->id, false));
            ?>

        <?php endif; ?>
        
</div>   
<div class="row">
        <?php echo $form->labelEx($model,'type'); ?>
        <?php echo $form->dropDownList($model,'type', ConstantDefine::getMenuType(),array('id'=>'menu_type','options' => array(ConstantDefine::MENU_TYPE_URL=>array('selected'=>true)))); ?>
        <?php echo $form->error($model,'type'); ?>
</div>
<div class="row" >
        <?php echo $form->labelEx($model,'value'); ?>
    
        <!-- Start for the form of URL  -->
        <div class="type_form" id="type_form_div_<?php echo ConstantDefine::MENU_TYPE_URL; ?>" style="display: none">
             <input type="text" name="type_form_<?php echo ConstantDefine::MENU_TYPE_URL; ?>" id="type_form_<?php echo ConstantDefine::MENU_TYPE_URL; ?>" value="" class="text_type_form simple_text_type_form"/>
        </div>
        
        <!-- Start for the form of Page Autocomplete -->
        <div class="type_form" id="type_form_div_<?php echo ConstantDefine::MENU_TYPE_PAGE; ?>" style="display: none">
        <?php $this->widget('CAutoComplete', array(
                            'name'=>'type_form_'.ConstantDefine::MENU_TYPE_PAGE,
                            'url'=>array('suggestPage'),
                            'value'=> ($model->isNewRecord) ? '' : MenuItems::ReBindValueForMenuType($model->type,$model->value),
                            'multiple'=>false,
                            'mustMatch'=>true,
                            'htmlOptions'=>array('size'=>50,'class'=>'text_type_form maxWidthInput','id'=>'type_form_'.ConstantDefine::MENU_TYPE_PAGE),
                            'methodChain'=>".result(function(event,item){ if(item!==undefined) \$(\"#menu_value\").val(item[1]);})",
                    )); ?>
        </div>
        
         <!-- Start for the form of Content Autocomplete -->
        <div class="type_form" id="type_form_div_<?php echo ConstantDefine::MENU_TYPE_CONTENT; ?>" style="display: none">
        <?php $this->widget('CAutoComplete', array(
                            'name'=>'type_form_'.ConstantDefine::MENU_TYPE_CONTENT,
                            'url'=>array('suggestContent'),
                            'value'=> ($model->isNewRecord) ? '' : MenuItems::ReBindValueForMenuType($model->type,$model->value),
                            'multiple'=>false,
                            'mustMatch'=>true,
                            'htmlOptions'=>array('size'=>50,'class'=>'text_type_form maxWidthInput','id'=>'type_form_'.ConstantDefine::MENU_TYPE_CONTENT),
                            'methodChain'=>".result(function(event,item){ if(item!==undefined) \$(\"#menu_value\").val(item[1]);})",
                    )); ?>
        </div>
        
        
        <!-- Start for the form of Term Autocomplete -->
        <div class="type_form" id="type_form_div_<?php echo ConstantDefine::MENU_TYPE_TERM; ?>" style="display: none">
        <?php $this->widget('CAutoComplete', array(
                            'name'=>'type_form_'.ConstantDefine::MENU_TYPE_TERM,
                            'url'=>array('suggestTerm'),
                            'value'=> ($model->isNewRecord) ? '' : MenuItems::ReBindValueForMenuType($model->type,$model->value),
                            'multiple'=>false,
                            'mustMatch'=>true,
                            'htmlOptions'=>array('size'=>50,'class'=>'text_type_form maxWidthInput','type_form_'.ConstantDefine::MENU_TYPE_TERM),
                            'methodChain'=>".result(function(event,item){ if(item!==undefined)  \$(\"#menu_value\").val(item[1]);})",
                    )); ?>
        
        </div>        
                
       <!-- Start for the form of String  -->
       <div class="type_form" id="type_form_div_<?php echo ConstantDefine::MENU_TYPE_STRING; ?>" style="display: none">
           <input type="text" name="type_form_<?php echo ConstantDefine::MENU_TYPE_STRING; ?>" id="type_form_<?php echo ConstantDefine::MENU_TYPE_STRING; ?>" value="<?php echo $model->isNewRecord ? '' : $model->value; ?>" class="text_type_form simple_text_type_form" />
       </div>
        
        
        <?php echo $form->hiddenField($model,'value', array('id'=>'menu_value')); ?>
        <?php echo $form->error($model,'value'); ?>
       <script type="text/javascript">
           var current_menu_type=$('#menu_type').val();
           $('.type_form').hide();
           $('#type_form_div_'+current_menu_type).show();
           
           $('#menu_type').change(function() {               
               $('.type_form').hide();
               $('#type_form_div_'+$(this).val()).show();
           });
           
           
           $('.simple_text_type_form').keyup(function() {                                             
               $('#menu_value').val($(this).val());
           });
           
            $('.simple_text_type_form').change(function() {                              
              
               $('#menu_value').val($(this).val());
           });
           
          
           
       </script>
</div>
    
<div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('Global','Save'),array('class'=>'button')); ?>
</div>
<?php $this->endWidget(); ?>
</div><!-- form -->
<?php
$menuType = " 
var current_menu_type=$('#menu_type').val();
           $('.type_form').hide();
           $('#type_form_div_'+current_menu_type).show();
           
           $('#menu_type').change(function() {               
               $('.type_form').hide();
               $('#type_form_div_'+$(this).val()).show();
           });
           
           
           $('.simple_text_type_form').keyup(function() {                                             
               $('#menu_value').val($(this).val());
           });
           
            $('.simple_text_type_form').change(function() {                              
              
               $('#menu_value').val($(this).val());
           });
"
?>
<?php
Yii::app()->clientScript->registerScript('menuType', "$(document).ready(function(){" . $menuType . "});", CClientScript::POS_HEAD);
?>
