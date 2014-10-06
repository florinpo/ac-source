<?php

class SphinxDataProvider extends CDataProvider
{
        /**
         * @var string the primary ActiveRecord class name. The {@link getData()} method
         * will return a list of objects of this class.
         */
        public $modelClass;
        /**
         * @var CActiveRecord the AR finder instance (eg <code>Post::model()</code>).
         * This property can be set by passing the finder instance as the first parameter
         * to the constructor. For example, <code>Post::model()->published()</code>.
         * @since 1.1.3
         */
        public $model;
        /**
         * @var string the name of key attribute for {@link modelClass}. If not set,
         * it means the primary key of the corresponding database table will be used.
         */
        public $keyAttribute;
        
        private $_sort;
        private $_criteria; //criteria for select ActiveRecord object
        private $_sphinxCriteria; //sphinxsearch criteria for search
        private $_fieldWeights; //sphinxsearch criteria for search

        /**
         * Constructor.
         * @param mixed $modelClass the model class (e.g. 'Post') or the model finder instance
         * (e.g. <code>Post::model()</code>, <code>Post::model()->published()</code>).
         * @param array $config configuration (name=>value) to be applied as the initial property values of this class.
         */
        public function __construct($modelClass,$config=array())
        {
                if(is_string($modelClass))
                {
                        $this->modelClass=$modelClass;
                        $this->model=CActiveRecord::model($this->modelClass);
                }
                else if($modelClass instanceof CActiveRecord)
                {
                        $this->modelClass=get_class($modelClass);
                        $this->model=$modelClass;
                }
                
                $this->setId($this->modelClass);
                foreach($config as $key=>$value)
                        $this->$key=$value;
        }

        /**
         * Returns the query criteria.
         * @return CDbCriteria the query criteria
         */
        public function getCriteria()
        {
                return $this->_criteria;
        }
        /**
         * Sets the query criteria.
         * @param mixed $value the query criteria. This can be either a CDbCriteria object or an array
         * representing the query criteria.
         */
        public function setCriteria($value)
        {
                $this->_criteria=$value;
        }
        /**
         * Returns the sphinx query criteria.
         * @return stdClass the sphinx query criteria
         */
        public function getSphinxCriteria()
        {
                return $this->_sphinxCriteria;
        }

        /**
         * Sets the sphinx criteria.
         * @param mixed $value the sphinx criteria. This can be either a stdClass object
         * representing the sphinx criteria.
         */
        public function setSphinxCriteria($value)
        {
                $this->_sphinxCriteria=$value;
        }
        

        

        /**
         * Returns the sorting object.
         * @return CSort the sorting object. If this is false, it means the sorting is disabled.
         */
        public function getSphinxSort()
        {
                if(($sort=parent::getSort())!==false)
                        $sort->modelClass=$this->modelClass;
                return $sort;
        }

        /**
         * Fetches the data from the persistent data storage.
         * @return array list of data items
         */
        protected function fetchData()
        {
                $criteria = clone $this->getCriteria();
                $sphinxCriteria = clone $this->getSphinxCriteria();
                
                if(($pagination=$this->getPagination())!==false)
                {
                        $pagination->setItemCount(2000);
                        $pagination->applyLimit($sphinxCriteria);
                        $sphinxCriteria->paginator = $pagination;
                }

                if(($sort=$this->getSort())!==false)
                        $sort->applyOrder($sphinxCriteria);
                
                //var_dump($sphinxCriteria); exit;
                
                $sphinx = Yii::App()->search;
                $sphinx->setMatchMode(SPH_MATCH_EXTENDED2);
                $resArray = $sphinx->searchRaw($sphinxCriteria);
                
                $total_found = isset($resArray['total_found']) ? $resArray['total_found'] : 0;
                $pagination->setItemCount($total_found);
                //var_dump($resArray); exit();
                
                $values = array(0);
                if(!empty($resArray['matches']))
                {
                        foreach($resArray['matches'] as $k => $v)
                                array_push($values, $k);
                }
                //var_dump($values); 
                if(!empty($values)) {
                        $resCriteria = new CDbCriteria();
                        $resCriteria->addInCondition('t.'.$this->model->getMetaData()->tableSchema->primaryKey, $values);
                        $criteria->mergeWith($resCriteria);
                }
                //$criteria->order = 't.'.$this->model->getMetaData()->tableSchema->primaryKey.' DESC';
                //var_dump($criteria); exit();
                $data=$this->model->findAll($criteria);
                return $data;
        }
        /**
         * Returns the sort object.
         * @return CSort the sorting object. If this is false, it means the sorting is disabled.
         */
        public function getSort()
        {
                if($this->_sort===null)
                {
                        $this->_sort=new SphinxSort;
                        if(($id=$this->getId())!='')
                                $this->_sort->sortVar=$id.'_sort';
                }
                return $this->_sort;
        }

        /**
         * Fetches the data item keys from the persistent data storage.
         * @return array list of data item keys.
         */
        protected function fetchKeys()
        {
                $keys=array();
                foreach($this->getData() as $i=>$data)
                {
                        $key=$this->keyAttribute===null ? $data->getPrimaryKey() : $data->{$this->keyAttribute};
                        $keys[$i]=is_array($key) ? implode(',',$key) : $key;
                }
                return $keys;
        }

        /**
         * Calculates the total number of data items.
         * @return integer the total number of data items.
         */
        protected function calculateTotalItemCount()
        {
                $baseCriteria=$this->model->getDbCriteria(false);
                if($baseCriteria!==null)
                        $baseCriteria=clone $baseCriteria;
                $count=$this->model->count($this->getCriteria());
                $this->model->setDbCriteria($baseCriteria);
                return $count;
        }
}
