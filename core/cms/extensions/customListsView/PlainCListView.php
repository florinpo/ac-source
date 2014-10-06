<?php

Yii::import('zii.widgets.CListView');

class PlainCListView extends CListView {

    public $preItemsTag = '';
    public $postItemsTag = '';

    public function renderItems() {
       
        $data = $this->dataProvider->getData();
        if (($n = count($data)) > 0) {
            echo $this->preItemsTag . "\n";
            $owner = $this->getOwner();
            $render = $owner instanceof CController ? 'renderPartial' : 'render';
            $j = 0;
            foreach ($data as $i => $item) {
                $data = $this->viewData;
                $data['index'] = $i;
                $data['data'] = $item;
                $data['widget'] = $this;
                $owner->$render($this->itemView, $data);
                if ($j++ < $n - 1)
                    echo $this->separator;
            }
            echo $this->postItemsTag . "\n";
        }
        else 
            $this->renderEmptyText();
        
    }

}

?>