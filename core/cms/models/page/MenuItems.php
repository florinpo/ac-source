<?php

/**
 * This is the model class for table "{{menu_items}}".
 *
 * The followings are the available columns in table '{{menu_items}}':
 * @property integer $id
 * @property integer $menu_id
 * @property string $name
 * @property string $lft
 * @property string $rgt
 * @property integer $level
 * @property string $description
 * @property string $type
 */
class MenuItems extends CActiveRecord {

    public $parent_id;
    public $order_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return MenuItems the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{menu_items}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('menu_id, name, type, value', 'required'),
            array('menu_id', 'numerical', 'integerOnly' => true),
            array('name, type', 'length', 'max' => 255),
            array('description, parent_id, order_id', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, menu_id, name, description, type, value', 'safe', 'on' => 'search'),
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

    public function behaviors() {
        return array(
            'NestedSetBehavior' => array(
                'class' => 'cms.extensions.behaviors.NestedSetBehavior',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
                'hasManyRoots' => true
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('Menu', 'ID'),
            'menu_id' => Yii::t('Menu', 'Menu'),
            'name' => Yii::t('Menu', 'Name'),
            'description' => Yii::t('Menu', 'Description'),
            'type' => Yii::t('Menu', 'Type'),
            'value' => Yii::t('Menu', 'Value'),
            'parent_id' => Yii::t('Menu', 'Parent'),
            'order_id' => Yii::t('Menu', 'Order')
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

        if (isset($_GET['menu']))
            $menu = isset($_GET['menu']) ? (int) ($_GET['menu']) : 0;

        if ($menu != 0) {
            $criteria->addCondition('menu_id=:menuId');
            $criteria->params = array(
                ':menuId' => $menu,
            );
        }

        $criteria->addCondition('level > 1');

        $criteria->compare('id', $this->id);
        $criteria->compare('menu_id', $this->menu_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('lft', $this->lft, true);
        $criteria->compare('rgt', $this->rgt, true);
        $criteria->compare('level', $this->level);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('type', $this->type, true);
        $criteria->order = $this->hasManyRoots ? $this->rootAttribute . ', ' . $this->leftAttribute : $this->leftAttribute;

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    /*     * * this function is to display the parents in the drop down** */

    public static function getOptions($menuId, $id = false, $children = false) {

        $items = MenuItems::model()->findAll(array(
            'condition'=>'menu_id=:menuId',
            'params'=>array(':menuId'=>$menuId),
            'order' => 'root, lft'));
        //we start from level 2 since level 1 will be the automatic root
        $level = 2;
        $data = array('empty' => Yii::t('Menu', 'Root Node'));

        if ($items && count($items) > 0) {
            foreach ($items as $t) {

                if ($t->level == $level) {
                    $data[$t->id] = $t->name;
                } else if ($t->level > $level) {
                    $data[$t->id] = str_repeat("- ", $t->level - 2) . $t->name;
                }
                if ($children) {
                    foreach ($children as $c) {
                        unset($data[$c->id]);
                    }
                }
            }
        }

        if ($id) {
            $data = array_diff($data, array($data[$id]));
        }
        return $data;
    }

    /*     * * this function is to display the siblings in the drop down for the order** */

    public static function getSiblings($id, $render = true) {
        $current = MenuItems::model()->findByPk($id);
        $level = $current->level;
        $old_parent_id = isset($current->parent) ? $current->parent->id : 'empty';
        $categories = MenuItems::model()->findAll(array(
            'order' => 'lft',
            'condition' => 'level=:level',
            'params' => array(':level' => $level),
                ));


        $first = array('-1' => Yii::t('UserProfile', '-First-'));
        $last = array('-2' => Yii::t('UserProfile', '-Last-'));

        if ($categories && count($categories) > 0) {

            if ($old_parent_id !== 'empty') {

                $children = MenuItems::model()->findByPk($old_parent_id)->children()->findAll();
                $data = CMap::mergeArray($first, CHtml::listData($children, 'id', 'name'), $last);
            } else {

                $data = CMap::mergeArray($first, CHtml::listData($categories, 'id', 'name'), $last);
            }
        }
        if ($render) {
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            return $data;
        }
    }

    /*     * * this function is for displaying the rows in gridview hierarchical style** */

    public function getChildren($parent, $level = 0) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'lft=:id AND level > 2';
        $criteria->params = array(':id' => $parent);
        $model = $this->findAll($criteria);
        foreach ($model as $key) {
            return str_repeat(' |— ', $level - 2);
            $this->getChildren($key->id, $level + 2);
        }
    }

    public static function ReBindValueForMenuType($type, $value) {

        $result = '';
        switch ($type) {
            case ConstantDefine::MENU_TYPE_PAGE:
                $page = Page::model()->findByPk($value);
                if ($page)
                    $result = $page->name;
                break;
            case ConstantDefine::MENU_TYPE_TERM:
                $term = Term::model()->findByPk($value);
                if ($term)
                    $result = $term->name;

                break;
            
        }

        return $result;
    }

}