<?php

/**
 * This is the Widget for create new User.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets.user
 *
 */
class CompanyCategorySelectWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {

        $user_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $model = new CompanyCategoryForm;
        if ($user_id != 0) {
            if (isset($_POST['save-cat'])) {
                $model->setScenario('save-cat');
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {

                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                }
            } else if (isset($_POST['select-cat'])) {
                $model->setScenario('select-cat');
                if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {

                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                }
            }

            $user = User::model()->findByPk($user_id);

            $this->render('cmswidgets.views.category_select.company_category', array('model' => $model, 'user' => $user));
        } else {
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        }
    }

}
