function slug(text) {
    return text
        .toLowerCase()
        .replace(/[^\w ]+/g,'')
        .replace(/ +/g,'-');
}

var App = {
	'init': function() {
		
		/* Setup h2's where slugs will work to jump to them */
		$('h2').each(function() {
			$(this).attr('id', slug($(this).html()));
		});
		
		/* On hash change, jump to #id */
		$(window).bind('hashchange', function() {
			if (window.location.hash.length > 0) {
				var id = window.location.hash;
				$('body,html').animate({scrollTop: $(id).offset().top - 50}, 0);
			}	
		});
		
		/* Check for hash */
		$(window).trigger('hashchange');
	}
};

$(document).ready(function() {
	App.init();
});