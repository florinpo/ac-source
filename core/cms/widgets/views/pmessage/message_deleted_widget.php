<?php $this->render('cmswidgets.views.notification'); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'message-deleted-grid',
    'dataProvider' => $model,
    'summaryText' => Yii::t('Global', 'Displaying') . ' {start} - {end} ' . Yii::t('Global', 'in') . ' {count} ' . Yii::t('Global', 'results'),
    'pager' => array(
        'header' => Yii::t('Global', 'Go to page:'),
        'nextPageLabel' => Yii::t('Global', 'next'),
        'prevPageLabel' => Yii::t('Global', 'previous'),
        'firstPageLabel' => Yii::t('Global', 'First'),
        'lastPageLabel' => Yii::t('Global', 'Last'),
        'pageSize' => Yii::app()->settings->get('system', 'page_size')
    ),
    'selectableRows' => '2',
    'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'value' => '$data->id',
            'id' => 'Id',
        ),
        array(
            'type' => 'raw',
            'header' => Yii::t('AdminNotification', 'From'),
            'htmlOptions' => array('class' => 'gridLeft'),
            'cssClassExpression' => 'PrivateMessage::deletedUnread($data->id)',
            'value' => 'PrivateMessage::activeSender($data->sender,$data->id)',
        ),
        array(
            'type' => 'raw',
            'header' => Yii::t('AdminNotification', 'Subject'),
            'htmlOptions' => array('class' => 'gridLeft'),
            'cssClassExpression' => 'PrivateMessage::deletedUnread($data->id)',
            'value' => 'CHtml::link($data->subject, app()->getController()->createUrl("view",array("mailbox"=>"recyclebox","id"=>$data->id)))',
        ),
        array(
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeftMax'),
            'cssClassExpression' => 'PrivateMessage::deletedUnread($data->id)',
            'header' => Yii::t('AdminNotification', 'Date'),
            'value' => 'niceDate($data->create_time)',
        ),
    ),
));
?>


<?php echo CHtml::button(t('cms', 'Delete Selected'),array('class'=>'deleteSelected-button'));?>

<?php
app()->clientScript->registerScript('delete-messages', "
$('.deleteSelected-button').click(function(){
        // get the ids
        var ids =  $.fn.yiiGridView.getSelection('message-deleted-grid');
                // we have array, lets split them into a string separating
                // values with commas
               
               if (ids.length > 0) {
                    //if (confirm('asasa'+ids.length+'asasaa')) {
                     if (confirm('".t('cms','You are about to remove \'+ids.length+\' item(s) do you want to continue?')."')) {
                        // now just call the ajax
                        $.ajax({
                                url: '" . Yii::app()->createUrl('pmessage/deleteselected') . "',
                                data: {'ids':ids, 'action':'deleted' ,'YII_CSRF_TOKEN': '" .Yii::app()->getRequest()->getCsrfToken(). "'},
                                type: 'post',
                                success: function(data){
                                        $.fn.yiiGridView.update('message-deleted-grid', {
                                                data: $(this).serialize()
                                        });
                                }
                        });
                    }
                }
                
        
        return false; // if you want to avoid default button action
});", CClientScript::POS_READY);
?>



