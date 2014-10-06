<?php

/**
 * This is the Widget for Updating Menu
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package  cmswidgets.object
 *
 */
class MenuUpdateWidget extends CWidget
{
    
    public $visible=true; 
    
    public $form_create_term_url='';
    public $form_update_term_url='';
    public $form_change_order_term_url='';
    public $form_delete_term_url='';
 
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
        $id=isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $model=  GxcHelpers::loadDetailModel('Menu', $id);
            
      
        //Guid of the Object
        $guid=$model->guid;                            
        
        //List of language that should exclude not to translate       
        $lang_exclude=array();
        
        //List of translated versions
        $versions=array();        

        // if it is ajax validation request
        if(isset($_POST['ajax']) && $_POST['ajax']==='menu-form')
        {
                echo CActiveForm::validate($model);
                Yii::app()->end();
        }

        // collect user input data
        if(isset($_POST['Menu']))
        {
                $model->attributes=$_POST['Menu'];                        
                if($model->save()){                            
                    user()->setFlash('success',Yii::t('AdminPage','Update Menu Successfully!'));                                                           
                }
        }

        $this->render('cmswidgets.views.menu.menu_form_widget',array('model'=>$model,'lang_exclude'=>$lang_exclude,'versions'=>$versions
            ));            
        
        
          
    }   
}
