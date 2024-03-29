<?php

/**
 * Class of parent Controller for Front end of GXC CMS, extends from RController
 * 
 * 
 * @author Tuan Nguyen <nganhtuan63@gmail.com>
 * @version 1.0
 * @package cms.components
 */
class FeController extends RController {

    public $description;
    public $keywords;
    public $change_title = false;
    public $data = array();

    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
    }

    public function init() {
        // register class paths for extension captcha extended
        Yii::$classMap = array_merge(Yii::$classMap, array(
            'CaptchaExtendedAction' => Yii::getPathOfAlias('cms.components.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedAction.php',
            'CaptchaExtendedValidator' => Yii::getPathOfAlias('cms.components.captchaExtended') . DIRECTORY_SEPARATOR . 'CaptchaExtendedValidator.php'
                ));
    }

    /**
     * Filter by using Modules Rights
     * 
     * @return type 
     */
    public function filters() {
        return array(
            'rights'
        );
    }
    
    public function filterCacheInit($filterChain) {
        $caches = Yii::app()->db->createCommand()
                ->select('table_name, last_modification')
                ->from('gxc_table_modifications')
                ->queryAll();
        $cachesArray = array();
        foreach ($caches as $key => $cache) {
            $cachesArray[$cache['table_name']] = $cache['last_modification'];
        }
        //Note I'm using an intermediate array because Yii::app()->params
        // is a virtual attribute so you can't assign data as we are doing above
        Yii::app()->params["lastModifications"] = $cachesArray;
        $filterChain->run();
    }

    public function renderPageSlug($slug) {

        $connection = Yii::app()->db;
        $command = $connection->createCommand('SELECT * FROM {{page}} WHERE slug=:slug limit 1');
        $command->bindValue(':slug', $slug, PDO::PARAM_STR);
        $page = $command->queryRow();
        if ($page) {
            //We first need to check if having Ajax Request
            if (isset($_REQUEST['ajax']) && strpos($_REQUEST['ajax'], ConstantDefine::AJAX_BLOCK_SEPERATOR) !== false) {
                $ajax = explode(ConstantDefine::AJAX_BLOCK_SEPERATOR, plaintext($_REQUEST['ajax']));
                $block_id = $ajax[1];
                $id = $ajax[0];
                $block_ini = parse_ini_file(Yii::getPathOfAlias('common.blocks.' . $id) . DIRECTORY_SEPARATOR . 'info.ini');

                //Include the class            
                Yii::import('common.blocks.' . $id . '.' . $block_ini['class']);
                $layout_asset = GxcHelpers::publishAsset(Yii::getPathOfAlias('common.layouts.' . $page['layout'] . '.assets'));
                //Get the Block
                $command = $connection->createCommand('SELECT b.block_id,b.name,b.type,b.params FROM 
                        {{block}} b                        
                        WHERE b.block_id=:bid
                        Limit 1');
                $command->bindValue(':bid', $block_id, PDO::PARAM_INT);
                $block = $command->queryRow();
                if ($block !== false) {
                    $this->widget('common.blocks.' . $id . '.' . $block_ini['class'], array('block' => $block, 'page' => $page, 'layout_asset' => $layout_asset));
                } else {
                    echo '';
                }
                Yii::app()->end();
            } else {
                $this->layout = 'main';
                $this->pageTitle = $page['title'];
                $this->description = $page['description'];
                $this->keywords = $page['keywords'];
                //depend on the layout of the page, use the corresponding file to render                  
                $this->renderPage('common.layouts.' . $page['layout'] . '.' . $page['display_type'], array('page' => $page));
            }
        } else {
            throw new CHttpException('404', t('cms', 'Oops! Page not found!'));
        }
    }

    public function renderPage($view, $data = null, $return = false) {

        if ($this->beforeRender($view)) {

            if (($layoutFile = $this->getLayoutFile($view)) !== false) {
                $output = $this->renderFile($layoutFile, array('page' => $data['page']), true);
            }

            $this->afterRender($view, $output);

            $output = $this->processOutput($output);

            if ($return)
                return $output;
            else
                echo $output;
        }
    }

    public function afterRender($view, &$output) {
        Yii::app()->clientScript->registerMetaTag($this->description, 'description');
        Yii::app()->clientScript->registerMetaTag($this->keywords, 'keywords');
        //Check if change Title, we will replace content in <title> with new Title
        if ($this->change_title) {
            $output = replaceTags('<title>', '</title>', $this->pageTitle, $output);
        }
    }

}