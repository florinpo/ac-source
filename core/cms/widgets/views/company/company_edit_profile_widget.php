<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'company-profile-form',
        'enableAjaxValidation' => true,
            ));
    ?>


    <?php echo $form->errorSummary($cmodel); ?>


    <?php if ($profile->logo != null && $profile->logo != ''): ?>
        <img src="<?php echo IMAGES_URL . '/img100/' . $profile->logo; ?>"/>
        <?php
        echo CHtml::ajaxLink(
                Yii::t('FrontendUser', 'Delete image'), $this->getController()->createUrl('deleteimg'), array(
            'type' => 'POST',
            'data' => array('id' => $profile->id, 'deleteImg' => 'true', 'YII_CSRF_TOKEN' => Yii::app()->getRequest()->getCsrfToken()),
            //'update' => '#product_img',
            'success' => "function(data) {
                if(data==1){
                    window.parent.location.href = '" . $this->getController()->createUrl('companyprofile', array('id' => $user->user_id)) . "';
                } else {
                    alert(" . Yii::t('FrontendUser', '"Error while deleting the image"') . ");
                }
            }"), array(
            'href' => 'javascript:void(0)',
            'confirm' => Yii::t('FrontendUser', 'Are you sure you want to delete this image?')));
        ?> 

    <?php else: ?>


        <div class="row">
            <?php echo $form->labelEx($cmodel, 'uploadimg'); ?>
            <?php
            $this->widget('cms.extensions.xupload.XUpload', array(
                'url' => Yii::app()->createUrl("/company/upload"),
                //our XUploadForm
                'model' => $files,
                //We set this for the widget to be able to target our own form
                'htmlOptions' => array('id' => 'company-profile-form'),
                'attribute' => 'uploadimg',
                'multiple' => false,
                //Note that we are using a custom view for our widget
                //Thats becase the default widget includes the 'form' 
                //which we don't want here
                'formView' => 'cmswidgets.views.company.upload_form',
                'options' => array(
                    'maxFileSize' => ConstantDefine::UPLOAD_MAX_SIZE,
                    'minFileSize' => ConstantDefine::UPLOAD_MIN_SIZE,
                    'autoUpload' => true,
                    'sequentialUploads' => true,
                    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                    'completed' => 'js:function (event, files, index, xhr, handler, callBack) {
                    //$("tr.template-download").fadeOut("fast");
                    $("button.delete, input:checkbox").removeClass("hidden");
                }')
                    )
            );
            ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php echo $form->labelEx($cmodel, 'services'); ?>
        <?php echo $form->textArea($cmodel, 'services', array('autoComplete' => 'off', 'style' => 'height:80px; width:400px')); ?>
        <?php echo $form->error($cmodel, 'services'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::button(t('cms', 'Delete'), array('id' => 'delete_cat_copy')); ?>
        <?php
        echo CHtml::button(t('cms', 'Add Category'), array(
            'name' => 'addCategory',
            'onclick' => "popWindow('" . Yii::app()->createUrl('categoryselect/companycategory', array('id' => $user->user_id)) . "','company_profile_categories',1100,500,1,1)"
        ));
        ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($cmodel, 'selected_cats'); ?>
        <?php echo $form->hiddenField($cmodel, 'selected_cats', array('type' => "hidden")); ?>
        <?php
        echo Chtml::listBox('listboxSelected', array(), $user->getSelectedCategories(false), array('size' => 5, 'multiple' => 'multiple', 'style' => 'width: 400px')
        );
        ?>
        <?php echo $form->error($cmodel, 'selected_cats'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($cmodel, 'marketplace'); ?>
        <?php
        echo $form->dropDownList($cmodel, 'marketplace', array(
            '1' => Yii::t('FrontendUser', 'Italy'),
            '2' => Yii::t('FrontendUser', 'Vest Europe'),
            '3' => Yii::t('FrontendUser', 'East/Central Europe'),
            '4' => Yii::t('FrontendUser', 'Africa'),
            '5' => Yii::t('FrontendUser', 'North America'),
            '6' => Yii::t('FrontendUser', 'Sud America'),
            '7' => Yii::t('FrontendUser', 'Asia'),
            '7' => Yii::t('FrontendUser', 'Oceania'),
                ), array('empty' => Yii::t('FrontendUser', '')));
        ?>
        <?php echo $form->error($cmodel, 'marketplace'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($cmodel, 'certificate'); ?>
        <?php echo $form->textField($cmodel, 'certificate', array('class' => 'userform', 'autoComplete' => 'off')); ?>
        <?php echo $form->error($cmodel, 'certificate'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($cmodel, 'description'); ?>
        <?php echo $form->textArea($cmodel, 'description', array('autoComplete' => 'off', 'style' => 'height:120px; width:400px')); ?>
        <?php echo $form->error($cmodel, 'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($cmodel, 'website'); ?>
        <?php echo $form->textField($cmodel, 'website'); ?>
        <?php echo $form->error($cmodel, 'website'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('Global', 'Save'), array('class' => 'bebutton', 'id' => 'sub')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->



<script type="text/javascript">
    $(function() {
        $("#delete_cat_copy").live("click",function() {
            var selected = $('#listboxSelected option:selected');
            selected.remove();
            var list =[];
            
            $("#listboxSelected option").each(function (index) {
                list.push(this.value);
            });
            $("#UserCompanyProfileForm_selected_cats").val(list);
            return false;
        });
        
        
        $('#company-profile-form').submit(function() {
            var list = [];
            $('#listboxSelected').find('option').each(function() {
                list.push(this.value);
            });
            $("#UserCompanyProfileForm_selected_cats").val(list);
        });
    });   
</script>

<?php
app()->clientScript->registerScriptFile(bu() . '/js/main-frame.js', CClientScript::POS_HEAD);
?>

