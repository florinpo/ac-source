<?php

Yii::import('zii.widgets.grid.CGridView');

class FXSGridView extends CGridView {

    public $headerCssClass = 'columnHeadings';
    //public $itemsCssClass = 'grey';
    public $rowCssClassExpression = '$this->rowCssClassFunction($row, $data);';
    public $rowCssClass = array('odd', 'even');

    public function rowCssClassFunction($row, $data) {
        $classes = array();

        if ($row == 0)
            $classes [] = 'first';
        if ($row == $this->dataProvider->getItemCount() - 1)
            $classes [] = 'last';

        // Do flip/flop on defined rowCssClass
        if (is_array($this->rowCssClass) && !empty($this->rowCssClass))
            $classes [] = $this->rowCssClass[$row % count($this->rowCssClass)];

        return empty($classes) ? false : implode(' ', $classes);
    }

    public function renderTableHeader() {
        if (!$this->hideHeader) {
            echo "<thead>\n";

            if ($this->filterPosition === self::FILTER_POS_HEADER)
                $this->renderFilter();

            echo '<tr class="' . $this->headerCssClass . ' ">' . "\n";
            foreach ($this->columns as $column)
                $column->renderHeaderCell();
            echo "</tr>\n";

            if ($this->filterPosition === self::FILTER_POS_BODY)
                $this->renderFilter();

            echo "</thead>\n";
        }
        else if ($this->filter !== null && ($this->filterPosition === self::FILTER_POS_HEADER || $this->filterPosition === self::FILTER_POS_BODY)) {
            echo "<thead>\n";
            $this->renderFilter();
            echo "</thead>\n";
        }
    }

}

?>