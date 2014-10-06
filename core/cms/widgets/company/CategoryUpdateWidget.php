<?php

/**
 * This is the Widget for Updating a Term
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package  cmswidgets.object
 *
 */
class CategoryUpdateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $cat_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        $model = GxcHelpers::loadDetailModel('CompanyCats', $cat_id);
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data


        $old_parent_id = isset($model->parent) ? $model->parent->id : 0;
        $previous_node_id = isset($model->prevSibling) ? $model->prevSibling->id : 0;
        $next_node_id = isset($model->nextSibling) ? $model->nextSibling->id : 0;


        if (isset($_POST['CompanyCats'])) {
            $model->attributes = $_POST['CompanyCats'];
            $model->name = trim($_POST['CompanyCats']['name']);
            $model->description = trim($_POST['CompanyCats']['description']);
            
            if ($model->validate()) {
                $new_parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 'empty';
                $new_order_id = isset($_POST['order_id']);

                if ($model->saveNode(false))
                    user()->setFlash('success', t('cms', 'Current category has been successfully updated'));


                if ($new_order_id !== $model->id && $old_parent_id != 0) {
                    $old_parent = GxcHelpers::loadDetailModel('CompanyCats', $old_parent_id);
                    $children = $old_parent->children()->findAll();

                    $first = reset($children);
                    $first_node = GxcHelpers::loadDetailModel('CompanyCats', $first->id);
                    $last = end($children);
                    $last_node = GxcHelpers::loadDetailModel('CompanyCats', $last->id);


                    //the previous sibling node (after the move).
                    if ($new_order_id != '-1' && $new_order_id != '-2')
                        $new_order_node = GxcHelpers::loadDetailModel('CompanyCats', $new_order_id);

                    //if the moved node is somewhere in the middle
                    if ($new_order_id != '-1' && $new_order_id != '-2' && $new_order_node->id !== $model->id) {

                        if ($model->moveAfter($new_order_node)) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    } else if ($new_order_id == '-1' && $model->id !== $first_node->id) {

                        if ($model->moveBefore($first_node)) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    } else if ($new_order_id == '-2' && $model->id !== $last_node->id) {
                        if ($model->moveAfter($last_node)) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    }
                }

                if ($new_parent_id != 'empty') {

                    $new_parent = GxcHelpers::loadDetailModel('CompanyCats', $new_parent_id);
                    //the previous sibling node (after the move).

                    if ($previous_node_id != '0')
                        $previous_node = GxcHelpers::loadDetailModel('CompanyCats', $previous_node_id);

                    //if the moved node is only child of new parent node
                    if ($previous_node_id == '0' && $next_node_id == '0') {

                        if ($model->moveAsFirst($new_parent)) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    }
                    //if we moved it in the first position
                    else if ($previous_node_id != '0' && $next_node_id != '0') {

                        if ($model->moveAsFirst($new_parent)) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    }

                    //if we moved it in the first position
                    else if ($previous_node_id == '0' && $next_node_id != '0') {

                        if ($model->moveAsFirst($new_parent)) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    }
                    //if we moved it in the last position
                    else if ($previous_node_id != '0' && $next_node_id == '0') {

                        if ($model->moveAsLast($new_parent)) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    }
                    //if the moved node is somewhere in the middle
                    else if ($previous_node_id != '0' && $next_node_id != '0') {

                        if ($model->moveAfter($previous_node)) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    }
                } else {
                    //if moved node is not Root
                    if (!$model->isRoot()) {
                        if ($model->moveAsRoot()) {
                            user()->setFlash('success', t('cms', 'Current category has been successfully updated'));
                        }
                    }
                }
            }
        }

        $this->render('cmswidgets.views.company.category_form_widget', array('model' => $model));
    }

}
