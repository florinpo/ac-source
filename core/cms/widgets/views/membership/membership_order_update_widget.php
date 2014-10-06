<div class="form">
    <?php $this->render('cmswidgets.views.notification'); ?>
</div>
<?php
$this->widget('zii.widgets.CDetailView', array(
    'data' => $order,
    'attributes' => array(
        'order_num',
        array(
            'label' => t('cms', 'Purchased item'),
            'value' => $order->product->title
        ),
        array(
            'name' => 'status',
            'value' => $order->getStatus()
        ),
        array(
            'name' => 'order_date',
            'value' => simple_date($order->order_date)
        ),
        array(
            'name' => 'payment_due',
            'value' => simple_date($order->payment_due)
        ),
        array(
            'name' => 'payment_date',
            'value' => !empty($order->payment_date) ? simple_date($order->payment_date) : '-'
        ),
        array(
            'name' => 'end_date',
            'value' => !empty($order->end_date) ? simple_date($order->end_date) : '-'
        ),
        array(
            'name' => 'Username',
            'type' => 'raw',
            'value' => CHtml::link($order->user->username, array('company/view', 'id' => $order->company_id))
        ),
        array(
            'label' => t('cms', 'Company'),
            'value' => $order->payment->company_name
        ),
        array(
            'label' => t('cms', 'Represented by'),
            'value' => $order->payment->first_name . ' ' . $order->payment->last_name
        ),
        array(
            'label' => t('cms', 'Region'),
            'value' => Region::model()->findByPk($order->payment->region_id)->name
        ),
        array(
            'label' => t('cms', 'Province'),
            'value' => Province::model()->findByPk($order->payment->province_id)->name
        ),
        array(
            'label' => t('cms', 'Location'),
            'value' => $order->payment->location
        ),
        array(
            'label' => t('cms', 'Adress'),
            'value' => $order->payment->adress
        ),
        array(
            'label' => t('cms', 'Phone'),
            'value' => $order->payment->phone
        ),
        array(
            'label' => t('cms', 'Email'),
            'value' => $order->payment->email
        ),
        array(
            'label' => t('cms', 'Fax'),
            'value' => $order->payment->fax
        ),
        array(
            'label' => t('cms', 'Mobile'),
            'value' => $order->payment->mobile
        ),
        array(
            'label' => t('cms', 'Print bill'),
            'type' => 'raw',
            'value' => !empty($order->invoice_num) ? CHtml::link(t('cms', 'Print invoice'), array('membership/printorder', 'id' => $order->id), array('target'=>'_blank')) : '-'
        )
    )
));
?>

<?php if(empty($order->invoice_num)): ?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'membership-activate-form',
        'enableAjaxValidation' => true,
            ));
    ?>
    <?php echo $form->hiddenField($model, 'order_id', array('value' => $order->id)); ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'invoice_num'); ?>
        <?php echo $form->textField($model, 'invoice_num', array()); ?>
        <?php echo $form->error($model, 'invoice_num'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton(t('cms', 'Set payment today'), array('class' => 'bebutton')); ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>
<br /><br /><br />