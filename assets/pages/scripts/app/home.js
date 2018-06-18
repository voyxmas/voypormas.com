var Daterange = function(){

    return {
        initDaterange: function() 
        {
            $('input#fecha').daterangepicker({
                "autoUpdateInput": false,
                "alwaysShowCalendars": false,
                "ranges": {
                    'Hoy': [moment()],
                    'Mañana': [moment().add('day',1),moment().add('day',1)],
                    'Esta semana': [moment().startOf('week'), moment().endOf('week')],
                    'Este mes': [moment().startOf('monvth'), moment().endOf('month')],
                    'Proximo mes': [moment().add('month', 1).startOf('month'), moment().add('month', 1).endOf('month')],
                    'Reset': [moment(), moment().add('year', 5).endOf('year')]
                },
                "locale": {
                    "cancelLabel": 'Clear',
                    "format": "DD/MM/YY",
                    "separator": " - ",
                    "applyLabel": "Ok",
                    "cancelLabel": "Cancelar",
                    "fromLabel": "Desde",
                    "toLabel": "Hasta",
                    "customRangeLabel": "Rango",
                    "daysOfWeek": ["Dom","Lun","Mar","Mie","Jue","Vie","Sab"],
                    "monthNames": ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
                    "firstDay": 1
                }
            }, function(start, end, label) {
                $('input#fecha').val(start.format("DD/MM/YY")+' - '+end.format("DD/MM/YY"));
            });

            $('input#fecha').on('cancel.daterangepicker', function() {
                $(this).val('');
            })

            $('[data-range-key=Reset]').click(function () {  setTimeout(function(){$('input#fecha').val('')},1)} );
        },
        init: function() 
        {
            this.initDaterange();
        }
    };

}();


// cargo datepicker para el form de buscqueda
$(document).ready(function(){
    Daterange.init();

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
            data[i].max = $(sliders[i]).data('max') == $(sliders[i]).data('max') ? $(sliders[i]).data('max')+1 : $(sliders[i]).data('max');
            data[i].minselected = $(sliders[i]).data('minselected') == $(sliders[i]).data('minselected') ? $(sliders[i]).data('minselected')+1 : $(sliders[i]).data('minselected');
            data[i].maxselected = $(sliders[i]).data('maxselected') == $(sliders[i]).data('maxselected') ? $(sliders[i]).data('maxselected')+1 : $(sliders[i]).data('maxselected');
            data[i].name = $(sliders[i]).data('name');
            data[i].prefix = $(sliders[i]).data('prefix') != undefined ? $(sliders[i]).data('prefix') : '';
            data[i].sufix = $(sliders[i]).data('sufix') != undefined ? $(sliders[i]).data('sufix') : '';
            data[i].maxinput = $(sliders[i]).data('maxinput');
            data[i].mininput = $(sliders[i]).data('mininput');
        
        // set defaults a los inputs
            $('[name="'+data[i].mininput+'"]').val(data[i].minselected-1);
            $('[name="'+data[i].maxinput+'"]').val(data[i].maxselected-1);
            $('#'+data[i].mininput).text(data[i].prefix +''+ data[i].minselected +''+ data[i].sufix);
            $('#'+data[i].maxinput).text(data[i].prefix +''+ data[i].maxselected +''+ data[i].sufix);
    
        // creo el slider
        noUiSlider.create(sliders[i], {
            start: [ data[i].minselected , data[i].maxselected ],
            format: wNumb({
                decimals: 0
            }),
            range: { min:data[i].min, max:data[i].max },
            connect: true
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
        });

        // actualizoel estado actual segun minselected y maxselected
        
    }
});