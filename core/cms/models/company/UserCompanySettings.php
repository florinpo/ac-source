<?php

/**
 * This is the model class for table "{{user_company_settings}}".
 *
 * The followings are the available columns in table '{{user_company_settings}}':
 * @property string $id
 * @property string $companyId
 * @property integer $email_news
 * @property integer $email_message
 * @property integer $email_traffic
 * @property integer $email_inquiry
 * @property integer $email_status
 */
class UserCompanySettings extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return UserCompanySettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_company_settings}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('companyId', 'required'),
			array('email_news, email_message, email_traffic, email_inquiry, email_status', 'numerical', 'integerOnly'=>true),
			array('companyId', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, companyId, email_news, email_message, email_traffic, email_inquiry, email_status', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	 public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        $relations = array(
            //'company_settings' => array(self::BELONGS_TO, 'User', 'companyId'),
        );
        return $relations;
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'companyId' => 'Company',
			'email_news' => 'Email News',
			'email_message' => 'Email Message',
			'email_traffic' => 'Email Traffic',
			'email_inquiry' => 'Email Inquiry',
			'email_status' => 'Email Status',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('companyId',$this->companyId,true);
		$criteria->compare('email_news',$this->email_news);
		$criteria->compare('email_message',$this->email_message);
		$criteria->compare('email_traffic',$this->email_traffic);
		$criteria->compare('email_inquiry',$this->email_inquiry);
		$criteria->compare('email_status',$this->email_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}