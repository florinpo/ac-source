
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
            'value' =>$model->recent_login,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Role'),
            'type' => 'raw',
            'value' => User::getStringRoles($model->user_id),
        ),
        
        array(
            'label' => Yii::t('AdminUser', 'Company position'),
            'type' => 'raw',
            'value' => UserCompanyProfile::getStringCposition($model->cprofile->companyposition),
        ),
        
        array(
            'label' => Yii::t('AdminUser', 'First name'),
            'type' => 'raw',
            'value' => $model->cprofile->firstname,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Last name'),
            'type' => 'raw',
            'value' => $model->cprofile->lastname,
        ),
        
        array(
            'label' => Yii::t('AdminUser', 'Send message'),
            'type' => 'raw',
            'value' => CHtml::link('Submit message', 'javascript:void()',array('submit'=>array('pmessage/compose'), 'params' => array('to' => $model->user_id), 'csrf'=>true)),
        ),
    ),
));
?>




<hr />

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
       
         array(
            'label' => Yii::t('AdminUser', 'Company name'),
            'type' => 'raw',
            'value' => $model->cprofile->companyname,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Company type'),
            'type' => 'raw',
            'value' => UserCompanyProfile::getStringCtype($model->cprofile->companytype),
        ),
        
        array(
            'label' => Yii::t('AdminUser', 'Categories'),
            'type'=>'raw',
            'value' => $model->getStringCategories(),
        ),
         array(
            'label' => Yii::t('AdminUser', 'V.A.T. Code'),
            'type' => 'raw',
            'value' => $model->cprofile->vat_code,
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
            'label' => Yii::t('AdminUser', 'Region'),
            'type' => 'raw',
            'value' => ($model->cprofile->region_id != null) ? Region::model()->findByPk($model->cprofile->region_id)->name : "",
        ),
       
        array(
            'label' => Yii::t('AdminUser', 'Province'),
            'type' => 'raw',
            'value' => ($model->cprofile->province_id != null) ? Province::model()->findByPk($model->cprofile->province_id)->name : "",
        ),
        array(
            'label' => Yii::t('AdminUser', 'Location'),
            'type' => 'raw',
            'value' => ($model->cprofile->location != '') ? $model->cprofile->location : "",
        ),
        
        array(
            'label' => Yii::t('AdminUser', 'Adress'),
            'type' => 'raw',
            'value' => $model->cprofile->adress,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Phone number'),
            'type' => 'raw',
            'value' => $model->cprofile->phone,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Fax'),
            'type' => 'raw',
            'value' => $model->cprofile->fax,
        ),
        array(
            'label' => Yii::t('AdminUser', 'Mobile'),
            'type' => 'raw',
            'value' => $model->cprofile->mobile,
        ),
    ),
));
?>
