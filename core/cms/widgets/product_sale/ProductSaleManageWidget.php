<?php

/**
 * This is the Widget for manage a specific Model.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets
 *
 */
class ProductSaleManageWidget extends CWidget {

    public $visible = true;
    public $model_name = '';
    public $queryParameter = 'q';
    public $sphinx_index = 'products_sale';

    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent() {
        $search = new SiteSearchForm;
        $search->keyword = isset($_GET['q']) ? str_replace('-', ' ', $_GET['q']) : '';
        if (isset($_POST['SiteSearchForm'])) {
            $search->unsetAttributes();
            $search->keyword = $_POST['SiteSearchForm']['keyword'];
            $stringSearch = encode($search->keyword, '-', false);
            Yii::app()->controller->redirect(array('admin', 'q' => $stringSearch));
        }
        $companyId = isset($_GET['comp_id']) ? (int) $_GET['comp_id'] : 0;

        $sort_opt = isset($_POST['SortForm']['option']) ? $_POST['SortForm']['option'] : 1;

        $criteria = new CDbCriteria;
        if ($companyId != 0) {
            $criteria->addCondition('t.companyId=:companyId');
            $criteria->params = array(
                ':companyId' => $companyId,
            );
        }
        $current = 1;
        if ($sort_opt == 1) {
            $criteria->order = 't.create_time DESC';
            $current = $sort_opt;
        } else if ($sort_opt == 2) {
            $criteria->order = 't.name ASC';
            $current = $sort_opt;
        } else if ($sort_opt == 3) {
            $criteria->addCondition('company.has_membership=1');
            //$criteria->order = 'company.has_membership DESC';
            $current = $sort_opt;
        }
        $criteria->with = array('company');
        $criteria->together = true;
        
        $total_found = null;
        if ($this->getQuery() != null) {

            //SphinxSearch criteria
            $searchCriteria = new stdClass();
            $searchCriteria->select = '*';
            $searchCriteria->query = '@(name,tags) ' . $this->getQuery() . '';
            $searchCriteria->from = $this->sphinx_index;
            $searchCriteria->paginator = null;  // we set paginator as null since we do search to determine total found
            
    
           $sphinx = Yii::App()->search;
           $resArray = $sphinx->searchRaw($searchCriteria);
           $total_found = isset($resArray['total_found']) ? $resArray['total_found'] : 0;
           $model = new SphinxDataProvider('ProductSale',
                            array(
                                'criteria' => $criteria, //criteria for AR model
                                'sphinxCriteria' => $searchCriteria, //SphinxSearch critria
                                'pagination' => array(
                                    'pageSize' => (int)(Yii::app()->settings->get('system', 'page_size')),
                                    'pageVar' => 'page',
                                )
                    ));
        } else {

            $model = new CActiveDataProvider('ProductSale', array(
                        'criteria' => $criteria,
                        'pagination' => array(
                            'pageSize' => Yii::app()->settings->get('system', 'page_size'),
                            'pageVar' => 'page',
                        )
                    ));
        }



        $this->render('cmswidgets.views.product_sale.product_sale_manage_widget', array('model' => $model, 'search' => $search, 'total_found'=>$total_found, 'current' => $current));
    }

    public function getQuery() {
        return isset($_REQUEST[$this->queryParameter]) ? $_REQUEST[$this->queryParameter] : null;
    }

    public function getStringStype() {
        $types = array(
            '1' => t('cms', 'Date'),
            '2' => t('cms', 'Name'),
            '3' => t('cms', 'Premium'),
        );
        return $types;
    }

}
