<?php

/**
 * This is the model class for table "{{ProductSaleCategoryList}}".
 *
 * The followings are the available columns in table '{{ProductSaleCategoryList}}':
 * @property string $id
 * @property string $taxonomy_id
 * @property string $root
 * @property string $lft
 * @property string $rgt
 * @property integer $level
 * @property string $name
 * @property string $description
 * @property string $slug
 */
class ProductSaleCategoryList extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ProductSaleCategoryList the static model class
     */
    public $parent_id;
    public $order_id;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{product_sale_cats}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name, slug', 'required'),
            //Slug must be uniqued
            array('slug', 'unique',
                'attributeName' => 'slug',
                'className' => 'cms.models.product.ProductSaleCategoryList',
                'message' => Yii::t('ProductSaleCategoryList', 'Slug must be uniqued.'),
            ),
            array('lang', 'numerical', 'integerOnly' => true),
            array('name, slug', 'length', 'max' => 255),
            array('productId, categoryId, description, parent_id, order_id, guid', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, lang,  name, description, slug', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'products' => array(self::MANY_MANY, 'ProductSale', 'gxc_product_sale_category(categoryId, productId)'),
            'productsCount' => array(self::STAT, 'ProductSale', 'gxc_product_sale_category(categoryId, productId)'),
            'language' => array(self::BELONGS_TO, 'Language', 'lang'),
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
            'CAdvancedArBehavior' => array(
                'class' => 'cms.extensions.behaviors.CAdvancedArBehavior',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => Yii::t('ProductSaleCategoryList', 'ID'),
            'name' => Yii::t('ProductSaleCategoryList', 'Name'),
            'description' => Yii::t('ProductSaleCategoryList', 'Description'),
            'slug' => Yii::t('ProductSaleCategoryList', 'Slug'),
            'lang' => Yii::t('ProductSaleCategoryList', 'Language'),
            'parent_id' => Yii::t('ProductSaleCategoryList', 'Parent'),
            'order_id' => Yii::t('ProductSaleCategoryList', 'Order')
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
        $criteria->compare('name', $this->name, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('lang', $this->lang, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->order = $this->hasManyRoots ? $this->rootAttribute . ', ' . $this->leftAttribute : $this->leftAttribute;

        return new CActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                if ($this->guid == '') {
                    $this->guid = uniqid();
                }
            }
            return true;
        }
        else
            return false;
    }

    protected function afterDelete() {
        parent::afterDelete();
        if ($this->productsCount > 0) {
            ProductSaleCategory::model()->deleteAll('categoryId=' . $this->id);
        }
    }

    public function getParents($model) {
        $parents = array();
        if ($model->parent) {
            $parents[] = $model->parent;
            $parents = array_merge($model->getParents($model->parent, true), $parents);
        }
        return $parents;
    }

    //public function for recursive lists
    public function getCategoryParents($id = null, $showRoot = false) {
        $childId = ($id === null) ? $owner->getAttribute($this->id) : $id;
        $model = ProductSaleCategoryList::model()->findByPk($childId);
        if ($model === null)
            return null;
        $items = array();
        foreach ($model->getParents($model, $showRoot) as $parent)
            $items[] = $this->formatLabel($parent);
        if ($items !== array())
            $items[] = $this->formatLabel($model);
        return implode(' > ', $items);
    }

    public function formatLabel($model) {
        if ($model->name != null)
            $label = $model->name;
        return $label;
    }

    public function getChildren($parent, $level = 0) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'lft=:id';
        $criteria->params = array(':id' => $parent);
        $model = $this->findAll($criteria);
        foreach ($model as $key) {
            return str_repeat(' |— ', $level - 1);
            $this->getChildren($key->id, $level + 1);
        }
    }

    public static function getOptions($id = false, $children = false) {

        $categories = ProductSaleCategoryList::model()->findAll(array('order' => 'root,lft'));
        $level = 0;
        $data = array('empty' => Yii::t('Region', 'Root Node'));

        if ($categories && count($categories) > 0) {
            foreach ($categories as $t) {

                if ($t->level == $level) {
                    $data[$t->id] = $t->name;
                } else if ($t->level > $level) {
                    $data[$t->id] = str_repeat("- ", $t->level - 1) . $t->name;
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

    public static function getSiblings($id, $render = true) {
        $current = ProductSaleCategoryList::model()->findByPk($id);
        $level = $current->level;
        $old_parent_id = isset($current->parent) ? $current->parent->id : 'empty';
        $categories = ProductSaleCategoryList::model()->findAll(array(
            'order' => 'lft',
            'condition' => 'level=:level',
            'params' => array(':level' => $level),
                ));


        $first = array('-1' => Yii::t('UserProfile', '-First-'));
        $last = array('-2' => Yii::t('UserProfile', '-Last-'));

        if ($categories && count($categories) > 0) {

            if ($old_parent_id !== 'empty') {

                $children = ProductSaleCategoryList::model()->findByPk($old_parent_id)->children()->findAll();
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

    public static function getDomains($render = true) {
        $domains = ProductSaleCategoryList::model()->findAll(array('order' => 'root,lft', 'condition' => 'level=1'));

        if ($domains && count($domains) > 0) {
            $data = CHtml::listData($domains, 'id', 'name');
        } else {
            $data = array();
        }
        if ($render) {
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            return $data;
        }
    }

    public static function countCatProducts($id) {
        if (isset($id)) {
            $products = 0;
            $current_category = ProductSaleCategoryList::model()->findByPk($id);
            if (count($current_category->descendants()->findAll()) > 0) {
                $children = $current_category->descendants()->findAll();
                foreach ($children as $child) {
                    $products += $child->productsCount;
                }
                return $products;
            } else {
                return $products = $current_category->productsCount;
            }
        }
    }

    public function getBreadcrumbs($model) {
        $breadcrumbs = array();
        $pageslug = plaintext($_GET['slug']);

        if (count($model->parent) > 0) {
            $parents = $model->getParents($model);
            $breadcrumbs[$parents[0]->name] = $this->buildLink($pageslug, $parents[0]->slug . '-' . $parents[0]->id);
            $text = ucfirst($this->name);
            $breadcrumbs[] = $text;
        }
        return $breadcrumbs;
    }

    public function buildLink($slug, $cat) {
        if (!empty($slug) && !empty($cat)) {
            return app()->createUrl('page/render', array('slug' => $slug, 'cat' => $cat));
        }
    }

}