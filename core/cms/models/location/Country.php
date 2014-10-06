<?php

/**
 * This is the model class for table "{{country}}".
 *
 * The followings are the available columns in table '{{country}}':
 * @property string $code
 * @property string $name
 * @property string $continent
 */
class Country extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Country the static model class
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
		return '{{country}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
//	public function rules()
//	{
//		// NOTE: you should only define rules for those attributes that
//		// will receive user inputs.
//		return array(
//			array('code', 'length', 'max'=>3),
//			array('name', 'length', 'max'=>52),
//			array('continent', 'length', 'max'=>13),
//			// The following rule is used by search().
//			// Please remove those attributes that should not be searched.
//			array('code, name, continent', 'safe', 'on'=>'search'),
//		);
//	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'code' => Yii::t('Country','Code'),
			'name' => Yii::t('Country','Name'),
			'continent' => Yii::t('Country','Continent'),
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

		$criteria->compare('code',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('continent',$this->continent,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        public static function convertCountry($id){
                $model=Country::model()->findByPk($id);
                if($model){
                    return $model->name;
                }
                
                return '';
        }
        
        public static function getCountry(){
            $country=Country::model()->findAll();
                        
            $data=array(0=>t("None"));
            if($country && count($country) > 0 ){
               foreach($country as $t){
                    $data[$t->code]=$t->name;
                }
            }
            return $data;
        }
        
      
}