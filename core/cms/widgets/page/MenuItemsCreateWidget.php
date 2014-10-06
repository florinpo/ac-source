<?php

/**
 * This is the Widget for Creating new Menu Item
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package  cmswidgets.page
 *
 *
 */
class MenuItemsCreateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $model = new MenuItems;

        if (!empty($_GET['menu'])) {
            $name = "root-menu-" . (int) $_GET['menu'];
            $root = MenuItems::model()->find(array('condition' => 'name=:name', 'params' => array(':name' => $name)));
            // if it is ajax validation request
            if (isset($_POST['ajax']) && $_POST['ajax'] === 'menuitems-form') {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }

            // collect user input data
            if (isset($_POST['MenuItems'])) {
                $model->attributes = $_POST['MenuItems'];
                $model->menu_id = (int) $_GET['menu'];
                $model->name = trim($_POST['MenuItems']['name']);
                $model->description = trim($_POST['MenuItems']['description']);
                if ($model->validate()) {
                    if (isset($_POST['parent_id']) && $_POST['parent_id'] !== 'empty') {
                        $parent = GxcHelpers::loadDetailModel('MenuItems', $_POST['parent_id']);
                        if ($model->appendTo($parent, false)) {
                            user()->setFlash('success', Yii::t('AdminMenu', 'New menu item has been successfully created'));
                            Yii::app()->controller->redirect(array('create', 'menu' => $model->menu_id));
                        }
                    } else {
                        // if model is new root
                        if ($model->appendTo($root, false)) {
                            user()->setFlash('success', Yii::t('AdminMenu', 'New menu item has been successfully created'));
                            Yii::app()->controller->redirect(array('update', 'menu' => $model->menu_id, 'id'=>$model->id));
                        }
                    }
                }
            }

            $this->render('cmswidgets.views.menuitems.menu_items_form_widget', array('model' => $model));
        } else {
            throw new CHttpException(404, Yii::t('error', 'The requested page does not exist.'));
        }
    }

}
