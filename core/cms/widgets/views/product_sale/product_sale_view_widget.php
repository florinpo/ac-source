<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => array(
        
        'name', 
        'model',
       
        array(
            'label' => Yii::t('AdminProductSale', 'Product images'),
            'type' => 'html',
            'value' =>$model->getListImages(100),
        ),
        
         array(
            'name' => 'status',
            'type' => 'image',
            'value' => ProductSale::convertProductState($model),
        ),
        
        array(
            'label' => Yii::t('AdminProductSale', 'Company owner'),
            'type' => 'raw',
            'value' => UserCompanyProfile::model()->findByAttributes(array("companyId"=>$model->companyId))->companyname,
        ),
        
        array(
            'label' => Yii::t('AdminProductSale', 'ProductSale price'),
            'type' => 'raw',
            'value' =>$model->price
        ),
        
        array(
            'label' => Yii::t('AdminProductSale', 'ProductSale description'),
            'type' => 'raw',
            'value' =>nl2br($model->description)
        ),
        
        array(
           'label' => Yii::t('AdminProductSale', 'Created at'),
            'type' => 'raw',
            'value' => date("Y-m-d H:i:s", $model->create_time),
        ),
        
        array(
           'label' => Yii::t('AdminProductSale', 'Last update'),
            'type' => 'raw',
            'value' => date("Y-m-d H:i:s", $model->update_time),
        ),

    ),
));
?>