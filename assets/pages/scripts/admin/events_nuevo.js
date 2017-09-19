$(document).on('click', '.form_builder_helper_add_one_more', function(event) {
  // tomar el grupo siguiente
  var label = $(this).parent();
  var parent = label.parent();
  var next = label.next();
  var clone = next.clone();
  parent.append(clone);
});