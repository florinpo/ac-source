<?php

/**
 * This is the Widget for Creating new Page 
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package  cmswidgets.page
 *
 */
class CategoryManageWidget extends CWidget {

    public $visible = true;

    //public $message, $category;


    public function init() {
        
    }

    public function run() {
        if ($this->visible) {
            $this->renderContent();
        }
    }

    protected function renderContent()
    { 
        $model_name='ProductSaleCategoryList';
        if($model_name!=''){
            
            $model=new $model_name('search');            
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET[$model_name]))
                    $model->attributes=$_GET[$model_name];                       
             $this->render('cmswidgets.views.product_sale.category_manage_widget',array('model'=>$model));
        } else {
           throw new CHttpException(404,Yii::t('error','The requested page does not exist.'));
        }
    }   

}
