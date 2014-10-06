<?php

/**
 * This is the model class for table "{{language}}".
 *
 * The followings are the available columns in table '{{language}}':
 * @property integer $lang_id
 * @property string $lang_name
 * @property string $lang_desc
 * @property integer $lang_required
 * @property integer $lang_active
 */
class Language extends CActiveRecord {

    private static $_items = array();

    /**
     * Returns the static model of the specified AR class.
     * @return Language the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{language}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('lang_required, lang_active', 'numerical', 'integerOnly' => true),
            array('lang_name, lang_desc', 'length', 'max' => 255),
            array('lang_short', 'length', 'max' => 10),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('lang_id, lang_name, lang_desc, lang_required, lang_active', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'lang_id' => Yii::t('Language','Language'),
            'lang_name' => Yii::t('Language','Language Key'),
            'lang_desc' => Yii::t('Language','Language Name'),
            'lang_required' => Yii::t('Language','Language Required'),
            'lang_active' => Yii::t('Language','Language Active'),
            'lang_short' => Yii::t('Language','Language Shortcut'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;
        $criteria->compare('lang_id', $this->lang_id);
        $criteria->compare('lang_name', $this->lang_name, true);
        $criteria->compare('lang_desc', $this->lang_desc, true);
        $criteria->compare('lang_required', $this->lang_required);
        $criteria->compare('lang_active', $this->lang_active);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                   
                ));
    }

    public static function findByCode($cod,$route,$redirect = true){
        $cod = substr($cod,0,2);
        $data = self::model()->find('lang_name=:LANG AND lang_active = 1',array(':LANG'=>$cod));
        if(empty($data)){
            $lang = settings()->get("system","language_source");
 
            if($redirect == true){
                if(preg_match("/index/", $route))
                Yii::app()->controller->redirect(Yii::app()->homeUrl.$lang.'/');        
                else        
                Yii::app()->controller->redirect(Yii::app()->homeUrl.$lang.'/'.$route);
            }else
            return $lang;    
        } 
        else
        return $data->lang_name;
    }
 
    /*drop down settings*/
    public static function getAvalaibleLanguages() {
        $langs = array();
        
        $models = Language::model()->findAll(array(
                'condition' => 'lang_active=:type',
                'params' => array(':type' => 1),
                'order' => 'lang_id ASC',
                    //  'limit'=>settings()->get('system','language_number')
                    ));
         if (count($models) > 0) {
            foreach ($models as $model) {
                    $langs[$model->lang_name] = $model->lang_desc;
            }
            return $langs;
        }
      
    }
    
    
    public static function items($exclude = array(), $desc = true) {
        return self::loadItems($exclude, $desc);
    }

    /**
     * Load Items Language
     * @param type $exclude 
     */
    public static function loadItems($exclude = array(), $desc = true) {

        if (count($exclude) <= 0) {
            $models = self::model()->findAll(array(
                'condition' => 'lang_active=:type',
                'params' => array(':type' => 1),
                'order' => 'lang_id ASC',
                    //  'limit'=>settings()->get('system','language_number')
                    ));
        } else {
            $models = self::model()->findAll(array(
                'condition' => 'lang_active=:type and lang_id NOT IN ( ' . implode(',', $exclude) . ')',
                'params' => array(':type' => 1),
                'order' => 'lang_id',
                'limit' => settings()->get('system', 'language_number')
                    ));
        }

        if (count($models) > 0) {
            foreach ($models as $model) {
                if ($desc) {
                    $items[$model->lang_id] = $model->lang_desc;
                } else {
                    $items[$model->lang_id] = $model->lang_name;
                }
            }
            return $items;
        } else {
            Yii::app()->getController()->redirect('admin');
        }
    }

    public static function mainLanguage() {
        $criteria = new CDbCriteria;
        $criteria->condition = 'lang_active=1';
        $criteria->order = 'lang_id ASC';
        $criteria->limit = 1;
        $model = self::model()->find($criteria);
        if ($model != null)
            return $model->lang_id;
        else
            return 0;
    }

    public static function convertLanguage($id) {
        $model = Language::model()->findByPk($id);
        if ($model) {
            return $model->lang_desc;
        }

        return '';
    }

    public static function getStringTranslationDifference($language) {
        $origs = SourceMessage::model()->findAll();
        $translated = 0;
        if (count($origs)) {
            foreach ($origs as $orig) {
                // Grab the translation from the messages table
                $message = TranslatedMessage::model()->find('language=:lang AND id=:id', array(':lang' => $language, ':id' => $orig->id));
                if ($message) {
                    if ($message->translation != $orig->message) {
                        $translated++;
                    }
                }
            }
        }
        return $translated;
    }

    public static function convertLanguageIcon($data) {

        if ($data->lang_name == settings()->get("system","language_source")) {
            $image = 'alert-circle';
        } else {
            $image = 'check-circle';
        }

        //$image= ($data->status==ConstantDefine::USER_STATUS_ACTIVE) ? 'active' : 'disabled';

        $backend_asset = Yii::app()->assetManager->publish(Yii::getPathOfAlias('cms.assets.backend'), false, -1, false);

        return $backend_asset . '/images/translate/' . $image . '.png';
    }

    public static function convertLanguageTitle($data) {

        if ($data->lang_name == settings()->get("system","language_source")) {
            $title = Yii::t('Language', 'Source language cannot be translated');
        } else {
            $title = Yii::t('Language', 'Not source language');
        }

        return $title;
    }

}