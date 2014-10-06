
<?php $this->render('cmswidgets.views.notification'); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'message-sent-grid',
    'dataProvider' => $model,
    'summaryText' => t('cms', 'Displaying') . ' {start} - {end} ' . t('cms', 'in') . ' {count} ' . t('cms', 'results'),
    'pager' => array(
        'header' => t('cms', 'Go to page:'),
        'nextPageLabel' => t('cms', 'next'),
        'prevPageLabel' => t('cms', 'previous'),
        'firstPageLabel' => t('cms', 'First'),
        'lastPageLabel' => t('cms', 'Last'),
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
            'header' => t('cms', 'Sent to'),
            'htmlOptions' => array('class' => 'gridLeft'),
            'type' => 'raw',
            'value' => 'PrivateMessage::activeReceiver($data->receiver,$data->id)',
        ),
        array(
            'header' => t('cms', 'Subject'),
            'htmlOptions' => array('class' => 'gridLeft'),
            'type' => 'raw',
            'value' => 'CHtml::link($data->subject, app()->getController()->createUrl("view",array("mailbox"=>"outbox","id"=>$data->id)))',
        ),
        array(
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeftMax'),
            'header' => t('cms', 'Date'),
            'value' => 'niceDate($data->create_time)',
        ),
    ),
));
?>

<?php echo CHtml::button(Yii::t('AdminNotification', 'Delete Selected'),array('class'=>'deleteSelected-button'));?>

<?php
app()->clientScript->registerScript('delete-messages', "
$('.deleteSelected-button').click(function(){
        // get the ids
        var ids =  $.fn.yiiGridView.getSelection('message-sent-grid');
                // we have array, lets split them into a string separating
                // values with commas
               
               if (ids.length > 0) {
                     if (confirm('".t('cms','You are about to move in trash \'+ids.length+\' item(s) do you want to continue?')."')) {
                        // now just call the ajax
                        $.ajax({
                                url: '" . Yii::app()->createUrl('pmessage/marksDeleted') . "',
                                data: {'ids':ids, 'action':'sent' ,'YII_CSRF_TOKEN': '" .Yii::app()->getRequest()->getCsrfToken(). "'},
                                type: 'post',
                                success: function(data){
                                        $.fn.yiiGridView.update('message-sent-grid', {
                                                data: $(this).serialize()
                                        });
                                }
                        });
                    }
                }
                
        
        return false; // if you want to avoid default button action
});", CClientScript::POS_READY);
?>



