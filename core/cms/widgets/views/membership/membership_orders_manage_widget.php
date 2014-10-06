
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'id',
    'dataProvider' => $model->search(),
    'filter' => $model,
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
        array('name' => 'order_num',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            //'value' => '$data->order_num',
            'value' => 'CHtml::link($data->order_num, array("' . app()->controller->id . '/updateorder", "id"=>$data->id))',
        ),
        array('name' => 'membership_id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->product->title',
        ),
        array('name' => 'order_date',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => 'simple_date($data->order_date)',
            'filter' => false
        ),
        array('name' => 'payment_due',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => 'simple_date($data->payment_due)',
            'filter' => false
        ),
        array('name' => 'status',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->getStatus()',
            'filter' => ConstantDefine::getOrderStatus()
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'buttons' => array(
                'update' => array(
                    'label' => t('cms', 'Edit'),
                    'imageUrl' => false,
                    'url' => 'app()->createUrl("' . app()->controller->id . '/updateorder", array("id"=>$data->id))',
                )
            )
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{delete}',
            'buttons' => array(
                'delete' => array
                    (
                    'label' => t('cms', 'Delete'),
                    'imageUrl' => false,
                    'url' => 'app()->createUrl("' . app()->controller->id . '/deleteorder", array("id"=>$data->id))',
                )
            )
        )
    )
));
?>
