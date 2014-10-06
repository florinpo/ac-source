<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <div class="template-download fade clearfix">
       {% if (file.error) { %}
            <span class="label label-important">{%=locale.fileupload.error%}</span> <span>{%=locale.fileupload.errors[file.error] || file.error%}</span>
        {% } else { %}
            <div class="thumbnail">{% if (file.thumbnail_url) { %}
               <img src="{%=file.thumbnail_url%}">
            {% } %}</div>
        {% } %}
        <div class="delete action-bar clearfix">
             <button class="cancel btn-n grey" data-type="{%=file.delete_type%}" data-url="{%=file.delete_url%}">
                    <span class="inner">
                    <span class="text">
                        <i class="icon-trash icon-grey"></i>
                        {%=locale.fileupload.cancellogo%}
                    </span>
                    </span>
            </button>
        </div>
    </div>
    {% } %}
</script>
