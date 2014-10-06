
<div class="search-form">
        <?php echo CHtml::beginForm(''); ?>
        <div class="row">
            <?php
            echo CHtml::activeTextField($search, 'keyword', array('style' => 'width:300px'));
            ?>
            <?php echo CHtml::SubmitButton(t('cms','Start Search')); ?>
        </div>
        <?php echo CHtml::endForm(); ?>
   
</div>

<div id="sorting-form">
        <?php
           echo CHtml::form();
           echo CHtml::label('Sort by: ', 'SortForm[option]');
           echo CHtml::dropDownList('SortForm[option]', $current, $this->getStringStype(),
            array(
                'submit'=>'',
            )
        ); 
        echo CHtml::endForm();
        ?>
  </div>


<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'product-grid',
    'dataProvider' => $model,
    'enableSorting' => false,
    'ajaxUpdate'=>false,
    'summaryText' => t('cms', 'Displaying') . ' {start} - {end} ' . t('cms', 'in') . (!empty($_GET['q']) ? ' '.$total_found.' ' : ' {count} ')  . t('cms', 'results'),
    'pager' => array(
        'header' => t('cms', 'Go to page:'),
        'nextPageLabel' => t('cms', 'next'),
        'prevPageLabel' => t('cms', 'previous'),
        'firstPageLabel' => t('cms', 'First'),
        'lastPageLabel' => t('cms', 'Last'),
        //'pageSize' => Yii::app()->settings->get('system', 'page_size')
    ),
    'columns' => array(
        array(
            'name' => 'name',
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeft'),
            'value' => 'CHtml::link($data->name,array("' . app()->controller->id . '/view","id"=>$data->id))',
        ),
        array(
            'header' => t('cms', 'Image'),
            'name' => 'image',
            'type' => 'html',
            'value' => '$data->selectedImage(80)',
        ),
        array(
            'name' => 'status',
            'type' => 'image',
            'htmlOptions' => array('class' => 'gridmaxwidth'),
            'value' => 'ProductSale::convertProductState($data)',
            'filter' => false,
        ),
        array(
            'header' => t('cms', 'Company'),
            'type' => 'raw',
            'value' => '(isset($data->company)) ? CHtml::link(UserCompanyProfile::model()->findByAttributes(array("companyId"=>$data->companyId))->companyname,array("company/view","id"=>$data->companyId)): ""',
        ),
        array
            (
            'class' => 'CButtonColumn',
            'template' => '{view}',
            'buttons' => array
                (
                'view' => array
                    (
                    'label' => t('cms', 'View'),
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("' . app()->controller->id . '/view", array("id"=>$data->id))',
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
