{{ DCZ|json }}
<div class="dcz-container" data-id="{{ DCZ['id']|attr }}">
{% for dcz in DCZ['comment'] %}
<div class="dcz-item">
    {{ dcz['text'] }}
</div>
{% endfor %}
<div class="dcz-item">
    <div class="dcz-form">
        <div class="form-line">
            <textarea class="textbox role-dcz-input"></textarea>
        </div>
        <div class="form-line">
            <input type="button" class="button button-def role-dcz-submit" value="Submit">
        </div>
    </div>
</div>
</div>
