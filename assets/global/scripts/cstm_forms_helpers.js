// ajustar la altura de los textfields a su contenido
$('textarea').on('keyup',function(){
	textAreaAdjust(this);
});

// ajusta la altura de un textarea a su contenido
function textAreaAdjust(o) 
{
  o.style.height = "1px";
  o.style.height = (25+o.scrollHeight)+"px";
}