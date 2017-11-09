// ajustar la altura de los textfields a su contenido
$(document).on('keyup','textarea',function(){
	textAreaAdjust(this);
});

// ajusta la altura de un textarea a su contenido
  function textAreaAdjust(o) 
  {
    o.style.height = "1px";
    o.style.height = (3+ o.scrollHeight)+"px";
  }

// formbuilderhelper
  function addRow(selector) 
  {
    var table = $(selector);
    // clonar la ultima fila
    var row = table.find('.body.table-row').first().clone();
    // reset values
    row.find('input').val('');
    row.find('textarea').val('');
    // agregar
    table.append(row);

    $('[data-toggle="tooltip"]').tooltip(); 
  }

  function remRow(selector) 
  {
    var table = $(selector);
    // clonar la ultima fila
    table.find('.body.table-row').last().remove();
  }

  function addColumn(selector)
  {
    var table = $(selector);
    // agregarselo a las rows en header
    table.find('.table-row').each(function(){
      // tomar la columna a clonar en header
      var col = $(this).find('.table-cell').last().clone();
      //reset value
      col.find('input').val('');
      col.find('textarea').val('');
      // append
      $(this).append(col);

      $('[data-toggle="tooltip"]').tooltip(); 
    });
  }

  function remColumn(selector)
  {
    var table = $(selector);
    // agregarselo a las rows en header
    table.find('.table-row').each(function(){
      // tomar la columna a clonar en header
      var col = $(this).find('.table-cell').last().remove();
    });
  }

$(document).ready(function() {
  // dinamic table
    // get max/min x elements
  var max_x = $('.gridinput .table').data('maxxelements');
  var min_x = $('.gridinput .table').data('minxelements');
  var num_x = min_x; // valor inicial del contador

    // get max/min y elements
  var max_y = $('.gridinput .table').data('maxyelements');
  var min_y = $('.gridinput .table').data('minyelements');
  var num_y = min_y; // valor inicial del contador

  // ver si miestro boton de quitar filas columnas
  if(min_x < 2) // oculto el boton qutiar columna
    $('a.rem-column').fadeOut();
  
  if(min_y < 2) // oculto el boton qutiar fila
    $('a.rem-row').fadeOut();

  // no mostrar el boton de quitar un elemento por defecto
  $('.form_builder_helper_add_one_less').fadeOut();

  $('a.add-row').click(function() {
    num_y++;
    if(num_y <= max_y || max_y == 0) // ver si se alcanzo l maximo de lemento o si es infinito
    {
      var addtoid = $(this).data('addtoid');
      addRow('#'+addtoid);
      // fade in rem row
      $(this).siblings(".rem-row").fadeIn();
    }
    else
    {
      $(this).fadeOut();
    }
  });

  $('a.rem-row').click(function() {
    if(num_y > 1)
    {
      var remtoid = $(this).data('remtoid');
      remRow('#'+remtoid);
      num_y--;
      
      if(num_y == 1) // si es el ultimo elemento sacar el bonton
        $(this).fadeOut();
    }
  });
  
  $('a.add-column').click(function() {
    num_x++;
    if(num_x <= max_x || max_x == 0)
    {
      var addtoid = $(this).data('addtoid');
      addColumn('#'+addtoid);
      // fade in rem column
      $(this).siblings(".rem-column").fadeIn();
    }
    else
    {
      $(this).fadeOut();
    }
  });

  $('a.rem-column').click(function() {
    if(num_x > 1)
    {
      var remtoid = $(this).data('remtoid');
      remColumn('#'+remtoid);
      num_x--;
      
      if(num_x == 1) // si es el ultimo elemmento sacar el boton
        $(this).fadeOut();
    }
  });

  $(document).on('click', '.form_builder_helper_add_one_more', function(event) {
    // tomar el grupo siguiente
    var label = $(this).parent();
    var parent = label.parent();
    var next = label.next();
    var clone = next.clone();
    parent.append(clone);
    $(this).siblings('.form_builder_helper_add_one_less').fadeIn();
  });

  $(document).on('click', '.form_builder_helper_add_one_less', function(event) {
    // tomar el grupo siguiente, buscar por input-group-z si existe o input-group-z-element para asegurar que funcione siempre
    if($(this).parent().parent().find('.input-group-z').length > 0)
      var items = $(this).parent().parent().find('.input-group-z');
    else
      var items = $(this).parent().parent().find('.input-group-z-element');
    // quito el ultimo elemento
    items.last().remove();
    // veo cuantos quedan en el grupo de elementos
    var number_b = $(this).parent().parent().find('.input-group-z').size();
    // si es el último, saco el botón
    if(number_b < 2)
      $(this).fadeOut();
  });

  $('[data-toggle="tooltip"]').tooltip(); 
  
});
