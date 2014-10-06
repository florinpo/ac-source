
<ul>
    <li>
        <?php

            if (isset($model->receiver)) {
                if ($model->receiver->status == 1) {
                    echo CHtml::link(t('cms', 'Reply'), array('pmessage/reply',
                        'm' => $model->id,
                    ));
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
      
        ?>
    </li>
    <li>
        <?php echo CHtml::link(t('cms', 'Delete'), "javascript:void(0);", array("submit" => Yii::app()->createUrl("pmessage/markDelete", array('id' =>$model->id, 'action'=>'spam')), 'csrf' => true, 'confirm' => 'Are you sure you want to move this item to trash ?')); ?>
    </li>
    <li>
        <?php echo CHtml::link(t('cms', 'No spam'), "javascript:void(0);", array("submit" => Yii::app()->createUrl("pmessage/noSpam", array('id' =>$model->id, 'action'=>'spam')), 'csrf' => true)); ?>
    </li>
    
</ul>  

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model != null ? $model : '',
    'attributes' => array(
        array(
            'label' => t('cms', 'From'),
            'type' => 'raw',
            'value' => $model != null ? PrivateMessage::activeSender($model->sender,$model->id) : '',
        ),
         array(
            'label' => t('cms', 'Sent to'),
            'type' => 'raw',
            'value' => $model != null ? PrivateMessage::activeReceiver($model->receiver,$model->id) : '',
        ),
        array(
            'label' => t('cms', 'Date'),
            'type' => 'raw',
            'value' => $model != null ? niceDate($model->create_time) : '',
        ),
        
        array(
            'label' => t('cms', 'Subject'),
            'type' => 'raw',
            'value' => $model != null ? $model->subject : '',
        )
    ),
));
?>

<br />
<div class="message">
    <?php
    $message = nl2br(str_replace("--------------------------------------------", "<hr style='margin:5px 0px 0 0; padding:0' />", makeLinks($model != null ? $model->body : t('cms', 'This user does not exist'))));
    echo $message;
    ?>
</div>



