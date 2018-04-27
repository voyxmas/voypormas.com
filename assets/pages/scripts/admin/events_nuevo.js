

/* Cuando agrego una variante del evento, agragar un gurpo de inputos para los premios */
  /* Usar un modelo para clonar el elemento en el dom */
$(document).on('click','.rem-row',function(){
  // borro el ultimo elemento si quito la fila
  $('.form-group.premios').last().remove();
});

$(document).on('click','.add-row',function(){
  // cargar un nuevo grupo de premios
  var otro_grupo_de_premios = $('.form-group.premios').last().clone();
  otro_grupo_de_premios.children('label').children('span').text('Premios');
  // saco los valores al formulario
  // otro_grupo_de_premios.find('.form-control').val('');
  // saco todos menos el primer input-group-z
  otro_grupo_de_premios.find('.input_bag-z > .input-group-z:not(:first-child)');
  // insertar el elemento despues del ultio de este tipo
  otro_grupo_de_premios.insertAfter($('.form-group.premios').last());
  
  var index = otro_grupo_de_premios.index();
  // saco el badge
  otro_grupo_de_premios.eq(index).text('Premios');
});

$(document).on('click','.add-premio',function(){
  // mostrar el pop up
  console.log('add premio');
  var index = $(this).index('.add-premio');
  var popup = $(".popup.premios").eq(index);
  // hago visible el que intento abrir
  setShowSatate(popup);
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
  var contenedor = $(this).parent().parent();
  var premios = contenedor.find('.input-group-z'); 
  var numero = premios.length;

  setHideState(contenedor); // cierro el pop up
  // tomar el indice
  var index = contenedor.index('.form-group.premios') ;
  console.log('Add premio index: '+index);
  console.log('Add premio count: '+numero);

  // cambiar el numero
  $('.add-premio').eq(index).html('Premios <input name="premios_cnt[]" type="text" readonly class="badge badge-success" value="'+numero+'">');
});

function setHideState(jElement)
{
  jElement.removeClass('bounceInDown'); // cierro el pop up
  jElement.addClass('bounceOutUp'); // cierro el pop up
  setTimeout(function() {
    jElement.addClass('hide');
    jElement.removeClass('bounceOutUp');
  }
  , 500);
}

function setShowSatate(jElement)
{
  // cierro los pop ups que esten abiertos antes
  $(".form-group.premios").addClass('hide');
  jElement.removeClass('hide');
  jElement.addClass('bounceInDown');
}