<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'taxonomy-grid',
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
        array('name' => 'page_id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => '$data->page_id',
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => 'CHtml::link($data->name,array("' . app()->controller->id . '/view","id"=>$data->page_id))',
        ),
        //'layout',
        
        array(
            'name' => 'layout',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->layout',
            'filter' => GxcHelpers::getAvailableLayouts(true),
        ),
        
        
        array(
            'name' => 'lang',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => 'Language::convertLanguage($data->lang)',
            'filter' => CHtml::listData(Language::model()->findAll(), "lang_id", "lang_desc"),
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
                    'label' => Yii::t('AdminPage', 'Translate'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/create", array("guid"=>$data->guid))',
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
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/update", array("id"=>$data->page_id))',
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
