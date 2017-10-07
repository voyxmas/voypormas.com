// selects
$('.selectpicker').selectpicker({
  size: 6
});

// datepickers
$('.datepicker').selectpicker({
  size: 6
});

// sliders
var sliders = $('.nouislider');

// creo todos los sliders basado en los data esperados: name, max, min
var data = [];
for (var i = 0; i < sliders.length; i++) 
{
  // tomo los valores de data
  data[i] = {};
  data[i].min = $(sliders[i]).data('min');
  data[i].max = $(sliders[i]).data('max');
  data[i].name = $(sliders[i]).data('name');
  data[i].prefix = $(sliders[i]).data('prefix') != undefined ? $(sliders[i]).data('prefix') : '';
  data[i].sufix = $(sliders[i]).data('sufix') != undefined ? $(sliders[i]).data('sufix') : '';
  data[i].maxinput = $(sliders[i]).data('maxinput');
  data[i].mininput = $(sliders[i]).data('mininput');

  console.log(data);
  
  // set defaults a los inputs
  $('[name="'+data[i].mininput+'"]').val(data[i].min);
  $('[name="'+data[i].maxinput+'"]').val(data[i].max);
  $('#'+data[i].mininput).text(data[i].prefix +''+ data[i].min +''+ data[i].sufix);
  $('#'+data[i].maxinput).text(data[i].prefix +''+ data[i].max +''+ data[i].sufix);

  // creo el slider
  noUiSlider.create(sliders[i], {
    start: [ data[i].min      , data[i].max      ],
    format: wNumb({
      decimals: 0
    }),
    range: { min: data[i].min , max: data[i].max },
    connect: true,
  });

  // actualizo cuando muevo el slider
  sliders[i].noUiSlider.on('slide', function(values, handle){
    name = handle == 0 ? $($(this)[0].target).data('mininput') : $($(this)[0].target).data('maxinput');
    // actualizo el form hidden
    $('[name="'+name+'"]').val(values[handle]);
    //actualizo los datos visibles
    prefix = $($(this)[0].target).data('prefix') != undefined ? $($(this)[0].target).data('prefix') : '';
    sufix = $($(this)[0].target).data('sufix') != undefined ? $($(this)[0].target).data('sufix') : '';
    $('#'+name).text(prefix+''+values[handle]+''+sufix);
    consol.log(name);
  });

}