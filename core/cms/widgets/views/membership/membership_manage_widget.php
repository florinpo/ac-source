<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'id',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'summaryText' => t('cms','Displaying') . ' {start} - {end} ' .t('cms','in') . ' {count} ' . t('cms','results'),
    'pager' => array(
        'header' => t('cms', 'Go to page:'),
        'nextPageLabel' => t('cms', 'next'),
        'prevPageLabel' => t('cms', 'previous'),
        'firstPageLabel' => t('cms', 'First'),
        'lastPageLabel' => t('cms', 'Last'),
        'pageSize' => Yii::app()->settings->get('system', 'page_size')
    ),
    'columns' => array(
        array('name' => 'id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => '$data->id',
        ),
        array('name' => 'title',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->title',
        ),
        array(
            'name' => 'items_num',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->items_num',
        ),
        array(
            'name' => 'rolename',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->rolename',
        ),
        array(
            'name' => 'price',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->price',
        ),
        array(
            'header'=>t('cms','Duration'),
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->getDuration()',
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'buttons' => array
                (
                'update' => array (
                    'label' => t('cms','Edit'),
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
                    'label' => t('cms','Delete'),
                    'imageUrl' => false,
                ),
            ),
        ),
    ),
));
?>
