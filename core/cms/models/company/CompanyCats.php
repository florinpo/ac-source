<?php

/**
 * This is the model class for table "{{CompanyCats}}".
 *
 * The followings are the available columns in table '{{CompanyCats}}':
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
class CompanyCats extends CActiveRecord {

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return CompanyCats the static model class
     */
    public $parent_id;
    public $order_id;
    //public $categoryId;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{company_cats}}';
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
//            array('slug', 'unique',
//                'attributeName' => 'slug',
//                'className' => 'cms.models.company.CompanyCats',
//                'message' => t('cms','Slug must be uniqued.'),
//            ),
            array('lang', 'numerical', 'integerOnly' => true),
            array('name, slug', 'length', 'max' => 255),
            array('companyId, categoryId, description, parent_id, order_id, guid', 'safe'),
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
            'companies' => array(self::MANY_MANY, 'User', 'gxc_shop_category(categoryId, companyId)'),
            'companiesCount' => array(self::STAT, 'User', 'gxc_company_category(categoryId, companyId)'),
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
            'id' => t('cms', 'ID'),
            'name' => t('cms', 'Name'),
            'description' => t('cms', 'Description'),
            'slug' => t('cms', 'Slug'),
            'lang' => t('cms', 'Language'),
            'parent_id' => t('cms', 'Parent'),
            'order_id' => t('cms', 'Order')
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
         CompanyCategory::model()->deleteAll('categoryId=' . $this->id);
    }

    public function getParents($model, $showRoot=false) {
        $parents = array();

        if ($model->parent) {
            $parents[] = $model->parent;
            $parents = array_merge($model->getParents($model->parent, true), $parents);
        }
        return $parents;
    }

    //public function for recursive lists
    public function getCategoryParents($id=null, $showRoot=false) {
        $childId = ($id === null) ? $owner->getAttribute($this->id) : $id;
        $model = CompanyCats::model()->findByPk($childId);
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

    /*     * * this function is for displaying the rows in gridview hierarchical style** */

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

        $categories = CompanyCats::model()->findAll(array('order' => 'root,lft'));
        $level = 0;
        $data = array('empty' => t('cms', 'Root Node'));

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
        $current = CompanyCats::model()->findByPk($id);
        $level = $current->level;
        $old_parent_id = isset($current->parent) ? $current->parent->id : 'empty';
        $categories = CompanyCats::model()->findAll(array(
            'order' => 'lft',
            'condition' => 'level=:level',
            'params' => array(':level' => $level),
                ));


        $first = array('-1' => t('cms', '-First-'));
        $last = array('-2' => t('cms', '-Last-'));

        if ($categories && count($categories) > 0) {

            if ($old_parent_id !== 'empty') {

                $children = CompanyCats::model()->findByPk($old_parent_id)->children()->findAll();
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
        $domains = CompanyCats::model()->findAll(array('order' => 'root,lft', 'condition' => 'level=1'));
        if ($domains && count($domains) > 0) {
            $data = CHtml::listData($domains, 'id', 'name');
        }
        if ($render) {
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
        } else {
            return $data;
        }
    }

    public static function getCompaniesNumber($id) {
        if (isset($id)) {
            $companies = 0;
            $current_category = CompanyCats::model()->findByPk($id);
            if (count($current_category->descendants()->findAll()) > 0) {
                $children = $current_category->descendants()->findAll();
                foreach ($children as $child) {
                    $companies += $child->companiesCount;
                }
                return $companies;
            } else {
                return $companies = $current_category->companiesCount;
            }
        }
    }

    public function getBreadcrumbs($model, $slug = '', $link = false) {
        $breadcrumbs = array();
        $pageslug = !empty($slug) ? $slug : fn_clean_input($_GET['slug']);

        if (count($model->parent) > 0) {
            $parents = $model->getParents($model);
            $breadcrumbs[$parents[0]->name] = $this->buildLink($pageslug, $parents[0]->slug . '-' . $parents[0]->id);

            $text = ucfirst($this->name);
            if ($link)
                $breadcrumbs[$text] = $this->buildLinkSubcat($pageslug, $parents[0]->slug . '-' . $parents[0]->id, $model->slug . '-' . $model->id);
            else
                $breadcrumbs[] = $text;
        }
        return $breadcrumbs;
    }

    public function buildLink($slug, $cat) {
        if (!empty($slug) && !empty($cat)) {
            return app()->createUrl('site/index', array('slug' => $slug, 'cat' => $cat));
        }
    }
    public function buildLinkSubcat($slug, $cat, $subcat) {
        if (!empty($slug) && !empty($cat)) {
            return app()->createUrl('site/index', array('slug' => $slug, 'cat' => $cat, 'subcat'=>$subcat));
        }
    }

}