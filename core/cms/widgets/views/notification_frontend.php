<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="successMessage notification-box-success notification-box">
        <a href="javascript:void();" class="close"></a>
        <div>
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasFlash('error')): ?>
    <div class="errorMessage notification-box-error notification-box">
        <a href="javascript:void();" class="close"></a>
        <div>
            <?php echo Yii::app()->user->getFlash('error'); ?>
        </div>
    </div>
<?php endif; ?>

<?php if (Yii::app()->user->hasFlash('info')): ?>
    <div class="infoMessage notification-box-info notification-box">
        <a href="javascript:void();" class="close"></a>
        <div>
            <?php echo Yii::app()->user->getFlash('info'); ?>
        </div>
    </div>
<?php endif; ?>