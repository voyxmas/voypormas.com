$(document).on('change', 'input[type=file]', function() {
    // tomar el grupo siguiente
    var input = $(this);
    var id = input.attr('id');

    if (input.files && input.files[0]) 
    {
        innput.files.forEach(function(file) {
            var reader = new FileReader();
            
                reader.onload = function (e) {
                    // get loaded data and render thumbnail.
                    document.getElementById("image").src = e.target.result;
                };
            
                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            
        }, this);
    }

});