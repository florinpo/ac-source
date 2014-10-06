<?php
 Yii::import("zii.widgets.CBreadcrumbs");
class BreadCrumbs extends CBreadcrumbs {

    public function run() {
        if (empty($this->links))
            return;

        //echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
        $links = array();

        if ($this->homeLink === null)
            $links[] = '<li class="first">' . CHtml::link(Yii::t('zii', 'Home'), Yii::app()->homeUrl, array('id'=>'homelink')) . '</li>';
        else if ($this->homeLink !== false)
            $links[] = '<li><a>' . $this->homeLink . '</a></li>';
        foreach ($this->links as $label => $url) {
            if (is_string($label) || is_array($url))
                $links[] = '<li>' . CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url) . '</li>';
            else
                $links[] = '<li>' . ($this->encodeLabel ? CHtml::encode($url) : $url) . '</li>';
        }
        echo implode($this->separator, $links);
        //echo CHtml::closeTag($this->tagName);
    }

}

?>