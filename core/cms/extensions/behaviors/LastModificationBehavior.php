<?php

/**
 * LastModificationBehavior class file.
 */
class LastModificationBehavior extends CActiveRecordBehavior {
    
    public function afterSave($event) {
        $this->updateCacheDependencies();
        //parent::afterDelete($event);
        return true;
    }

    public function afterDelete($event) {
       $this->updateCacheDependencies();
       //parent::afterDelete($event);
        return true;
    }
    
    protected function updateCacheDependencies() {
        //Update timestamps on related models so that view caches get updated
        $cacheUpdates = array();
       
        $cacheUpdates[] = 'Cache.'.$this->owner->tableSchema->name;
        $relations = $this->owner->relations();
        foreach ($relations as $relation)
            $cacheUpdates[] = 'Cache.' .$this->owner->tableSchema->name .'.' . strtolower($relation[1]);

        foreach ($cacheUpdates as $cacheUpdate) {
            Yii::app()->setGlobalState($cacheUpdate, time());
            Yii::app()->saveGlobalState();
        }
    }

}
