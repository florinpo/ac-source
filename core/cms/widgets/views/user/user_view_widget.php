<?php

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'user_id',
        'username',
        'email',
        array(
            'name' => 'status',
            'type' => 'image',
            'value' => User::convertUserState($model),
        ),
        array(
            'name' => 'created_time',
            'type' => 'raw',
            'value' => complex_date($model->create_time),
        ),
        array(
            'name' => 'updated_time',
            'type' => 'raw',
            'value' => complex_date($model->update_time),
        ),
        array(
            'name' => 'recent_login',
            'type' => 'raw',
            'value' => complex_date($model->recent_login),
        ),
        array(
            'label' => Yii::t('AdminUser', 'Role'),
            'type' => 'raw',
            'value' => User::getStringRoles($model->user_id),
        ),
        array(
            'label' => Yii::t('AdminUser', 'Membership'),
            'type' => 'raw',
            'value' => (isset($model->membership->membership_id)) ? User::getStringMemberships($model->membership->membership_id) : "No",
        ),
        array(
            'label' => Yii::t('AdminUser', 'Membership Starting Date'),
            'type' => 'raw',
            'value' => (isset($model->membership->payment_date)) ? date("Y-m-d H:i:s", $model->membership->payment_date) : '',
        ),
        array(
            'label' => Yii::t('AdminUser', 'Membership End Date'),
            'type' => 'raw',
            'value' => (isset($model->membership->end_date)) ? date("Y-m-d H:i:s", $model->membership->end_date) : '',
        ),
        array(
            'label' => Yii::t('AdminUser', 'First Name'),
            'type' => 'raw',
            'value' => $model->profile->firstname,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Last Name'),
            'type' => 'raw',
            'value' => $model->profile->lastname,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Region'),
            'type' => 'raw',
            'value' => ($model->profile->region_id != null) ? Region::model()->findByPk($model->profile->region_id)->name : "",
        ),
        
        array(
            'label' => Yii::t('AdminUser', 'Province'),
            'type' => 'raw',
            'value' => ($model->profile->province_id != null) ? Province::model()->findByPk($model->profile->province_id)->name : "",
        ),
        array(
            'label' => Yii::t('AdminUser', 'Location'),
            'type' => 'raw',
            'value' => ($model->profile->location != null) ? $model->profile->location : "",
        ),
        
        array(
            'label' => Yii::t('AdminUser', 'Adress'),
            'type' => 'raw',
            'value' => $model->profile->adress,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Phone number'),
            'type' => 'raw',
            'value' => $model->profile->phone,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Send message'),
            'type' => 'raw',
            'value' => CHtml::link('Submit message', array('pmessage/compose', 'to' => $model->user_id)),
        ),
    ),
));
?>
