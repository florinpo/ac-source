<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        {% if (file.error) { %}
            <td></td>
            <td class="name"><span>{%=file.name%}</span></td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
            <td class="error" colspan="2"><span class="label label-important">{%=locale.fileupload.error%}</span> {%=locale.fileupload.errors[file.error] || file.error%}</td>
        {% } else { %}
            <td class="preview">{% if (file.thumbnail_url) { %}
                <a href="{%=file.url%}" title="{%=file.name%}" rel="gallery" download="{%=file.name%}"><img src="{%=file.thumbnail_url%}"></a>
            {% } %}</td>
            <td class="name">
               <!-- <a href="{%=file.url%}" title="{%=file.name%}" rel="{%=file.thumbnail_url&&'gallery'%}" download="{%=file.name%}">{%=file.name%}</a>-->
                <span>{%=file.name%}</span>
                
            </td>
            <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
           
        {% } %}
        <td class="delete">
            <button class="btn-t grey" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                <span class="inner">
                    <span class="text">
                        <i class="icon-trash icon-grey"></i>
                        {%=locale.fileupload.destroy%}
                    </span>
                </span>
            </button>
            <?php if ($this->multiple) : ?><input type="checkbox" name="delete" value="1" class="cToogle">
            <?php else: ?><input type="hidden" name="delete" value="1" class="cToogle">
            <?php endif; ?>
        </td>
    </tr>
{% } %}
</script>
