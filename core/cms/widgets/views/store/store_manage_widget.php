<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'store-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
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
         array('name' => 'id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => '$data->id',
        ),
        
        array(
            'header' => Yii::t('CompanyStore', 'Image'),
            'name' => 'image',
            'type' => 'html',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => '(!empty($data->image) && file_exists(IMAGES_FOLDER . "/" . "img80" . "/" . $data->image)) ? CHtml::image(IMAGES_URL . "/img80/" . $data->image,"",array("style"=>"width:80px; height:80px")): CHtml::image(IMAGES_URL . "/default/product-default-100.jpg","",array("style"=>"width:80px; height:80px"))',
            'filter' => false
        ),
        
        array(
            'name' => 'name',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => 'CHtml::link($data->name,array("' . app()->controller->id . '/view","id"=>$data->id))',
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'buttons' => array
                (
                'view' => array
                    (
                    'label' => Yii::t('Global', 'View'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/view", array("id"=>$data->id))',
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
                    'label' => Yii::t('Global', 'Edit'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/update", array("id"=>$data->id))',
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
                    'label' => Yii::t('Global', 'Delete'),
                    'imageUrl' => false,
                ),
            ),
        ),
    ),
));
?>
