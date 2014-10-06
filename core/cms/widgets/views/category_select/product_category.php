<div class="close" style="text-align:right;margin-bottom:10px;float:right;">
    <span style="font-size: 11px;font-weight: bold;cursor:pointer;">Inchide fereastra</span>
    <span class="closeBox">x</span>
</div>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'category-form',
        'enableAjaxValidation' => true,
        //'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange'=>false,

            'afterValidate' => 'js:function(form,data,hasError){
                        if(!hasError){
                                $.ajax({
                                        "type":"POST",
                                       
                                        "url":"' . CHtml::normalizeUrl(array("categoryselect/selectedcategoriesprod")) . '",
                                        "data":{"id":send(), "YII_CSRF_TOKEN":"' . Yii::app()->getRequest()->getCsrfToken() . '"},
                                        "success":function(data){
                                            var $response=$(data).val();
                                            var list = [];
                                            if (!$("#listbox_categories").find("option[value="+$response+"]").length > 0) {
                                                $("#listbox_categories").append(data);
                                                $("#listbox_categories option").each(function (index) {
                                                    list.push(this.value);
                                                 });
                                                $("#ProductSaleCategoryForm_selected_categories").val(list);
                                            };
                                        },
                                        
                                 });
                                 
                                 $("#saveCats").click(function(){
                                    var catSelected = $("#listbox_categories");
                                    var catList = $("#listboxSelected", window.opener.document);
                                    //var catListCount =  $("#ProductSaleCategoryForm_selected_cats", window.opener.document);
                                    catList.find("option").remove();
                                    catSelected.children().each(function(){
                                        catList.append($("<option></option>").attr("value",$(this).val()).text($(this).text()));
                                    });
                                    $(".close").trigger("click");					
                                });
                         }
                        }'
        ),
            ));
    ?>

    <div class="clear"></div>

    <div class="row" style="250px; float:left;">
        <label for="domain_id"><?php echo t('cms', 'Select domain:'); ?></label>

        <?php
        echo $form->listBox($model, 'domain_id', ProductSaleCategoryList::getDomains(false), array(
            'size' => 10,
            'style' => 'width: 230px',
            'ajax' => array(
                'type' => 'POST',
                'url' => Yii::app()->createUrl('productsale/updatecategories'),
                'data' => array('domain_id' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->getRequest()->getCsrfToken()),
                'update' => '#ProductSaleCategoryForm_category_id',
            ),
            'options' => array($model->domain_id => array('selected' => true))
        ));
        ?>
        <?php echo $form->error($model, 'domain_id'); ?>
    </div>

    <div class="row" style="250px; float:left;">
        <label for="category_id"><?php echo t('cms', 'Select category:'); ?></label>
        <?php
        echo $form->listBox($model, 'category_id', array(), array('size' => 10, 'style' => 'width: 230px',
            'ajax' => array(
                'type' => 'POST',
                'url' => Yii::app()->createUrl('productsale/updatesubcategories'),
                'data' => array('category_id' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->getRequest()->getCsrfToken()),
                'update' => '#ProductSaleCategoryForm_subcategory_id',
                )));
        ?>
        <?php echo $form->error($model, 'category_id'); ?>
    </div>
    <div class="row" style="250px; float:left;">
        <label for="category_id"><?php echo t('cms', 'Select subcategory:'); ?></label>
        <?php
        echo $form->listBox($model, 'subcategory_id', array(), array('size' => 10, 'style' => 'width: 230px'));
        ?>
        <?php echo $form->error($model, 'subcategory_id'); ?>

    </div>

    <div class="clear"></div>

    <div class="row">
        <?php echo Chtml::submitButton(t('cms', 'Select Category'), array('id' => 'select_cat', 'name' => 'select-cat')); ?>
        <?php echo Chtml::button(t('cms', 'Delete'), array('id' => 'delete_cat')); ?>
    </div>
    
    <div class="row">
        <label for="selected_categories"><?php echo t('cms', 'Selected categories'); ?></label>
        <?php echo $form->hiddenField($model, 'selected_categories', array('type' => "hidden", 'value' => '')); ?>
        <?php
        echo Chtml::listBox('listbox_categories',array(), isset($product) ? ProductSale::getSelectedCategories($product->id) : array(), array(
            'size' => 5,
            'style' => 'width: 500px',
            'multiple' => 'multiple'
                )
        );
        ?>
        <?php echo $form->error($model, 'selected_categories'); ?>
    </div>

    <div class="row buttons">
        <?php echo Chtml::submitButton(t('cms', 'Done'), array('id' => 'saveCats', 'name' => 'save-cat')); ?>
    </div>

    <?php $this->endWidget(); ?>


</div><!-- form -->

<script type="text/javascript">
    function send()
    {
        var id;
        if ($("#ProductSaleCategoryForm_category_id").val() != null && $("#ProductSaleCategoryForm_subcategory_id").val() == null){
            id = $("#ProductSaleCategoryForm_category_id").val();
        }

        else if ($("#ProductSaleCategoryForm_category_id").val() != null && $("#ProductSaleCategoryForm_subcategory_id").val() !== null){
            id = $("#ProductSaleCategoryForm_subcategory_id").val();
        }
        return id;
    }
    
    $(function() {
        $("#delete_cat").live("click",function() {
            var selected = $('#listbox_categories option:selected');
            selected.remove();
            var list =[];
            $("#listbox_categories option").each(function (index) {
                list.push(this.value);
            });
            $("#ProductSaleCategoryForm_selected_categories").val(list);
            
            return false;
        });
        
        $(".close").click(function(){
            window.close();
        });
        
    })
</script>