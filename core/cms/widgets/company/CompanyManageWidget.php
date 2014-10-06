<?php

/**
 * This is the Widget for manage a specific Model.
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cmswidgets
 *
 */
class CompanyManageWidget extends CWidget {

    public $visible = true;
    public $model_name = '';
    public $queryParameter = 'q';
    public $sphinx_index = 'companies';

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
        $criteria->addCondition('t.user_type='.ConstantDefine::USER_COMPANY);
        $current = 1;
        if ($sort_opt == 1) {
            $criteria->order = 't.create_time DESC';
            $current = $sort_opt;
        } else if ($sort_opt == 2) {
            $criteria->order = 'cprofile.companyname ASC';
            $current = $sort_opt;
        } else if ($sort_opt == 3) {
            $criteria->addCondition('t.has_membership=1');
            $current = $sort_opt;
        }
        
        $criteria->with = array('cprofile');
        $criteria->together = true;

        $total_found = null;
        if ($this->getQuery() != null) {

            //SphinxSearch criteria
            $searchCriteria = new stdClass();
            $searchCriteria->select = '*';
            $searchCriteria->query = '@(companyname,services) ' . $this->getQuery() . '';
            $searchCriteria->from = $this->sphinx_index;
            $searchCriteria->paginator = null;

            $sphinx = Yii::App()->search;
            $resArray = $sphinx->searchRaw($searchCriteria);
            $total_found = isset($resArray['total_found']) ? $resArray['total_found'] : 0;
            
            $model = new SphinxDataProvider('User',
                            array(
                                'criteria' => $criteria, //criteria for AR model
                                'sphinxCriteria' => $searchCriteria, //SphinxSearch critria
                                'pagination' => array(
                                    'pageSize' => (int)(Yii::app()->settings->get('system', 'page_size')),
                                    'pageVar' => 'page',
                                )
                    ));
        }
        else {

            $model = new CActiveDataProvider('User', array(
                        'criteria' => $criteria,
                        'pagination' => array(
                            'pageSize' => Yii::app()->settings->get('system', 'page_size'),
                            'pageVar' => 'page',
                        )
                    ));
        }


        $this->render('cmswidgets.views.company.company_manage_widget', array('model' => $model, 'search' => $search, 'total_found'=>$total_found,  'current' => $current));
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
