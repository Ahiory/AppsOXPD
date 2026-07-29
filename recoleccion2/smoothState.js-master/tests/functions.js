;(function($) {
'use strict';
var $body = $('html, body'),
content = $('#main').smoothState({
// Sobreescribimos el método onStart
onStart: {
duration: 250, // Duración de la animación
render: function (url, $container) {
// toggleAnimationClass() es una clase pública
// para reiniciar la transición CSS con una clase
// y aplicar el efecto inverso al salir de la página
content.toggleAnimationClass('is-exiting');
// Hacemos scroll al principio de la página
$body.animate({
scrollTop: 0
});
}
}
}).data('smoothState');
//.data('smoothState') es para habilitar los métodos públicos
})(jQuery);
