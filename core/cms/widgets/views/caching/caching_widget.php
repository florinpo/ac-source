<div class="form">
<?php $this->render('cmswidgets.views.notification'); ?>
<a class="button" href="<?php echo bu();?>/caching/cachemanagement?cache_id=backend_assets"><?php echo t('cms','Clear BACKEND Assets'); ?></a>
<a class="button" href="<?php echo bu();?>/caching/cachemanagement?cache_id=backend_cache"><?php echo t('cms','Clear BACKEND Cache'); ?></a>
<a class="button" href="<?php echo bu();?>/caching/cachemanagement?cache_id=frontend_assets"><?php echo t('cms','Clear FRONTEND Assets'); ?></a>
<a class="button" href="<?php echo bu();?>/caching/cachemanagement?cache_id=frontend_cache"><?php echo t('cms','Clear FRONTEND Cache'); ?></a>
<a class="button" href="javascript:void(0)" 
   onclick="popupFS('<?php echo bu(); ?>/cache/apc/', 'APC Cache Management');"><?php echo t('cms','Manage APC Cache'); ?></a>
</div>

<?php
app()->clientScript->registerScriptFile(bu() . '/js/main-frame.js', CClientScript::POS_HEAD);
?>

