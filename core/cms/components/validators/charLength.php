<?php

class charLength extends CStringValidator {
	
    protected function validateAttribute($object,$attribute) {
        $value = strip_tags($object->$attribute);
//        if (!$this->isCharsetCorrect($value)) {
//            $message=$this->wrongCharset !== null ? $this->wrongCharset : Yii::t('yii','Wrong character set.');
//            $object->$attribute = '';
//            $this->addError($object,$attribute,$message);
//        }
        
        parent::validateAttribute(strip_tags($object),$attribute);
    }

}
