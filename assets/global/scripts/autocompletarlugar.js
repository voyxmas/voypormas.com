// autocomplete lugar
$(document).ready(function(){
    
    var input = document.getElementById('lugar');
    
    var ubicacion = new google.maps.places.Autocomplete(input);
    
    google.maps.event.addListener(ubicacion, 'place_changed', function () {
        
        var address_components = ubicacion.getPlace().address_components;
    
        for(i=0;address_components.length;i++)
        {
            var elemento  = address_components[i].types[0];
            var valor     = address_components[i].long_name;

            console.log(elemento);
        
            // busco numero de casa
            if(elemento == "street_number") $('[name=numero_casa]').val(valor);
            // busco calle
            if(elemento == "route") $('[name=calle]').val(valor);
            // busco ciudad
            if(elemento == "locality") $('[name=ciudad]').val(valor);
            // busco departamento
            if(elemento == "administrative_area_level_2") $('[name=departamento]').val(valor);
            // busco provincia
            if(elemento == "administrative_area_level_1") $('[name=provincia]').val(valor);
            // busco pais
            if(elemento == "country") $('[name=pais]').val(valor);
    
        }
    });

});