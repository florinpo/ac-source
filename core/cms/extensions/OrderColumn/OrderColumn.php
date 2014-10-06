<?php

class OrderColumn extends CGridColumn {

    public $ajaxUrl;
    public $pk;
    public $cssClass = 'order_link';
    public $name;
    public $actionUrl;
    private $_upIcon;
    private $_downIcon;

    public function init() {
        $assetsDir = dirname(__FILE__) . "/assets";
        $gridId = $this->grid->getId();



        $this->_upIcon = Yii::app()->assetManager->publish($assetsDir . "/up.png");
        $this->_downIcon = Yii::app()->assetManager->publish($assetsDir . "/down.png");

        Yii::app()->clientScript->registerCoreScript('jquery');

        $script = <<<SCRIPT
            $(document).ready(function() {
                $('.{$this->cssClass}').live('click', function(e) {
                    var link    = $(this).attr('href');
                    $.ajax({
                        cache: false,
                        dataType: 'json',
                        type: 'get',
                        url: link,
                        success: function(data) {
                            \$.fn.yiiGridView.update('$gridId');
                        }

                    });
                    return false;
                });

            });
SCRIPT;

        Yii::app()->clientScript->registerScript(__CLASS__ . "#{$this->cssClass}", $script, CClientScript::POS_END);
        //Yii::app()->clientScript->registerCssFile(Yii::app()->assetManager->publish($assetsDir . "/orderColumn.css"));
    }

    public function renderDataCellContent($row, $data) {
        $value = CHtml::value($data, $this->name);
        $this->ajaxUrl['pk'] = $data->primaryKey;
        $this->ajaxUrl['name'] = $this->name;
        $this->ajaxUrl['value'] = $value;

        $this->ajaxUrl['move'] = 'up';
        $this->ajaxUrl['move'] = 'down';
        
        $previous_node = CActiveRecord::model(get_class($data))->find(array('condition' => 'position < :position', 'params' => array(':position' => $value)));
        $next_node = CActiveRecord::model(get_class($data))->find(array('condition' => 'position > :position', 'params' => array(':position' => $value)));


        if ($previous_node == null && $next_node == null) {
            $up = '';
            $down = '';
        } else if ($previous_node == null) {
            $this->ajaxUrl['move'] = 'down';
            $up = '';
            $down = CHtml::link(CHtml::image($this->_downIcon), $this->ajaxUrl, array('class' => $this->cssClass.' down'));
        } else if ($next_node == null) {
            $this->ajaxUrl['move'] = 'up';
            $up = CHtml::link(CHtml::image($this->_upIcon), $this->ajaxUrl, array('class' => $this->cssClass.' up'));
            $down = '';
        } else {
            $this->ajaxUrl['move'] = 'up';
            $up = CHtml::link(CHtml::image($this->_upIcon), $this->ajaxUrl, array('class' => $this->cssClass.' up'));
            $this->ajaxUrl['move'] = 'down';
            $down = CHtml::link(CHtml::image($this->_downIcon), $this->ajaxUrl, array('class' => $this->cssClass.' down'));
        }
        echo $up;
        echo $down;
    }

}

?>