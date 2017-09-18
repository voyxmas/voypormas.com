$("form.ajax_call").on('submit',function(event)
{
	event.preventDefault();
	
	// disable submit
	var disabled = $(this).find("[type='submit']");
	disabled.prop('disabled',true);

	$.ajax({
		url: $(this).attr("action"),
		type: $(this).attr("method"),
		data: $(this).serialize(),
		beforeSend:function(){
			$(".loader").show();
		},
    	success:function(data){
			do_response(data);
    	},
	    complete: function(){
	    	disabled.prop('disabled',false);
	    }
	});
});

$("a.ajax_call").on('click',function(event)
{
	event.preventDefault();

	// get data
	var data = $(this).data();

	// get value from other source if defined
	if (typeof(data.valuesource) !== 'undefined')
	{
		var value = $(data.valuesource).val();
		var name  = $(data.valuesource).attr('name');
		data[name] = value;
		console.log($(this).data());
	}

	var datas = data;

	$.ajax({
		url: $(this).attr("href"),
		data: datas,
		type: 'POST',
		beforeSend:function(){
			$(".loader").show();
		},
    	success:function(data){
			do_response(data);
	    },
	    complete: function(){
	    }
	});
});

function do_response(data) 
{
	$(".loader").fadeOut("slow");

	var obj 	= JSON.parse(data);
	var data  	= obj.data;

	/*
	ESTARiA BUENO ENVIAR LASACCIONES COMO UN ARRAY PARA PODER ENVIAR MAS DE UNA POR RESPUESTA
	ESTARIA BUENO PODER MANDAR LAS VARIABLES DE CONFIGURACION DE CADA PLUGIN TAMBIEN
	*/

	// check si esta definido do after
	if ( typeof(obj) !== 'undefined' || typeof(obj.do_after) !== 'undefined')
		var action_delay = typeof(obj.do_after.action_delay) !== 'undefined' ? obj.do_after.action_delay : 0 ;
	// redirect
	if( typeof(obj.do_after.redirect) !== 'undefined')
		window.setTimeout(function(){window.location.href = obj.do_after.redirect;}, action_delay);
	// recarga la pagina
	if( typeof(obj.do_after.reload) !== 'undefined')
		window.setTimeout(function(){location.reload();}, action_delay);
	// execute function with data
	if( typeof(obj.do_after.callback) !== 'undefined')
		window.setTimeout(function(){executeFunctionByName(obj.do_after.callback, window, data)}, action_delay);

	// metodos no afectos por el time action_delay
	// messages
	if( typeof(obj.do_after.alert) !== 'undefined')
		bootbox.alert(obj.do_after.alert); // plugin
	if( typeof(obj.do_after.toastr) !== 'undefined')
		toastr[obj.do_after.toastr_type](obj.do_after.toastr); // plugin
	// console log
	if( typeof(obj.do_after.console) !== 'undefined')
		console.log(obj.do_after.console);
}

function executeFunctionByName(functionName, context, args) 
{
  var args = [].slice.call(arguments).splice(2);
  var namespaces = functionName.split(".");
  var func = namespaces.pop();
  
  for(var i = 0; i < namespaces.length; i++) 
  {
    context = context[namespaces[i]];
  }

  // ver si la funcion esta definida
  if (typeof(context[func]) === 'undefined')
  {
  	console.log('executeFunctionByName no encontro la funcion requerida');
  }

  return context[func].apply(context, args);
}

var CFG = {
	 url: 'http://zigna.dev',
	 token: ''
};