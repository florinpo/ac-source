<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'category-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'enableSorting' => false,
    'selectableRows' => 2,
    'summaryText' => t('cms', 'Displaying') . ' {start} - {end} ' . t('cms', 'in') . ' {count} ' . t('cms', 'results'),
    'pager' => array(
        'header' => t('cms', 'Go to page:'),
        'nextPageLabel' => t('cms', 'next'),
        'prevPageLabel' => t('cms', 'previous'),
        'firstPageLabel' => t('cms', 'First'),
        'lastPageLabel' => t('cms', 'Last'),
        'pageSize' => Yii::app()->settings->get('system', 'page_size')
    ),
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'value' => '$data->id',
            'id' => 'Id',
        ),
        
        array(
            'name' => 'name',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            //'value' => '"<span style=color:#999>".$data->getChildren($data->lft, $data->level)."</span>" . $data->name',
            'value' => 'CHtml::link("<span style=color:#999>".$data->getChildren($data->lft, $data->level)."</span>" . $data->name,array("' . app()->controller->id . '/admin?cat=$data->id&user_type=1"))',
        ),
       
        array(
            'name' => 'lang',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => 'Language::convertLanguage($data->lang)',
            'filter' => CHtml::listData(Language::model()->findAll(), "lang_id", "lang_desc"),
        ),
        array(
            'header' => Yii::t('AdminCategory', 'ProductSales'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => 'ProductSaleCategoryList::countCatProducts($data->id)',
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{translate}',
            'visible' => Yii::app()->settings->get('system', 'language_number') > 1,
            'buttons' => array
                (
                'translate' => array
                    (
                    'label' => t('cms', 'Translate'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/createcategory", array("guid"=>$data->guid))',
                ),
            ),
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'buttons' => array
                (
                'update' => array
                    (
                    'label' => t('cms', 'Edit'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/updatecategory", array("id"=>$data->id))',
                ),
            ),
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{delete}',
            'buttons' => array
                (
                'delete' => array
                    (
                    'label' => t('cms', 'Delete'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/deletecategory", array("id"=>$data->id))',
                ),
            ),
        ),
    ),
));
?>

<?php
Yii::app()->clientScript->registerScript('Delete', "
$('.deleteSelected-button').click(function(){
        // get the ids
        var ids =  $.fn.yiiGridView.getSelection('category-grid');
                // we have array, lets split them into a string separating
                // values with commas
               
               if (ids.length > 0) {
                    //if (confirm('asasa'+ids.length+'asasaa')) {
                     if (confirm('".t('cms','You are about to delete \'+ids.length+\' item(s) do you want to continue?')."')) {
                        // now just call the ajax
                        $.ajax({
                                url: '" . Yii::app()->createUrl('productsale/deleteselected') . "',
                                data: {'ids':ids, 'YII_CSRF_TOKEN': '" .Yii::app()->getRequest()->getCsrfToken(). "'},
                                type: 'post',
                                success: function(data){
                                        $.fn.yiiGridView.update('category-grid', {
                                                data: $(this).serialize()
                                        });
                                }
                        });
                    }
                }
                
        
        return false; // if you want to avoid default button action
});", CClientScript::POS_READY);
?>

<?php echo CHtml::button(t('cms', 'Delete Selected'),array('class'=>'deleteSelected-button'));?>
