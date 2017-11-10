

/* Cuando agrego una variante del evento, agragar un gurpo de inputos para los premios */
  /* Usar un modelo para clonar el elemento en el dom */
$('.add-row').click();
$(document).on('click','.rem-row',function(){
  // borro el ultimo elemento si quito la fila
  $('.form-group.premios').last().remove();
});

$(document).on('click','.add-row',function(){
  // cargar un nuevo grupo de premios
  var otro_grupo_de_premios = $('.form-group.premios').last().clone();
  otro_grupo_de_premios.children('label').children('span').text('Premios');
  // insertar el elemento despues del ultio de este tipo
  otro_grupo_de_premios.insertAfter($('.form-group.premios').last());
});

$(document).on('click','.add-premio',function(){
  // mostrar el pop up
  var index = $(this).index('.add-premio');
  var popup = $(".popup.premios").eq(index);
  // cierro los pop ups que esten abiertos antes
  $(".form-group.premios").addClass('hide');
  // hago visible el que intento abrir
  popup.removeClass('hide');
  // si no tiene ya el boton para cerrar agregarlo
  if(popup.find('.closebtn').length == 0)
  {
    // creo el el boton
    popup.children('label').append('<div class="closebtn btn green btn-sm">Gurdar</div>');
  }
});

// cuando cambia el nombre de una varian  te llevarla al label de los premios en timepo real keyUp()
$(document).on('keyup','.vdistancia',function(){
  // tomo el valor de la distancia y el numero de indice para asociar el elemento correcto de premio
  var value = $(this).val();
  var index = $(this).index('.vdistancia');
  // cambio el nombre del labels
  $('.form-group.premios').eq(index).children('label').children('span').text('Premios: '+value+'kms');
});

// close popupclosebtn
$(document).on('click','.form-group.premios > label > .closebtn ',function(){
  $(this).parent().parent().addClass('hide');

});