<?php

class UserCompanyProfile extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CompanyProfile the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_company_profile}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('companyId', 'required'),
            array('region_id, province_id, domain_id', 'numerical', 'integerOnly' => true),
            array('companyId', 'length', 'max' => 10),
            array('lastname, firstname, companyname, companytype, companyposition, location, bank_name, bank_iban', 'length', 'max' => 50),
            array('vat_code', 'length', 'max' => 11),
            array('adress', 'length', 'max' => 255),
            array('postal_code', 'length', 'max' => 5),
            array('phone, fax, mobile', 'length', 'max' => 20),
            array('gender, birthday', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, companyId, lastname, firstname, companyname, companytype, companyposition, vat_code, region_id, province_id, domain_id, location, adress, postal_code, phone, fax, mobile', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        $relations = array(
            'company' => array(self::BELONGS_TO, 'User', 'companyId'),
            'store' => array(self::BELONGS_TO, 'CompStore', 'storeId'),
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
            'id' => 'ID',
            'companyId' => 'Company',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
            'companyname' => 'Companyname',
            'companytype' => 'Companytype',
            'companyposition' => 'Companyposition',
            'vat_code' => 'vat_code',
            'region_id' => 'Region',
            'province_id' => 'Province',
            'location' => 'Location',
            'adress' => 'Adress',
            'postal_code' => 'Postal Code',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'mobile' => 'Mobile',
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
        $criteria->compare('companyId', $this->companyId, true);
        $criteria->compare('lastname', $this->lastname, true);
        $criteria->compare('firstname', $this->firstname, true);
        $criteria->compare('companyname', $this->companyname, true);
        $criteria->compare('companytype', $this->companytype, true);
        $criteria->compare('companyposition', $this->companyposition, true);
        $criteria->compare('vat_code', $this->vat_code, true);
        $criteria->compare('region_id', $this->region_id);
        $criteria->compare('province_id', $this->province_id);
        $criteria->compare('location', $this->location, true);
        $criteria->compare('adress', $this->adress, true);
        $criteria->compare('postal_code', $this->postal_code, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('fax', $this->fax, true);
        $criteria->compare('mobile', $this->mobile, true);
        $criteria->compare('website', $this->website, true);
        $criteria->compare('description', $this->description, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            $this->firstname = ucfirst($this->firstname);
            $this->lastname = ucfirst($this->lastname);
            return true;
        }
        else
            return false;
    }

    protected function afterSave() {
        $this->addImages();
        parent::afterSave();
    }

    protected function afterDelete() {
        parent::afterDelete();
        if ($this->logo != null && $this->logo != '') {
            $old_path = $this->logo;
            //Delete old file Sizes
            $sizes = ImageSize::getSizes();
            foreach ($sizes as $size) {
                if (file_exists(IMAGES_FOLDER . '/' . $size['id'] . '/' . $old_path))
                    @unlink(IMAGES_FOLDER . '/' . $size['id'] . '/' . $old_path);
            }
        }
    }

    public function addImages() {
        //If we have p=ending images
        if (Yii::app()->user->hasState('images')) {
            $userImages = Yii::app()->user->getState('images');
            $folder = 'company';
            $pname = toSlug($this->companyname);
            //Now lets create the corresponding models and move the files

            foreach ($userImages as $image) {
                $unique_key = substr(md5(rand(0, 1000000)), 0, 8);
                $filename = $pname . '_' . gen_uuid() . '_' . $unique_key;

                if (is_file($image["path"])) {
                    $this->logo = $folder . '/' . $filename . '.' . strtolower($image["extension"]);
                    $this->isNewRecord = false;
                    $this->saveAttributes(array('logo'));
                    @unlink($image["path"]);
                }

                if (is_file($image["80"])) {

                    $path = IMAGES_FOLDER . DIRECTORY_SEPARATOR . 'img80' . DIRECTORY_SEPARATOR . $folder;


                    if (!(file_exists($path) && ($path))) {
                        mkdir($path, 0777, true);
                    }

                    if (!(file_exists($path . DIRECTORY_SEPARATOR . 'index.html'))) {
                        $fp = fopen($path . DIRECTORY_SEPARATOR . 'index.html', 'w'); // open in write mode.
                        fclose($fp); // close the file.
                    }

                    if (rename($image["80"], $path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]))) {
                        chmod($path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]), 0777);
                    }
                }

                if (is_file($image["100"])) {
                    $path = IMAGES_FOLDER . DIRECTORY_SEPARATOR . 'img100' . DIRECTORY_SEPARATOR . $folder;


                    if (!(file_exists($path) && ($path))) {
                        mkdir($path, 0777, true);
                    }

                    if (!(file_exists($path . DIRECTORY_SEPARATOR . 'index.html'))) {
                        $fp = fopen($path . DIRECTORY_SEPARATOR . 'index.html', 'w'); // open in write mode.
                        fclose($fp); // close the file.
                    }

                    if (rename($image["100"], $path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]))) {
                        chmod($path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]), 0777);
                    }
                }
                if (is_file($image["180"])) {
                    $path = IMAGES_FOLDER . DIRECTORY_SEPARATOR . 'img180' . DIRECTORY_SEPARATOR . $folder;


                    if (!(file_exists($path) && ($path))) {
                        mkdir($path, 0777, true);
                    }

                    if (!(file_exists($path . DIRECTORY_SEPARATOR . 'index.html'))) {
                        $fp = fopen($path . DIRECTORY_SEPARATOR . 'index.html', 'w'); // open in write mode.
                        fclose($fp); // close the file.
                    }

                    if (rename($image["180"], $path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]))) {
                        chmod($path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]), 0777);
                    }
                }


                if (is_file($image["400"])) {

                    $path = IMAGES_FOLDER . DIRECTORY_SEPARATOR . 'img400' . DIRECTORY_SEPARATOR . $folder;


                    if (!(file_exists($path) && ($path))) {
                        mkdir($path, 0777, true);
                    }

                    if (!(file_exists($path . DIRECTORY_SEPARATOR . 'index.html'))) {
                        $fp = fopen($path . DIRECTORY_SEPARATOR . 'index.html', 'w'); // open in write mode.
                        fclose($fp); // close the file.
                    }

                    if (rename($image["400"], $path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]))) {
                        chmod($path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]), 0777);
                    }
                }
            }
            Yii::app()->user->setState('images', null);
        }
    }

    public static function getStringCposition($positionId) {

        $positions = array(
            '1' => Yii::t('AdminCompany', 'Director'),
            '2' => Yii::t('AdminCompany', 'General Manager'),
            '3' => Yii::t('AdminCompany', 'Company Owner'),
            '4' => Yii::t('AdminCompany', 'Sales'),
            '5' => Yii::t('AdminCompany', 'Marketing'),
            '6' => Yii::t('AdminCompany', 'Administration'),
            '7' => Yii::t('AdminCompany', 'Other')
        );

        if ($positionId) {
            return $positions[$positionId];
        } else {
            return '';
        }
    }

    public static function getStringCtype($typeId) {

        $types = array(
            '1' => t('cms', 'Manufacturer'),
            '2' => t('cms', 'Distributor'),
            '3' => t('cms', 'Wholesaler'),
            '4' => t('cms', 'Retailer'),
            '5' => t('cms', 'Service provider'),
            '6' => t('cms', 'Intermediate'),
            '7' => t('cms', 'Importer'),
        );
        if ($typeId) {
            return $types[$typeId];
        } else {
            return '';
        }
    }

    public static function getStringMarkettype($typeId) {

        $types = array(
            '1' => t('cms', 'Italy'),
            '2' => t('cms', 'Vest Europe'),
            '3' => t('cms', 'East/Central Europe'),
            '4' => t('cms', 'Africa'),
            '5' => t('cms', 'North America'),
            '6' => t('cms', 'Sud America'),
            '7' => t('cms', 'Asia'),
            '7' => t('cms', 'Oceania'),
        );
        if ($typeId) {
            return $types[$typeId];
        } else {
            return '';
        }
    }

    public function selectedImage($size, $stripped=false) {
        $path = $this->logo;
        if (empty($path)) {
            $url = IMAGES_URL . "/default/company-default-" . $size . ".jpg";
        } else {
            if ($path && file_exists(IMAGES_FOLDER . "/img" . $size . "/" . $path)) {
                $url = IMAGES_URL . "/img" . $size . "/" . $path;
            } else {
                $url = IMAGES_URL . "/default/company-default-" . $size . ".jpg";
            }
        }
        
        if($stripped==true)
            return $url;
        else 
            return CHtml::image($url, $this->companyname);
    }

}