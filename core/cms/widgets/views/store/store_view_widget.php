
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        'name',
        array(
            'label' => Yii::t('cms', 'Store thumbnail'),
            'type' => 'image',
            'value' =>IMAGES_URL .'/img100/'.$model->image
        ),
        
        
        array(
            'label' => Yii::t('cms', 'Store description'),
            'type' => 'raw',
            'value' =>nl2br($model->description)
        ),
        
    ),
));
?>



