<?php

class UserProfile extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserProfile the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_profile}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('firstname, lastname, region_id, province_id', 'required'),
            array('firstname, lastname', 'length', 'max' => 50),
            array('location', 'length', 'max' => 100),
            array('region_id, province_id, is_public', 'numerical', 'integerOnly' => true, 'message' => t('cms', 'Please select the {attribute}')),
            array('phone', 'numerical', 'integerOnly' => true),
            array('adress', 'length', 'max' => 100),
            array('postal_code', 'length', 'max' => 5),
            array('phone', 'length', 'max' => 20),
            array('postal_code, gender, birthday, avatar', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, userId, lastname, firstname, gender, region_id, province_id, location, adress, birthday, phone', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        $relations = array(
            'user' => array(self::BELONGS_TO, 'User', 'userId'),
            'region' => array(self::BELONGS_TO, 'Region', 'region_id', 'order' => 'region.name ASC'),
            'province' => array(self::BELONGS_TO, 'Province', 'province_id')
        );
        return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => t('cms', 'ID'),
            'userId' => t('cms', 'User'),
            'lastname' => t('cms', 'Last name'),
            'firstname' => t('cms', 'First name'),
            'gender' => t('cms', 'Gender'),
            'region_id' => t('cms', 'Region'),
            'province_id' => t('cms', 'Province'),
            'location' => t('cms', 'Location'),
            'postal_code' => t('cms', 'Postal code'),
            'adress' => t('cms', 'Adress'),
            'birthday' => t('cms', 'Birthday '),
            'phone' => t('cms', 'Phone'),
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('userId', $this->userId, true);
        $criteria->compare('lastname', $this->lastname, true);
        $criteria->compare('firstname', $this->firstname, true);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('region_id', $this->region_id, true);
        $criteria->compare('province_id', $this->province_id, true);
        $criteria->compare('gender', $this->gender, true);
        $criteria->compare('adress', $this->adress, true);
        $criteria->compare('phone', $this->phone, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    public function beforeSave() {
        $this->firstname = ucfirst($this->firstname);
        $this->lastname = ucfirst($this->lastname);

        if (!empty($this->birthday)) {
            list($day, $month, $year) = explode('/', $this->birthday);
            $this->birthday = implode('-', array($year, $month, $day));
        } else {
            $this->birthday = '0000-00-00';
        }
        return parent::beforeSave();
    }

    public function afterFind() {
        if (!empty($this->birthday)) {
            list($year, $month, $day) = explode('-', $this->birthday);
            $this->birthday = implode('/', array($day, $month, $year));
        }
        parent::afterFind();
    }

    public function selectedImage($size, $stripped=false) {
        $path = $this->avatar;
        $layout_asset = GxcHelpers::publishAsset(Yii::getPathOfAlias('common.layouts.default.assets'));
        if (empty($path)) {
            if (isset($this->gender)) {
                if ($this->gender == 'female')
                    $url = $layout_asset . "/images/entries/avatar/" . $size . "/f-silhouette.png";
                else
                    $url = $layout_asset . "/images/entries/avatar/" . $size . "/m-silhouette.png";
                    
            } else
                $url = $layout_asset . "/images/entries/avatar/" . $size . "/empty.png";
        } else {
            if ($path && file_exists(IMAGES_FOLDER . "/img" . $size . "/" . $path)) {
                $url = IMAGES_URL . "/img" . $size . "/" . $path;
            } else {
                $url = $layout_asset . "/images/entries/avatar/" . $size . "/empty.png";
                
            }
        }
        
        if($stripped==true)
            return $url;
        else 
            return CHtml::image($url, $this->user->username);
    }
    
    protected function afterDelete() {
        parent::afterDelete();
        if ($this->avatar != null && $this->avatar != '') {
            $old_path = $this->avatar;
            //Delete old file Sizes
            $sizes = ImageSize::getAvatarSizes();
            foreach ($sizes as $size) {
                if (file_exists(IMAGES_FOLDER . '/' . $size['id'] . '/' . $old_path))
                    @unlink(IMAGES_FOLDER . '/' . $size['id'] . '/' . $old_path);
            }
        }
    }

}