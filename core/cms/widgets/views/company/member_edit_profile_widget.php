<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    
   
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'member-profile-form',
        'enableAjaxValidation' => true,
      ));
    ?>
    
    
    <?php echo $form->errorSummary($umodel); ?>
    <div class="row">
        <?php echo $form->labelEx($user, 'username'); ?>
        <?php echo CHtml::activeTextField($user, 'username', array('disabled'=>true)); ?>
       
    </div>
    <div class="row">
        <?php echo $form->labelEx($user, 'email'); ?>
        <?php echo CHtml::activeTextField($user, 'email', array('disabled'=>true)); ?>
        
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'firstname'); ?>
        <?php echo $form->textField($umodel, 'firstname'); ?>
        <?php echo $form->error($umodel, 'firstname'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($umodel, 'lastname'); ?>
        <?php echo $form->textField($umodel, 'lastname'); ?>
        <?php echo $form->error($umodel, 'lastname'); ?>
    </div>
    
    
    
    <div class="row">
    <?php echo $form->labelEx($umodel, 'companytype'); ?>
    <?php echo $form->dropDownList($umodel,'companytype',
            array(
            '1' => Yii::t('FrontendUser', 'Manufacturer'),
            '2' => Yii::t('FrontendUser', 'Distributor'),
            '3' => Yii::t('FrontendUser', 'Wholesaler'),
            '4' => Yii::t('FrontendUser', 'Retailer'),
            '5' => Yii::t('FrontendUser', 'Service provider'),
            '6' => Yii::t('FrontendUser', 'Intermediate'),
            '7' => Yii::t('FrontendUser', 'Importer')),
                   array('empty'=>t('cms', '-Select company type-'))); ?>
        <?php echo $form->error($umodel, 'companytype'); ?>
    </div>
    
     <div class="row">
        <?php echo $form->labelEx($umodel, 'companyposition'); ?>
        <?php
        echo $form->dropDownList($umodel, 'companyposition', array('1' => t('cms', 'Director'),
            '2' => t('cms', 'General Manager'),
            '3' => t('cms', 'Company Owner'),
            '4' => t('cms', 'Sales'),
            '5' => t('cms', 'Marketing'),
            '6' => t('cms', 'Administration'),
            '7' => t('cms', 'Other')), array('empty' => t('cms', '-Select position-')));
        ?>
        <?php echo $form->error($umodel, 'companyposition'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'companyname'); ?>
        <?php echo $form->textField($umodel, 'companyname'); ?>
        <?php echo $form->error($umodel, 'companyname'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'vat_code'); ?>
        <?php echo $form->textField($umodel, 'vat_code'); ?>
        <?php echo $form->error($umodel, 'vat_code'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'region_id'); ?>	        
        <?php
        echo $form->dropDownList($umodel, 'region_id', Province::getRegion(), array(
            'ajax' => array(
                'type' => 'POST', //request type
                'url' => Yii::app()->getController()->createUrl('provinceFromRegion'),
                'update' => '#MemberProfileForm_province_id',
                ))
        );
        ?>
        <?php echo $form->error($umodel, 'country_id'); ?>
    </div>

    <?php $get_province_id = isset($_GET['province_id']) ? (int) ($_GET['province_id']) : null ?>

    <?php if ($get_province_id === null) : ?>
    <div class="row">
        <?php echo $form->labelEx($umodel, 'province_id'); ?>	 
        <?php echo $form->dropDownList($umodel, 'province_id', Province::getProvinceFromRegion($umodel->region_id, false), array('options' => array($umodel->province_id => array('selected' => true)))); ?>
        <?php echo $form->error($umodel, 'province_id'); ?>
    </div>
    <?php else : ?>
        <?php echo $form->hiddenField($umodel, 'province_id', array('value' => $get_province_id)); ?>
    <?php endif; ?>
    <div class="row">
        <?php echo $form->labelEx($umodel, 'location'); ?>
        <?php echo $form->textField($umodel, 'location'); ?>
        <?php echo $form->error($umodel, 'location'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'postal_code'); ?>
        <?php echo $form->textField($umodel, 'postal_code'); ?>
        <?php echo $form->error($umodel, 'postal_code'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'adress'); ?>
        <?php echo $form->textField($umodel, 'adress'); ?>
        <?php echo $form->error($umodel, 'adress'); ?>
    </div>
    
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'phone'); ?>
        <?php echo $form->textField($umodel, 'phone'); ?>
        <?php echo $form->error($umodel, 'phone'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'fax'); ?>
        <?php echo $form->textField($umodel, 'fax'); ?>
        <?php echo $form->error($umodel, 'fax'); ?>
    </div>
    
    <div class="row">
        <?php echo $form->labelEx($umodel, 'mobile'); ?>
        <?php echo $form->textField($umodel, 'mobile'); ?>
        <?php echo $form->error($umodel, 'mobile'); ?>
    </div>
    
    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('Global', 'Save'), array('class' => 'bebutton')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->



<script type="text/javascript">
    $(function() {
        
        $("#delete_cat_copy").live("click",function() {
            $('#selected_copy option:selected').remove();
            return false;
        });
        $("#delete_cat").live("click",function() {
            $('#selected_categories option:selected').remove();
            return false;
        });
        
        $('#company-profile-form').submit(function() {
		$('#selected_copy').find('option').each(function() {
			$(this).attr('selected', 'selected');
		});
	});
    });   
</script>