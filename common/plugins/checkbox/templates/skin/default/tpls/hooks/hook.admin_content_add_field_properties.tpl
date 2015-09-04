{literal}
<script>
$(function () {
    $('#field_type').off().on('change', function () {
        var selected = $(this).val(),
            inputval = $('#select_inputval')
        ;

        admin.onSelectField.apply(this);
        if ('checkbox' == selected) {
            inputval.show();
        }

        return false;
    })
    .trigger('change');
});
</script>
{/literal}
