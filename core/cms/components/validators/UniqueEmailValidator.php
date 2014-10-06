<?php

class UniqueEmailValidator extends CUniqueValidator
{
        protected function validateAttribute($object, $attribute)
        {
                $this->criteria = array(
                        'condition' => 't.id != :id',
                        'params' => array(':id' => $object->id)
                );

                return parent::validateAttribute($object, $attribute);
        }
}
?>
