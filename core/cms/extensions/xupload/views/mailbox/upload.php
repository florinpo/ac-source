<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        {% if (file.error) { %}
            <td class="error" colspan="2">
                <span class="name">{%=file.name%}</span>
                <span class="label error">{%=locale.fileupload.errors[file.error] || file.error%}</span>
            </td>
            {% } else { %}
       
        <td class="preview">
            <span class="fade"></span>
        </td>
        <td class="name">
            <span>{%=file.name%}</span>
             {% if (!file.error && o.files.valid && !i) { %}
             <div class="progress progress-success progress-striped active"><div class="bar" style="width:0%;"></div></div>
             {% } %}
           
        </td>
        {% } %}
        <td class="size"><span>{%=o.formatFileSize(file.size)%}</span></td>
        <td class="cancel">{% if (!i) { %}
            <button class="btn btn-warning btn-t grey">
                <span class="inner">
                    <span class="text">
                        <i class="icon-ban-circle icon-grey"></i>
                        {%=locale.fileupload.cancel%}
                    </span>
                </span>
            </button>
        {% } %}</td>
    </tr>
{% } %}
</script>
