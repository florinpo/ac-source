<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class MembershipOrderUpdateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $order = GxcHelpers::loadDetailModel('MembershipOrder', $id);
        $model = new MembershipActivateForm;

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'membership-activate-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['MembershipActivateForm'])) {
            $model->attributes = $_POST['MembershipActivateForm'];
            if ($model->validate()) {
                $order = GxcHelpers::loadDetailModel('MembershipOrder', $model->order_id);
                $order->invoice_num = 'CT AXE '.$model->invoice_num;
                $order->status = ConstantDefine::ORDER_STATUS_PAID;
                $order->payment_date = time();
                $order->end_date = $order->calculateEndDate($order->payment_date);
                if ($order->save(false, array('payment_date', 'end_date', 'status', 'invoice_num'))) {
                    $order->user->has_membership = 1;
                    $order->user->save(false, array('has_membership'));
                    user()->setFlash('success', t('cms', 'Order has been successfully updated!'));
                    Yii::app()->controller->redirect(array('updateorder', 'id'=>$order->id));
                    //here we will send the message to the user email to confirm the payment
                }
            }
        }
        $this->render('cmswidgets.views.membership.membership_order_update_widget', array('order' => $order, 'model' => $model));
    }

}

