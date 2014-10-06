<?php

/**
 * This is the model class for table "{{user}}".
 * 
 * @author Tuan Nguyen
 * @version 1.0
 * @package cms.models.user
 *
 * The followings are the available columns in table '{{user}}':
 * @property string $user_id
 * @property string $username
 * @property string $user_url
 * @property string $display_name
 * @property string $password
 * @property string $email
 * @property string $fbuid
 * @property integer $status
 * @property integer $create_time
 * @property integer $update_time
 * @property integer $recent_login
 * @property string $user_activation_key
 * @property integer $confirmed
 */
class User extends CActiveRecord {

    //sorting attributes
    public $role_sort;
    public $companyname_sort;
    public $contactIds;
    public $spamIds;

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.


        return array(
            array('username, email, password', 'required'),
            //email must be Unique if it is on Create Scenairo
            array('email', 'unique',
                'attributeName' => 'email',
                'className' => 'cms.models.user.User',
                'message' => t('cms', 'Username has been registered.'),
            ),
            //username must be Unique if it is on Create Scenairo
            array('username', 'unique',
                'attributeName' => 'username',
                'className' => 'cms.models.user.User',
                'message' => t('cms', 'Username has been registered.'),
            ),
            array('status', 'numerical', 'integerOnly' => true),
            array('username, password, salt, email', 'length', 'max' => 128),
            array('user_activation_key', 'length', 'max' => 255),
            array('email_recover_key', 'length', 'max' => 255),
            array('display_name, salt, create_time, update_time, recent_login, confirmed', 'safe'),
            array('user_type', 'in', 'range' => array('0', '1', '2')),
            array('has_membership', 'in', 'range' => array('0', '1')),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('user_id, username, display_name, email, status, create_time, update_time, recent_login, user_type, role_sort, companyname_sort, membership_sort, username', 'safe', 'on' => 'search'),
        );
    }

    public function behaviors() {
        return array(
            'CAdvancedArBehavior' => array(
                'class' => 'cms.extensions.behaviors.CAdvancedArBehavior',
            ),
            "APasswordBehavior" => array(
                "class" => "APasswordBehavior",
                //"autoUpgrade"=>true,
                "defaultStrategyName" => "bcrypt",
                "strategies" => array(
                    "bcrypt14" => array(
                        "class" => "ABcryptPasswordStrategy",
                        "workFactor" => 14
                    ),
                    "bcrypt" => array(
                        "class" => "ABcryptPasswordStrategy",
                        "workFactor" => 12
                    ),
                ),
            )
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.

     
            $relations = array();
            $relations['profile'] = array(self::HAS_ONE, 'UserProfile', 'userId');
            $relations['settings'] = array(self::HAS_ONE, 'UserSettings', 'userId');
            $relations['cprofile'] = array(self::HAS_ONE, 'UserCompanyProfile', 'companyId');
            $relations['cshop'] = array(self::HAS_ONE, 'UserCompanyShop', 'companyId');
            $relations['csettings'] = array(self::HAS_ONE, 'UserCompanySettings', 'companyId');
            $relations['contacts'] = array(self::HAS_MANY, 'ContactList', 'owner_id');
            $relations['spammers'] = array(self::HAS_MANY, 'MailboxSpam', 'user_id');
            $relations['favproducts'] = array(self::MANY_MANY, 'ProductSale', 'gxc_favorite_product(userId, productId)');
            $relations['favshops'] = array(self::MANY_MANY, 'UserCompanyShop', 'gxc_favorite_product(userId, shopId)');
            $relations['role'] = array(self::HAS_MANY, 'AuthAssignment', 'userid');
            $relations['membership'] = array(self::HAS_ONE, 'MembershipInfo', 'user_id');

           

        return $relations;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => t('cms', 'User'),
            'username' => t('cms', 'Username'),
            'user_url' => t('cms', 'User Url'),
            'display_name' => t('cms', 'Display Name'),
            'password' => t('cms', 'Password'),
            'email' => t('cms', 'Email'),
            'status' => t('cms', 'Status'),
            'create_time' => t('cms', 'Created Time'),
            'update_time' => t('cms', 'Updated Time'),
            'recent_login' => t('cms', 'Recent Login'),
            'user_activation_key' => t('cms', 'User Activation Key'),
            'has_membership' => t('cms', 'Membership'),
            'companyname_sort' => t('cms', 'Company name'),
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
        $criteria->with = array('cprofile', 'role');

        if ($_GET['user_type'] == 0) {
            $criteria->condition = 'user_type=0';
        } else if ($_GET['user_type'] == 1) {
            if (isset($_GET['cat'])) {
                $current_cat = CompanyCats::model()->findByPk((int) $_GET['cat']);
                $root = $current_cat->root;
                $descendants = $current_cat->descendants()->findAll();

                $lft_attr = $current_cat->lft;
                $rgt_attr = $current_cat->rgt;
                $criteria->select = 't.*';
                $criteria->join = 'INNER JOIN gxc_company_category AS c ON c.companyId = t.user_id INNER JOIN gxc_company_cats AS node ON node.id = c.categoryId';
                if (count($descendants) > 0) {
                    $criteria->condition = 'user_type=1 AND node.root = :root AND node.lft > :lft AND node.rgt < :rgt';
                } else {
                    $criteria->condition = 'user_type=1 AND node.root = :root AND node.lft = :lft AND node.rgt = :rgt';
                }

                $criteria->params = array(':root' => $root, ':lft' => $lft_attr, ':rgt' => $rgt_attr,);
                $criteria->group = 't.user_id';
            } else {
                $criteria->condition = 'user_type=1';
            }
        } else {
            $criteria->condition = '';
        }

        $criteria->together = true;
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('display_name', $this->display_name, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('salt', $this->salt, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('has_membership', $this->has_membership);
        $criteria->compare('create_time', $this->create_time);
        $criteria->compare('update_time', $this->update_time);
        $criteria->compare('recent_login', $this->recent_login);
        $criteria->compare('role.itemname', $this->role_sort, true);
        $criteria->compare('cprofile.companyname', $this->companyname_sort, true);



        $sort = new CSort;
        $sort->attributes = array(
            'user_id',
            'status',
            'has_membership',
            'role_sort' => array(
                'asc' => 'role.itemname',
                'desc' => 'role.itemname DESC',
            ),
        );
        $sort->defaultOrder = 't.user_id DESC';

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort,
                ));
    }

    public function afterFind() {
       
        if (!empty($this->contacts)) {
            foreach ($this->contacts as $n => $contact)
                $this->contactIds[] = $contact->contact_id;
        }
        if (!empty($this->spammers)) {
            foreach ($this->spammers as $n => $spammer)
                $this->spamIds[] = $spammer->spammer_id;
        }
        parent::afterFind();
    }

    /**
     * This is invoked before the record is saved.
     * @return boolean whether the record should be saved.
     */
    protected function beforeSave() {
        if (parent::beforeSave()) {
            $this->email = strtolower($this->email);
            $this->username = strtolower($this->username);
            if ($this->isNewRecord) {
                $this->create_time = $this->update_time = $this->recent_login = time();
                //$this->password = VieHashing::hash($this->password);    
            } else {
                $this->update_time = time();
            }

            return true;
        }
        else
            return false;
    }

    //Do Clear Session after Save
    protected function afterSave() {
        parent::afterSave();

        if (($this->user_id == user()->id) && ($this->scenario == 'update')) {
            //If this user updated his own settings, changed the session of him	            
            $command = Yii::app()->db->createCommand();
            $command->select('username,display_name,email,status,recent_login, user_type, confirmed, has_membership')->from('{{user}} u')
                    ->where('user_id=' . (int) $this->user_id)
                    ->limit(1);
            $user = $command->queryRow();
            //Add only some neccessary field
            if ($user) {
                // Set User States here
                Yii::app()->user->setState('current_user', $user);
            }
        }
    }

    /**
     * Delete information of the User with Afer Delete
     */
    protected function afterDelete() {
        parent::afterDelete();
        AuthAssignment::model()->deleteAll('userid = :uid', array(':uid' => $this->user_id));
        UserProfile::model()->deleteAll('userid = :uid', array(':uid' => $this->user_id));
        UserCompanyProfile::model()->deleteAll('companyId = :cid', array(':cid' => $this->user_id));
        UserCompanyShop::model()->deleteAll('companyId = :cid', array(':cid' => $this->user_id));
        UserSettings::model()->deleteAll('userid = :uid', array(':uid' => $this->user_id));
        UserCompanySettings::model()->deleteAll('companyId = :cid', array(':cid' => $this->user_id));
        MembershipOrder::model()->deleteAll('company_id = :uid', array(':uid' => $this->user_id));
        if (PrivateMessage::model()->findAll(array('condition' => 'sender_id=:userId', 'params' => array(':userId' => $this->user_id)))) {
            PrivateMessage::model()->updateAll(array('senderDeleted' => 1), 'sender_id=:userId', array(':userId' => $this->user_id));
        }
        CompanyCategory::model()->deleteAll('companyId = :companyId', array(':companyId' => $this->user_id));
        ProductSale::model()->deleteAll('companyId = :companyId', array(':companyId' => $this->user_id));
    }

    /**
     * Public Function retrun String Full name
     * @return string full name
     */
    public function getFull_Name() {
        if ($this->user_type == ConstantDefine::USER_NORMAL) {
            if ($this->user_id !== '1' && !empty($this->profile->firstname) && !empty($this->profile->lastname)) {
                $fullname = $this->profile->firstname . ' ' . $this->profile->lastname;
            } else {
                $fullname = $this->display_name;
            }
        } else if ($this->user_type == ConstantDefine::USER_COMPANY) {
            if (!empty($this->cprofile->firstname) && !empty($this->cprofile->lastname)) {
                $fullname = $this->cprofile->firstname . ' ' . $this->cprofile->lastname;
            } else {
                $fullname = $this->display_name;
            }
        }
        return $fullname;
    }

    /**
     * Static Function retrun String Roles of the User
     * @param bigint $uid
     * @return string
     */
    public static function getStringRoles($uid = 0) {

        if ($uid) {
            $roles = Rights::getAssignedRoles($uid, true);
            $res = array();
            foreach ($roles as $r) {
                $res[] = $r->name;
            }
            if (count($res) > 0)
                return implode(",", $res);
            else
                return '';
        }
        return '';
    }

    /**
     * Static Function retrun Array Roles of the User
     * @param bigint $uid
     * @return string
     */
    public static function getArrayRoles($uid = 0) {
        $res = array();
        if ($uid) {
            $roles = Rights::getAssignedRoles($uid, true);
            $res = array();
            foreach ($roles as $r) {
                $res[] = $r->name;
            }
        }
        return $res;
    }

    /**
     * Static Function retrun String Membership of the User
     * @param $membershipId
     * @return string
     */
    public static function getStringMemberships($membershipId) {
        $memberships = MembershipItem::model()->findAll('id = :id', array(':id' => $membershipId));
        $res = array();
        foreach ($memberships as $m) {
            $res[] = $m->title;
        }
        if (count($res) > 0)
            return implode(",", $res);
        else
            return '';
    }

    /**
     * Return the String to the image
     * @param CActiveRecord $data
     * @return string
     */
    public static function convertUserState($data) {
        if ($data->status == ConstantDefine::USER_STATUS_ACTIVE) {
            $image = 'active';
        } else if ($data->status == ConstantDefine::USER_STATUS_INACTIVE) {
            $image = 'inactive';
        } else {
            $image = 'banned';
        }
        return bu() . '/images/' . $image . '.png';
    }

    public function clearImagesSession() {
        if (Yii::app()->user->hasState('imagesProduct')) {
//            $userImages = Yii::app()->user->getState('imagesProduct');
//            foreach ($userImages as $k => $image) {
//
//                if (is_file($image['path'])) {
//                    unlink($image['path']);
//                }
//                if (is_file($image['100'])) {
//                    unlink($image['100']);
//                }
//                if (is_file($image['180'])) {
//                    unlink($image['180']);
//                }
//                if (is_file($image['400'])) {
//                    unlink($image['400']);
//                }
//            }
            //Yii::app()->user->setState('imagesProduct', null);
        }
    }

}