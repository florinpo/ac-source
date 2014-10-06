<?php

/**
 * This is the Widget for Creating new Page 
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package  cmswidgets.page
 *
 */
class LanguageManageWidget extends CWidget {

    public $visible = true;

    //public $message, $category;


    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $criteria = new CDbCriteria;
        $criteria->order = 'lang_name=:lang desc';
        $criteria->params = array(':lang' => settings()->get("system", "language_source"));

        $model = new CActiveDataProvider('Language', array(
                    'criteria' => $criteria,
                        //'sort'=>$sort
                ));

        $totalStringsInSource = SourceMessage::model()->count();


        $this->render('cmswidgets.views.language.language_manage_widget', array('model' => $model, 'totalStringsInSource' => $totalStringsInSource));
    }

}
