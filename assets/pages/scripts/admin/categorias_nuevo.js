$(document).on('change', '#evento_tipo_grupo_id', function() {
    // tomar el grupo siguiente
    var input = $(this);
    var value = input.val();

    if (value == 1) // si pido cargar un elemento nuevo
    {
        // muestro el campo para poner el nombre del grupo d categorias
        $('#evento_tipo_grupo_nombre').parent().removeClass('hidden');
        $('#evento_tipo_grupo_nombre').focus().select();
    }
    else
    {
        // oculto el campo de grupo de categorias
        $('#evento_tipo_grupo_nombre').parent().addClass('hidden');
        $('#evento_tipo_grupo_nombre').val('');
    }

});