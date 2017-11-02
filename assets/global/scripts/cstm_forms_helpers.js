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

    $('a.add-row').click(function() {
      num_y++;
      if(num_y <= max_y || max_y == 0)
      {
        var addtoid = $(this).data('addtoid');
        addRow('#'+addtoid);
      }
      else
      {
        $(this).fadeOut();
      }
    });
    $('a.add-column').click(function() {
      num_x++;
      if(num_x <= max_x || max_x == 0)
      {
        var addtoid = $(this).data('addtoid');
        addColumn('#'+addtoid);
      }
      else
      {
        $(this).fadeOut();
      }
    });
    $(document).on('change','.input-fecha',function(){
      // verificar los datos
    });

    $('[data-toggle="tooltip"]').tooltip(); 
    
  });
