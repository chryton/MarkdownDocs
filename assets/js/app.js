function slug(text)
{
    return text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-')
        ;
}

var App = {
	'init': function() {
		$('h2').each(function() {
			$(this).attr('id', slug($(this).html()));
		});
		
		$(window).bind('hashchange', function() {
			if (window.location.hash.length > 0) {
				var id = window.location.hash;
				console.log($(id));
				$('body,html').animate({scrollTop: $(id).offset().top - 50}, 0);
			}	
		});
		
		$(window).trigger('hashchange');
	}
};

$(document).ready(function() {
	App.init();
});