<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'user-update-form',
        'enableAjaxValidation' => true,
            ));
    ?>

    <?php echo $form->errorSummary($model); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'username'); ?>
        <?php echo $form->textField($model, 'username',  array('disabled'=>true)); ?>
        <?php echo $form->error($model, 'username'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'email'); ?>
        <?php echo $form->textField($model, 'email',  array('disabled'=>true)); ?>
        <?php echo $form->error($model, 'email'); ?>
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

    <?php
    if ($model->user_id == '1'):
        $class = 'hidden';
        ?>
        <div class="<?php echo $class; ?>">
            <?php endif; ?>
        
        <div class="row">
        <?php echo $form->labelEx($model,'status'); ?>
        <?php echo $form->dropDownList($model,'status',ConstantDefine::getUserStatus()); ?>
        <?php echo $form->error($model,'status'); ?>                                  
        </div>
            
            
        <?php
        $roles = Rights::getAssignedRoles($model->user_id);

        $select = array();
        foreach ($roles as $r) {
            array_push($select, $r->name);
        }

        $all_roles = new RAuthItemDataProvider('roles', array('type' => 2,));
        $data = $all_roles->fetchData();
        ?>
        <div class="row clearfix toHide" id="user_normal">
            <label>Roles</label>
            <?php
            echo CHtml::checkBoxList("roles_type", $select, CHtml::listData($data, 'name', 'name'), array('separator' => '',
                'class' => 'checkb-role'));
            ?>
            <span class="clear"></span>
        </div>
    </div>

    <div class="row buttons">
       <?php echo CHtml::submitButton(Yii::t('Global','Save'), array('class' => 'bebutton')); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- form -->


















