<?php

    class CustomButtonColumn extends CButtonColumn {
        
      
        /**
	 * Initializes the column.
	 * This method registers necessary client script for the button column.
	 */
	public function init()
	{
		$this->initDefaultButtons();
                

		foreach($this->buttons as $id=>$button)
		{
			if(strpos($this->template,'{'.$id.'}')===false)
				unset($this->buttons[$id]);
			elseif(isset($button['click']))
			{
				if(!isset($button['options']['class']))
					$this->buttons[$id]['options']['class']=$id;
                                if(!isset($button['options']['iclass']))
					$this->buttons[$id]['options']['iclass']=$id;
				if(!($button['click'] instanceof CJavaScriptExpression))
					$this->buttons[$id]['click']=new CJavaScriptExpression($button['click']);
			}
		}

		$this->registerClientScript();
	}
        
 
        /**
	 * Renders a link button.
	 * @param string $id the ID of the button
	 * @param array $button the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
	 * See {@link buttons} for more details.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data object associated with the row
	 */
	protected function renderButton($id,$button,$row,$data)
	{
		if (isset($button['visible']) && !$this->evaluateExpression($button['visible'],array('row'=>$row,'data'=>$data)))
  			return;
		$label=isset($button['label']) ? $button['label'] : $id;
		$url=isset($button['url']) ? $this->evaluateExpression($button['url'],array('data'=>$data,'row'=>$row)) : '#';
		//$button['options']['iclass'] = 'icon'; 
                $options=isset($button['options']) ? $button['options'] : array();
                //$iclass = isset($button['options']['iclass']) ? $button['options']['iclass'] : 'icon';
		if(!isset($options['title']))
			$options['title']=$label;
                if(!isset($options['iclass']))
			$options['iclass']='icon';
		//echo CHtml::link($label,$url,$options);
                echo CHtml::link('<span class="inner"><span class="text"><span class="'.$options['iclass'].'"></span>'.$label.'</span></span>',$url,$options);
               
	}
 
    }

?>