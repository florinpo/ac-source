<?php

class CustomPagerPNCounter extends CLinkPager {

    const CSS_HIDDEN_PAGE = 'hidden';
    const CSS_SELECTED_PAGE = 'current';

    public $nextPageLabel = '';
    public $prevPagelabel = '';
    public $firstPageLabel = '';
    public $lastPageLabel = '';
    public $header = '';
    public $counter = true;

    /**
     * Executes the widget.
     * This overrides the parent implementation by displaying the generated page buttons.
     */
    public function run() {
        $buttons = $this->createPageButtons();
        if (empty($buttons))
            return;
        echo $this->header;
        $list = implode("\n", $buttons);
        echo CHtml::tag('div', $this->htmlOptions, $list);
        echo $this->footer;
    }

    protected function createPageButtons() {
        if (($pageCount = $this->getPageCount()) <= 1)
            return array();

        list($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $pageCount = $this->getPageCount();
        $buttons = array();

        // first page
        // $buttons[] = $this->createPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);

        // prev page
        if (($page = $currentPage - 1) < 0)
            $page = 0;
        $buttons[] = $this->createPageButton($this->prevPageLabel, $page, $this->previousPageCssClass, $currentPage <= 0, false);

        // Pages Display
        if($this->counter==true)
        $buttons[] = $this->createPageDisplay($currentPage + 1, $pageCount);

        // next page
        if (($page = $currentPage + 1) >= $pageCount - 1)
            $page = $pageCount - 1;
        $buttons[] = $this->createPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);

        // last page
        //$buttons[] = $this->createPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);

        return $buttons;
    }

    /**
     * Creates a page button.
     * You may override this method to customize the page buttons.
     * @param string the text label for the button
     * @param integer the page number
     * @param string the CSS class for the page button. This could be 'page', 'first', 'last', 'next' or 'previous'.
     * @param boolean whether this page button is visible
     * @param boolean whether this page button is selected
     * @return string the generated button
     */
    protected function createPageButton($label, $page, $class, $hidden, $selected) {
        if ($hidden || $selected)
            $class.=' ' . ($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
          $link = CHtml::link($label, $this->createPageUrl($page), array('class'=>$class));
        
         //$link = CHtml::ajaxLink($label,$this->createPageUrl($page),array('update'=>'#product-grid'));

         return $link;
    }

    /**
     * Creates page display ex 1/2.
     */
    protected function createPageDisplay($current_page, $total) {
        if($this->counter == true)
        $content = CHtml::openTag('span', array('class' => 'page-display')) . '<span class="curent-page">' . $current_page . '</span>' . ' / ' . '<span class="total">' . $total . '</span>'. CHtml::closeTag('span');
        return $content;
        
    }

}