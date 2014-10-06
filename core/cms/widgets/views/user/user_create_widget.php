<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-create-form',
        'enableAjaxValidation' => true,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email'); ?>
        <?php echo $form->error($model, 'email'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username'); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'display_name'); ?>
        <?php echo $form->textField($model, 'display_name'); ?>
        <?php echo $form->error($model, 'display_name'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password'); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'verifyPassword'); ?>
        <?php echo $form->passwordField($model, 'verifyPassword'); ?>
        <?php echo $form->error($model, 'verifyPassword'); ?>
    </div>


    

    <?php
    if (user()->isSuperuser):
        $all_roles = new RAuthItemDataProvider('roles', array('type' => 2,));
        $data = $all_roles->fetchData();
        ?>
        <div class="row clearfix toHide" id="user_normal">
            <label>Roles</label>
            <?php
            echo CHtml::checkBoxList("roles_type", '', CHtml::listData($data, 'name', 'name'), array('separator' => '',
                'class' => 'checkb-role'));
            ?>
            <span class="clear"></span>
        </div>
    <?php endif; ?>

    <div class="row buttons">
        <?php echo CHtml::submitButton(t('cms','Save'), array('class' => 'bebutton')); ?>
    </div>

    <?php $this->endWidget(); ?>

    <script type="text/javascript">    
        CopyString('#UserCreateForm_email','#UserCreateForm_username','email');
        CopyString('#UserCreateForm_email','#UserCreateForm_display_name','email');
    </script>
</div><!-- form -->
