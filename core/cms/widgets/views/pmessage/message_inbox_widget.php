
<?php $this->render('cmswidgets.views.notification'); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'message-inbox-grid',
    'dataProvider' => $model,
    'summaryText' => t('site', 'Displaying') . ' {start} - {end} ' . t('site', 'in') . ' {count} ' . t('site', 'results'),
    'pager' => array(
        'header' => t('site', 'Go to page:'),
        'nextPageLabel' => t('site', 'next'),
        'prevPageLabel' => t('site', 'previous'),
        'firstPageLabel' => t('site', 'First'),
        'lastPageLabel' => t('site', 'Last'),
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
            'header' => t('site', 'From'),
            'htmlOptions' => array('class' => 'gridLeft'),
            'cssClassExpression' => "(!\$data->is_read) ? 'unread' :''",
            'value' => 'PrivateMessage::activeSender($data->sender,$data->id)',
        ),
        array(
            'type' => 'raw',
            'header' => t('site', 'Subject'),
            'htmlOptions' => array('class' => 'gridLeft'),
            'cssClassExpression' => "(!\$data->is_read) ? 'unread' :''",
            'value' => 'CHtml::link($data->subject, app()->getController()->createUrl("view",array("mailbox"=>"inbox","id"=>$data->id)))',
        ),
        array(
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeftMax'),
            'header' => t('site', 'Date'),
            'cssClassExpression' => "(!\$data->is_read) ? 'unread' :''",
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
        var ids =  $.fn.yiiGridView.getSelection('message-inbox-grid');
                // we have array, lets split them into a string separating
                // values with commas
               
               if (ids.length > 0) {
                     if (confirm('".t('cms','You are about to move in trash \'+ids.length+\' item(s) do you want to continue?')."')) {
                        // now just call the ajax
                        $.ajax({
                                url: '" . Yii::app()->createUrl('pmessage/marksDeleted') . "',
                                data: {'ids':ids, 'action':'inbox' ,'YII_CSRF_TOKEN': '" .Yii::app()->getRequest()->getCsrfToken(). "'},
                                type: 'post',
                                success: function(data){
                                        $.fn.yiiGridView.update('message-inbox-grid', {
                                                data: $(this).serialize()
                                        });
                                }
                        });
                    }
                }
                
        
        return false; // if you want to avoid default button action
});", CClientScript::POS_READY);
?>






