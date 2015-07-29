jQuery(function($){
	$('.loadmoreAjax').click(function(){
    	var el = $(this);
		$.post(ajaxurl+'?action=get_posts_ajax',{args: el.attr('data-args')},function(response){
			if(response.args) el.attr('data-args', response.args);			
			else el.hide();
			$('#ajaxOut').append(response.html);
		},'json');
    	return false;
    });
});