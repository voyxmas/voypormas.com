$('#modal_terminos').on('click', '#accept_terms', function() {
    console.log('terminos aceptados');
    $('[name=terminos]').prop('checked', true);
});
$('#modal_terminos').on('click', '#decline_terms', function() {
    console.log('terminos no aceptados');
    $('[name=terminos]').prop('checked', false);
});