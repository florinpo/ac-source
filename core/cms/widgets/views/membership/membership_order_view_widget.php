<?php

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'order_num',
        array(
            'label' => t('cms', 'Purchased item'),
            'value' => $model->product->title
        ),
        array(
            'name' => 'status',
            'value' => $model->getStatus()
        ),
        array(
            'name' => 'order_date',
            'value' => simple_date($model->order_date)
        ),
        array(
            'name' => 'end_date',
            'value' =>simple_date($model->end_date)
        ),
        array(
            'name' => 'payment_due',
            'value' => simple_date($model->payment_due)
        ),
        array(
            'name' => 'payment_date',
            'value' => !empty($model->payment_date) ? simple_date($model->payment_date) : '-'
        ),
        array(
            'name' => 'Username',
            'type' => 'raw',
            'value' => CHtml::link($model->user->username, array('company/view', 'id'=>$model->company_id))
        ),
        array(
            'label' => t('cms', 'Company'),
            'value' => $model->payment->company_name
        ),
        array(
            'label' => t('cms', 'Represented by'),
            'value' => $model->payment->first_name. ' '.$model->payment->last_name
        ),
        array(
            'label' => t('cms', 'Region'),
            'value' => Region::model()->findByPk($model->payment->region_id)->name
        ),
        array(
            'label' => t('cms', 'Province'),
            'value' => Province::model()->findByPk($model->payment->province_id)->name
        ),
        array(
            'label' => t('cms', 'Location'),
            'value' => $model->payment->location
        ),
        array(
            'label' => t('cms', 'Adress'),
            'value' => $model->payment->adress
        ),
        array(
            'label' => t('cms', 'Phone'),
            'value' => $model->payment->phone
        ),
         array(
            'label' => t('cms', 'Email'),
            'value' => $model->payment->email
        ),
        array(
            'label' => t('cms', 'Fax'),
            'value' => $model->payment->fax
        ),
        array(
            'label' => t('cms', 'Mobile'),
            'value' => $model->payment->mobile
        ),
    ),
));
?>
