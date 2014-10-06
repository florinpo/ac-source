
<ul>
    <li>
        <?php
        if (isset($model->sender)) {
            if ($model->sender->status == 1) {
                echo CHtml::link(t('cms', 'Reply'), array('pmessage/reply',
                    'm' => $model->id
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
        <?php echo CHtml::link(t('cms', 'Delete'), "javascript:void(0);", array("submit" => Yii::app()->createUrl("pmessage/markDelete", array('id' =>$model->id, 'action'=>'inbox')), 'csrf' => true, 'confirm' => 'Are you sure you want to move this item to trash ?')); ?>
    </li>
    <li>
        <?php echo CHtml::link(t('cms', 'Spam'), "javascript:void(0);", array("submit" => Yii::app()->createUrl("pmessage/markSpam", array('id' =>$model->id, 'action'=>'inbox')), 'csrf' => true)); ?>
    </li>

</ul>  

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $model != null ? $model : '',
    'attributes' => array(
        array(
            'label' => Yii::t('AdminNotification', 'From'),
            'type' => 'raw',
            'value' => $model != null ? PrivateMessage::activeSender($model->sender, $model->id) : '',
        ),
        array(
            'label' => Yii::t('AdminNotification', 'Date'),
            'type' => 'raw',
            'value' => $model != null ? niceDate($model->create_time) : '',
        ),
        array(
            'label' => Yii::t('AdminNotification', 'Subject'),
            'type' => 'raw',
            'value' => $model != null ? $model->subject : '',
        ),
//        array(
//            'label' => Yii::t('AdminNotification', 'PrivateMessage'),
//            'type' => 'raw',
//            'value' => $model->message,
//        ),
    ),
));
?>
<br />
<div class="message">
    <?php
    $message = nl2br(str_replace("--------------------------------------------", "<hr style='margin:5px 0px 0 0; padding:0' />", makeLinks($model != null ? $model->body : Yii::t('PMAdmin', 'This user does not exist'))));
    echo $message;
    ?>
</div>













