<script src="https://cdn.auth0.com/js/lock/10.6/lock.min.js"></script>
<script>
var lock = new Auth0Lock('<?=$config['client_id']?>', '<?=$config['domain']?>', {
	auth: {
		redirectUrl: '<?=$config['callback_url']?>',
		responseType: 'code',
		params: {
			scope: 'openid email' // Learn about scopes: https://auth0.com/docs/scopes
		}
	},
	languageDictionary:{
		title: "Bookmarker"
	},
	theme:{
		logo: "/image/bookmark.png",
		primaryColor:"#31324F"
	}
});
lock.show();
</script>