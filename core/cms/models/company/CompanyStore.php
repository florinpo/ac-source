<?php

/**
 * This is the model class for table "{{comp_store_layout}}".
 *
 * The followings are the available columns in table '{{comp_store_layout}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property string $image
 */
class CompanyStore extends CActiveRecord {

    public $uploadimg;
    private $_oldSlug = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CompStoreLayout the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{comp_store_layout}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 50, 'min' => '3'),
            array('description', 'length', 'max' => 255),
            array('image, slug', 'safe'),
            array('uploadimg', 'file', 'allowEmpty' => true),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, name, description, slug, image', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'slug' => 'Slug',
            'image' => 'Image',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('image', $this->image, true);

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function afterDelete() {
        parent::afterDelete();

        if ($this->image != null && $this->image != '') {
            $old_path = $this->image;
            //Delete old file Sizes
            $sizes = ImageSize::getStoreSizes();
            foreach ($sizes as $size) {
                if (file_exists(IMAGES_FOLDER . '/' . $size['id'] . '/' . $old_path))
                    @unlink(IMAGES_FOLDER . '/' . $size['id'] . '/' . $old_path);
            }
        }

        if (file_exists(COMMON_FOLDER . '/layouts/store/layouts/' . $this->slug)) {
            recursive_remove_directory(COMMON_FOLDER . '/layouts/store/layouts/' . $this->slug);
        }
    }

    public function afterFind() {
        parent::afterFind();
        $this->_oldSlug = $this->slug;
    }

    public function afterSave() {
        $this->checkFolders();
        $this->addImages();
        parent::afterSave();
    }

    public function addImages() {
        //If we have p=ending images
        if (Yii::app()->user->hasState('images')) {
            $userImages = Yii::app()->user->getState('images');
            $folder = 'store';
            $pname = $this->slug;
            //Now lets create the corresponding models and move the files

            foreach ($userImages as $image) {
                $unique_key = substr(md5(rand(0, 1000000)), 0, 8);
                $filename = $pname . '_' . gen_uuid() . '_' . $unique_key;

                if (is_file($image["path"])) {

                    $this->image = $folder . '/' . $filename . '.' . strtolower($image["extension"]);
                    $this->isNewRecord = false;
                    $this->saveAttributes(array('image'));
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

                if (is_file($image["600"])) {

                    $path = IMAGES_FOLDER . DIRECTORY_SEPARATOR . 'img600' . DIRECTORY_SEPARATOR . $folder;


                    if (!(file_exists($path) && ($path))) {
                        mkdir($path, 0777, true);
                    }

                    if (!(file_exists($path . DIRECTORY_SEPARATOR . 'index.html'))) {
                        $fp = fopen($path . DIRECTORY_SEPARATOR . 'index.html', 'w'); // open in write mode.
                        fclose($fp); // close the file.
                    }

                    if (rename($image["600"], $path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]))) {
                        chmod($path . DIRECTORY_SEPARATOR . $filename . '.' . strtolower($image["extension"]), 0777);
                    }
                }
            }
            Yii::app()->user->setState('images', null);
        }
    }

    public function checkFolders() {
        if ($this->isNewRecord) {

            $path = COMMON_FOLDER . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'store' . DIRECTORY_SEPARATOR . 'layouts';
            if (!(file_exists($path . DIRECTORY_SEPARATOR . $this->slug))) {
                mkdir($path . DIRECTORY_SEPARATOR . $this->slug, 0644, true);
            }
            if (!(file_exists($path . DIRECTORY_SEPARATOR . $this->slug . DIRECTORY_SEPARATOR . 'style.css'))) {
                $fp = fopen($path . DIRECTORY_SEPARATOR . $this->slug . DIRECTORY_SEPARATOR . 'style.css', 'w'); // open in write mode.
                fclose($fp); // close the file.
            }

            if (!(file_exists($path . DIRECTORY_SEPARATOR . $this->slug . DIRECTORY_SEPARATOR . 'index.html'))) {
                $fp = fopen($path . DIRECTORY_SEPARATOR . $this->slug . DIRECTORY_SEPARATOR . 'index.html', 'w'); // open in write mode.
                fclose($fp); // close the file.
            }
        } else {
            $path = COMMON_FOLDER . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'store' . DIRECTORY_SEPARATOR . 'layouts';
            if (file_exists($path . DIRECTORY_SEPARATOR . $this->_oldSlug)) {
                rename($path . DIRECTORY_SEPARATOR . $this->_oldSlug, $path . DIRECTORY_SEPARATOR . $this->slug);
            }
        }
    }
    
    // using for select layouts form
     public static function imageList($filenames) {
        $imageList = array();
        foreach ($filenames as $key => $value) {
            $name = CompanyStore::model()->findByPK($key)->name;
            $imageList[$key] = '<span>'.$name.'</span>'.CHtml::image(IMAGES_URL . '/img100/' . $value,$value);
        }//foreach $filenames
        return $imageList;
    }
    

}