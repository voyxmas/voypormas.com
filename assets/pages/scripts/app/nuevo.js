$('#modal_terminos').on('click', '#accept_terms', function() {
    $('[name=terminos]').prop('checked', true);
});
$('#modal_terminos').on('click', '#decline_terms', function() {
    $('[name=terminos]').prop('checked', false);
});
$(document).on('change','.toggle-publico > input[type=checkbox]', function(){
    var status  = $(this).prop('checked');
    var target  = $(this).parent().next();

    if(status)
        target.val(1);
    else
        target.val(0);
});