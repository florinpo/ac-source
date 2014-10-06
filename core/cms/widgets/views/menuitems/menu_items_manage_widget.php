<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'menu-items-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'enableSorting' => false,
    'selectableRows' => 2,
    'summaryText' => Yii::t('Global', 'Displaying') . ' {start} - {end} ' . Yii::t('Global', 'in') . ' {count} ' . Yii::t('Global', 'results'),
    'pager' => array(
        'header' => Yii::t('Global', 'Go to page:'),
        'nextPageLabel' => Yii::t('Global', 'next'),
        'prevPageLabel' => Yii::t('Global', 'previous'),
        'firstPageLabel' => Yii::t('Global', 'First'),
        'lastPageLabel' => Yii::t('Global', 'Last'),
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
            //'value' => 'CHtml::encode("<span style=color:#999>".$data->getChildren($data->lft, $data->level)."</span>" . $data->name,array("' . app()->controller->id . '/admin?cat=$data->id&user_type=1"))',
            'value' => '"<span style=color:#999>".$data->getChildren($data->lft, $data->level)."</span>" . $data->name',
        ),
        array(
            'header' => 'Order',
            'name' => 'lft',
            'class' => 'cms.extensions.OrderColumnNested.OrderColumn',
        ),
        array(
            'name' => 'description',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->description',
        ),
        
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'buttons' => array
                (
                'update' => array
                    (
                    'label' => Yii::t('Global','Edit'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/update", array("menu"=>$data->menu_id, "id"=>$data->id))',
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
                    'label' => Yii::t('Global','Delete'),
                    'imageUrl' => false,
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
        var ids =  $.fn.yiiGridView.getSelection('menu-items-grid');
                // we have array, lets split them into a string separating
                // values with commas
               
               if (ids.length > 0) {
                    //if (confirm('asasa'+ids.length+'asasaa')) {
                     if (confirm('".t('cms','You are about to delete \'+ids.length+\' item(s) do you want to continue?')."')) {
                        // now just call the ajax
                        $.ajax({
                                url: '" . Yii::app()->createUrl('menuitems/deleteselected') . "',
                                data: {'ids':ids, 'parentId':'".$_GET['menu']."', 'YII_CSRF_TOKEN': '" .Yii::app()->getRequest()->getCsrfToken(). "'},
                                type: 'post',
                                success: function(data){
                                        $.fn.yiiGridView.update('menu-items-grid', {
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
