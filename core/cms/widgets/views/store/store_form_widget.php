<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'store-form',
        'enableAjaxValidation' => true,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>

    <?php if ($model->image != null && $model->image != ''): ?>
        <img src="<?php echo IMAGES_URL . '/img100/' . $model->image; ?>"/>
        <?php
        echo CHtml::ajaxLink(
                Yii::t('FrontendUser', 'Delete image'), $this->getController()->createUrl('deleteimg'), array(
            'type' => 'POST',
            'data' => array('id' => $model->id, 'deleteImg' => 'true', 'YII_CSRF_TOKEN' => Yii::app()->getRequest()->getCsrfToken()),
            //'update' => '#product_img',
            'success' => "function(data) {
                if(data==1){
                    window.parent.location.href = '" . $this->getController()->createUrl('update', array('id' => $model->id)) . "';
                } else {
                    alert(" . Yii::t('FrontendUser', '"Error while deleting the image"') . ");
                }
            }"), array(
            'href' => 'javascript:void(0)',
            'confirm' => Yii::t('FrontendUser', 'Are you sure you want to delete this image?')));
        ?> 

    <?php else: ?>

        <div class="row">
            <?php echo $form->labelEx($model, 'uploadimg'); ?>
            <?php
            $this->widget('cms.extensions.xupload.XUpload', array(
                'url' => $this->getController()->createUrl("store/upload"),
                //our XUploadForm
                'model' => $photos,
                //We set this for the widget to be able to target our own form
                'htmlOptions' => array('id' => 'store-form'),
                'attribute' => 'uploadimg',
                'multiple' => false,
                //Note that we are using a custom view for our widget
                //Thats becase the default widget includes the 'form' 
                //which we don't want here
                'formView' => 'cmswidgets.views.store.upload_form',
                'options' => array(
                    'autoUpload' => true,
                    'sequentialUploads' => true,
                    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                    'completed' => 'js:function (event, files, index, xhr, handler, callBack) {
                    //$("tr.template-download").fadeOut("fast");
                }'
                    ))
            );
            ?>
        </div>

    <?php endif; ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name'); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <?php echo $form->labelEx($model, 'description'); ?>
    <?php echo $form->textArea($model, 'description', array('autoComplete' => 'off', 'style' => 'height:120px; width:400px')); ?>
    <?php echo $form->error($model, 'description'); ?>
</div>

<div class="row buttons">
    <?php echo CHtml::submitButton(t('cms','Save'), array('class' => 'bebutton')); ?>
</div>

<?php $this->endWidget(); ?>


</div><!-- form -->






