<?php


//$criteria->compare('id', $this->id, true);
//        $criteria->compare('company_id', $this->company_id, true);
//        $criteria->compare('product_id', $this->product_id, true);
//        $criteria->compare('product_type', $this->product_type, true);
//        $criteria->compare('last_name', $this->last_name, true);
//        $criteria->compare('first_name', $this->first_name, true);
//        $criteria->compare('company_name', $this->company_name, true);
//        $criteria->compare('company_position', $this->company_position);
//        $criteria->compare('vat_code', $this->vat_code, true);
//        $criteria->compare('bank_name', $this->bank_name, true);
//        $criteria->compare('bank_number', $this->bank_number, true);
//        $criteria->compare('region_id', $this->region_id);
//        $criteria->compare('province_id', $this->province_id);
//        $criteria->compare('location', $this->location, true);
//        $criteria->compare('adress', $this->adress, true);
//        $criteria->compare('postal_code', $this->postal_code, true);
//        $criteria->compare('phone', $this->phone, true);
//        $criteria->compare('fax', $this->fax, true);
//        $criteria->compare('mobile', $this->mobile, true);

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
        array('name' => 'id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => '$data->id',
        ),
        array('name' => 'company_name',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->company_name',
        ),
        array('name' => 'product_type',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->getProductType()',
        ),
        array('name' => 'product_id',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => '$data->getProductName()',
        ),
        array('name' => 'order_date',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => 'simple_date($data->order_date)',
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{update}',
            'buttons' => array
                (
                'update' => array(
                    'label' => t('cms', 'Edit'),
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
                    'label' => t('cms', 'Delete'),
                    'imageUrl' => false,
                ),
            ),
        ),
    ),
));
?>
