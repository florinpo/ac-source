<?php
/**
 * StringLengthValidator class file.
 *
 * this class is inspired from CStringValidator
 * the purpose is to exclude the html tags when doing the length validation
 * for the rich editor text
 */
class StringLengthValidator extends CValidator
{
	/**
	 * @var integer maximum length. Defaults to null, meaning no maximum limit.
	 */
	public $max;
	/**
	 * @var integer minimum length. Defaults to null, meaning no minimum limit.
	 */
	public $min;
	/**
	 * @var integer exact length. Defaults to null, meaning no exact length limit.
	 */
	public $is;
	/**
	 * @var string user-defined error message used when the value is too short.
	 */
	public $tooShort;
	/**
	 * @var string user-defined error message used when the value is too long.
	 */
	public $tooLong;
	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty=true;
	/**
	 * @var string the encoding of the string value to be validated (e.g. 'UTF-8').
	 * This property is used only when mbstring PHP extension is enabled.
	 * The value of this property will be used as the 2nd parameter of the
	 * mb_strlen() function. If this property is not set, the application charset
	 * will be used.
	 * If this property is set false, then strlen() will be used even if mbstring is enabled.
	 * @since 1.1.1
	 */
	public $encoding;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validateAttribute($object,$attribute)
	{
		$value=strip_tags($object->$attribute);
		if($this->allowEmpty && $this->isEmpty($value))
			return;

		if(function_exists('mb_strlen') && $this->encoding!==false)
			$length=mb_strlen($value, $this->encoding ? $this->encoding : Yii::app()->charset);
		else
			$length=strlen($value);

		if($this->min!==null && $length<$this->min)
		{
			$message=$this->tooShort!==null?$this->tooShort:Yii::t('yii','{attribute} is too short (minimum is {min} characters).');
			$this->addError($object,$attribute,$message,array('{min}'=>$this->min));
		}
		if($this->max!==null && $length>$this->max)
		{
			$message=$this->tooLong!==null?$this->tooLong:Yii::t('yii','{attribute} is too long (maximum is {max} characters).');
			$this->addError($object,$attribute,$message,array('{max}'=>$this->max));
		}
		if($this->is!==null && $length!==$this->is)
		{
			$message=$this->message!==null?$this->message:Yii::t('yii','{attribute} is of the wrong length (should be {length} characters).');
			$this->addError($object,$attribute,$message,array('{length}'=>$this->is));
		}
	}

	/**
	 * Returns the JavaScript needed for performing client-side validation.
	 * @param CModel $object the data object being validated
	 * @param string $attribute the name of the attribute to be validated.
	 * @return string the client-side validation script.
	 * @see CActiveForm::enableClientValidation
	 * @since 1.1.7
	 */
	public function clientValidateAttribute($object,$attribute)
	{
		$label=$object->getAttributeLabel($attribute);

		if(($message=$this->message)===null)
			$message=Yii::t('yii','{attribute} is of the wrong length (should be {length} characters).');
		$message=strtr($message, array(
			'{attribute}'=>$label,
			'{length}'=>$this->is,
		));

		if(($tooShort=$this->tooShort)===null)
			$tooShort=Yii::t('yii','{attribute} is too short (minimum is {min} characters).');
		$tooShort=strtr($tooShort, array(
			'{attribute}'=>$label,
			'{min}'=>$this->min,
		));

		if(($tooLong=$this->tooLong)===null)
			$tooLong=Yii::t('yii','{attribute} is too long (maximum is {max} characters).');
		$tooLong=strtr($tooLong, array(
			'{attribute}'=>$label,
			'{max}'=>$this->max,
		));

		$js='
                    var normalizedTxt = value.replace(/(\r\n|\n|\r)/gm, "").
                        replace(/^\s+|\s+$/g, "").
                        //replace("&nbsp;", "").
                        replace(/   /g, "");
                    normalizedTxt = strip(normalizedTxt);
                    
                    var normalizedLength = normalizedTxt.length;
                    
                    ';
		if($this->min!==null)
		{
			$js.="
if(normalizedLength<{$this->min}) {
	messages.push(".CJSON::encode($tooShort).");
        console.log(strip(value) + '-'+ normalizedLength);
}
";
		}
		if($this->max!==null)
		{
			$js.="
if(normalizedLength>{$this->max}) {
	messages.push(".CJSON::encode($tooLong).");
}
";
		}
		if($this->is!==null)
		{
			$js.="
if(normalizedLength={$this->is}) {
	messages.push(".CJSON::encode($message).");
}
";
		}

		if($this->allowEmpty)
		{
			$js="
if(jQuery.trim(value)!='') {
	$js
}
";
		}

		return $js;
	}
}

