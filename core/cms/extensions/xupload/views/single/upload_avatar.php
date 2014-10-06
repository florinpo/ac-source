<?php
$layout_asset = GxcHelpers::publishAsset(Yii::getPathOfAlias('common.layouts.default.assets'));
$default = $layout_asset . '/images/entries/shops/180/no-image.png';
?>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-upload fade clearfix">
        {% if (file.error) { %}
        <span class="error label">{%=locale.fileupload.errors[file.error] || file.error%}</span>
        {% } else { %}
        <div class="clear"></div>
        <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
        {% } %}
    </div>
    {% } %}
</script>
