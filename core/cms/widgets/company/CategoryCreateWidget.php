<?php

/**
 * This is the Widget for Creating new Term
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package  cmswidgets.object
 *
 *
 */
class CategoryCreateWidget extends CWidget {

    public $visible = true;

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $model = new CompanyCats;
        $guid = isset($_GET['guid']) ? strtolower(trim($_GET['guid'])) : '';

        $lang_exclude = array();

        //List of translated versions
        $versions = array();

        if ($guid != '') {
            $taxonomy_object = CompanyCats::model()->with('language')->findAll('guid=:gid', array(':gid' => $guid));
            if (count($taxonomy_object) > 0) {
                foreach ($taxonomy_object as $obj) {
                    $lang_exclude[] = $obj->lang;
                    $versions[] = $obj->name . ' - ' . $obj->language->lang_desc;
                }
            }
            $model->guid = $guid;
        }

        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'category-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['CompanyCats'])) {
            $model->attributes = $_POST['CompanyCats'];
            $model->name = trim($_POST['CompanyCats']['name']);
            $model->description = trim($_POST['CompanyCats']['description']);
            
            if ($model->validate()) {
                if (isset($_POST['parent_id']) && $_POST['parent_id'] !== 'empty') {
                    $parent = GxcHelpers::loadDetailModel('CompanyCats', $_POST['parent_id']);
                    if ($model->appendTo($parent, false)) {
                        user()->setFlash('success', t('cms', 'New category has been successfully created'));
                         Yii::app()->controller->redirect(array('updatecategory', 'id'=>$model->id));
                    }
                } else {
                    // if model is new root
                    if ($model->saveNode(false)) {
                        user()->setFlash('success', t('cms', 'New category has been successfully created'));
                        Yii::app()->controller->redirect(array('updatecategory', 'id'=>$model->id));
                    }
                }
            }
        }


        $this->render('cmswidgets.views.company.category_form_widget', array('model' => $model, 'lang_exclude' => $lang_exclude, 'versions' => $versions,));
    }

}
