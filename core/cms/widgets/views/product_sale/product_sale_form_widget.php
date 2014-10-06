<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>


    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'product-form',
        'enableAjaxValidation' => true,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
            ));
    ?>
    <?php if (isset($product)): ?>
        <?php if (isset($product->pimages)): ?>
            <ul class="items-100-form">
                <?php foreach ($product->pimages as $image): ?>
                    <li>
                        <?php if ($image->path != null && $image->path != ''): ?>

                            <?php
                            $class = 'thumbnail';
                            if ($product->main_image == $image->id) {
                                $class = 'thumbnail selected';
                            }
                            ?>
                            <img class="<?php echo $class; ?>" src="<?php echo IMAGES_URL . '/img100/' . $image->path; ?>"/>

                            <?php
                            echo CHtml::ajaxLink(
                                    t('cms', 'Delete image'), Yii::app()->createUrl('productsale/deleteimg'), array(
                                'type' => 'POST',
                                'data' => array('img_id' => $image->id, 'YII_CSRF_TOKEN' => Yii::app()->getRequest()->getCsrfToken()),
                                //'update' => '#product_img',
                                'success' => "function(data) {
                                    if(data==1){
                                        window.parent.location.href = '" . Yii::app()->getController()->createUrl('update', array('id' => $product->id)) . "';
                                    } else {
                                        alert(" . t('cms', '"Error while deleting the image"') . ");
                                    }
                                }"), array('href' => 'javascript:void(0)', 'confirm' => t('cms', 'Are you sure you want to delete this image?'),
                            ));
                            ?>


                            <?php
                            if ($product->imagescount > 1) {
                                echo CHtml::ajaxLink(
                                        Yii::t('FrontendProduct', 'Main image'), Yii::app()->createUrl('productsale/mainimg'), array(
                                    'type' => 'POST',
                                    'data' => array('img_id' => $image->id, 'prod_id' => $product->id, 'YII_CSRF_TOKEN' => Yii::app()->getRequest()->getCsrfToken()),
                                    //'update' => '#product_img',
                                    'success' => "function(data) {
                if(data==1){
                    window.parent.location.href = '" . Yii::app()->getController()->createUrl('update', array('id' => $product->id)) . "';
                } else {
                    alert(" . Yii::t('FrontendProduct', '"Error while updating the image"') . ");
                }
            }"), array(
                                    'href' => 'javascript:void(0)',
                                ));
                            }
                            ?> 

                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($product->imagescount < 3): ?>
            <?php
            $maxFiles = 3;
            if ($product->imagescount <= $maxFiles) {
                $maxFiles = $maxFiles - $product->imagescount;
            }
            ?>

            <div class="row">
                <?php echo $form->labelEx($model, 'uploadimg'); ?>
                <?php
                $this->widget('cms.extensions.xupload.XUpload', array(
                    'url' => Yii::app()->createUrl("/productsale/upload"),
                    //our XUploadForm
                    'model' => $files,
                    //We set this for the widget to be able to target our own form
                    'htmlOptions' => array('id' => 'product-form'),
                    'attribute' => 'uploadimg',
                    'multiple' => true,
                    //Note that we are using a custom view for our widget
                    //Thats becase the default widget includes the 'form' 
                    //which we don't want here
                    'formView' => 'cmswidgets.views.product_sale.upload_form',
                    'options' => array(
                        'maxNumberOfFiles' => isset($maxFiles) ? $maxFiles : 3,
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

    <?php else: ?>

        <div class="row">
            <?php echo $form->labelEx($model, 'uploadimg'); ?>
            <?php
            $this->widget('cms.extensions.xupload.XUpload', array(
                'url' => Yii::app()->createUrl("/productsale/upload"),
                //our XUploadForm
                'model' => $files,
                //We set this for the widget to be able to target our own form
                'htmlOptions' => array('id' => 'product-form'),
                'attribute' => 'uploadimg',
                'multiple' => true,
                //Note that we are using a custom view for our widget
                //Thats becase the default widget includes the 'form' 
                //which we don't want here
                'formView' => 'cmswidgets.views.product_sale.upload_form',
                'options' => array(
                    'maxNumberOfFiles' => isset($maxFiles) ? $maxFiles : 3,
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
        <?php echo $form->labelEx($model, 'name'); ?>
        <?php echo $form->textField($model, 'name', array('class' => 'userform', 'autoComplete' => 'off')); ?>
        <?php echo $form->error($model, 'name'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'model'); ?>
        <?php echo $form->textField($model, 'model', array('class' => 'userform', 'autoComplete' => 'off')); ?>
        <?php echo $form->error($model, 'model'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::button('Delete', array('id' => 'delete_cat_copy')); ?>
        <?php if (isset($product)): ?>
            <?php
            echo CHtml::button(t('cms', 'Add Category'), array(
                'name' => 'addCategory',
                //'onclick' => "window.open ('".Yii::app()->controller->createUrl('index', array('slug' => 'company-profile-categories'))."', 'company_profile_categories', config='height=400, width=1000, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no')",
                'onclick' => "popWindow('" . Yii::app()->createUrl('categoryselect/productcategory', array('id' => $product->id)) . "','product_categories',1100,500,1,1)"
            ));
            ?>
        <?php else: ?>
            <?php
            echo CHtml::button(t('cms', 'Add Category'), array(
                'name' => 'addCategory',
                //'onclick' => "window.open ('".Yii::app()->controller->createUrl('index', array('slug' => 'company-profile-categories'))."', 'company_profile_categories', config='height=400, width=1000, toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, directories=no, status=no')",
                'onclick' => "popWindow('" . Yii::app()->createUrl('categoryselect/productcategory', array()) . "','product_categories',1100,500,1,1)"
            ));
            ?>
        <?php endif; ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'selected_cats'); ?>
        <?php echo $form->hiddenField($model, 'selected_cats', array('type' => "hidden")); ?>
        <?php
        echo Chtml::listBox('listboxSelected', array(), isset($product) ? ProductSale::getSelectedCategories($product->id) : array(), array('size' => 5, 'multiple' => 'multiple', 'style' => 'width: 400px')
        );
        ?>
        <?php echo $form->error($model, 'selected_cats'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'tags'); ?>
        <?php echo $form->textField($model, 'tags', array()); ?>
        <?php echo $form->error($model, 'tags'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'price'); ?>
        <?php echo $form->textField($model, 'price', array('autoComplete' => 'asasaa', 'style' => 'width:50px')); ?>
        <?php echo $form->dropDownList($model, 'currency', ConstantDefine::getPriceCurrency(), array('style' => 'padding:4px')); ?>
        <span> I.V.A. incluso</span>
        <?php echo $form->error($model, 'price'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'description'); ?>
        <?php echo $form->textArea($model, 'description', array('autoComplete' => 'off', 'style' => 'height:120px; width:400px')); ?>
        <?php echo $form->error($model, 'description'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'status'); ?>
        <?php echo $form->dropDownList($model, 'status', ConstantDefine::getProductStatus()); ?>
        <?php echo $form->error($model, 'status'); ?>                                  
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(Yii::t('Global', 'Save'), array('class' => 'bebutton')); ?>
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
            $("#ProductSaleForm_selected_cats").val(list);
            return false;
        });
        
        
        $('#product-form').submit(function() {
            var list = [];
            $('#listboxSelected').find('option').each(function() {
                list.push(this.value);
            });
            $("#ProductSaleForm_selected_cats").val(list);
        });
    });   
</script>

<?php
app()->clientScript->registerScriptFile(bu() . '/js/main-frame.js', CClientScript::POS_HEAD);
?>

