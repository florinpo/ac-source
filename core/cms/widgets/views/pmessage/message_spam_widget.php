
<?php $this->render('cmswidgets.views.notification'); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'message-spam-grid',
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
            'cssClassExpression' => 'PrivateMessage::spamUnread($data->id)',
            'value' => 'PrivateMessage::activeSender($data->sender,$data->id)',
        ),
        array(
            'type' => 'raw',
            'header' => Yii::t('AdminNotification', 'Subject'),
            'htmlOptions' => array('class' => 'gridLeft'),
            'cssClassExpression' => 'PrivateMessage::spamUnread($data->id)',
            'value' => 'CHtml::link($data->subject, app()->getController()->createUrl("view",array("mailbox"=>"spambox","id"=>$data->id)))',
        ),
        array(
            'type' => 'raw',
            'htmlOptions' => array('class' => 'gridLeftMax'),
            'header' => Yii::t('AdminNotification', 'Date'),
            'cssClassExpression' => 'PrivateMessage::spamUnread($data->id)',
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
        var ids =  $.fn.yiiGridView.getSelection('message-spam-grid');
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
                                        $.fn.yiiGridView.update('message-spam-grid', {
                                                data: $(this).serialize()
                                        });
                                }
                        });
                    }
                }
                
        
        return false; // if you want to avoid default button action
});", CClientScript::POS_READY);
?>
