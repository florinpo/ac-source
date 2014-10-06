<?php

class SiteSearchForm extends CFormModel {

    public $keyword;
    public $type;


    public function rules() {
        return array(
            array('keyword, type', 'safe')
        );
    }

//    public function safeAttributes()
//    {
//        return array('keyword',);
//    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'keyword' => Yii::t('Page', 'Keyword'),
            'type' => Yii::t('Page', 'Type'),
        );
    }

}
