$(function(){
	$('#login_form').submit(function(){
		var pw = $(this).find('[name=password]');
		pw.val(md5(pw.val()));
		return true;
	});
});