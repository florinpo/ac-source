<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'summaryText' => Yii::t('cms', 'Displaying') . ' {start} - {end} ' . Yii::t('cms', 'in') . ' {count} ' . Yii::t('cms', 'results'),
    'pager' => array(
        'header' => Yii::t('cms', 'Go to page:'),
        'nextPageLabel' => Yii::t('cms', 'next'),
        'prevPageLabel' => Yii::t('cms', 'previous'),
        'firstPageLabel' => Yii::t('cms', 'First'),
        'lastPageLabel' => Yii::t('cms', 'Last'),
        'pageSize' => Yii::app()->settings->get('system', 'page_size')
    ),
    'columns' => array(
        array('name' => 'user_id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => '$data->user_id',
        ),
        array(
            'name' => 'username',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => 'CHtml::link($data->username,array("' . app()->controller->id . '/view","id"=>$data->user_id))',
        ),
        array(
            'name' => 'display_name',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->display_name',
        ),
        array(
            'name' => 'email',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->email',
        ),
        array(
            'name' => 'status',
            'type' => 'image',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => 'User::convertUserState($data)',
            'filter' => false,
            
        ),
        array(
            'name' => 'role_sort',
            'type' => 'raw',
            'value' => 'User::getStringRoles($data->user_id)',
            'filter' => false,
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'buttons' => array
                (
                'view' => array
                    (
                    'label' => Yii::t('cms','View'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/view", array("id"=>$data->user_id))',
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
                    'label' => Yii::t('cms','Edit'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/update", array("id"=>$data->user_id))',
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
                    'label' => Yii::t('cms','Delete'),
                    'imageUrl' => false,
                ),
            ),
        ),
    ),
));
?>
