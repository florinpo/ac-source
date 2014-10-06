<?php

/**
 * This is the Widget for Creating new Menu Item
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package  cmswidgets.page
 *
 *
 */
class MenuItemsAdminWidget extends CWidget
{
    
    public $visible=true;  
    
   
 
    public function init()
    {
        
    }
 
    public function run()
    {
        if($this->visible)
        {
            $this->renderContent();
        }
    }
    
    protected function renderContent()
    { 
        $model_name='MenuItems';
        if($model_name!=''){
            $model=new $model_name('search');            
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET[$model_name]))
                    $model->attributes=$_GET[$model_name];                       
             $this->render('cmswidgets.views.menuitems.menu_items_manage_widget',array('model'=>$model));
        } else {
           throw new CHttpException(404,Yii::t('error','The requested page does not exist.'));
        }
    }     
    
    
}
