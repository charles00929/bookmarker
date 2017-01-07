<script src="https://cdn.auth0.com/js/lock/10.6/lock.min.js"></script>
<script>
  var lock = new Auth0Lock('vx2b0X6B0uSNyS3Y4O1PG0EtiKmHnUy2', 'bwtv.au.auth0.com', {
    auth: {
      redirectUrl: 'http://bookmarker.localhost/user/InternalLogin',
      responseType: 'code',
      params: {
        scope: 'openid email' // Learn about scopes: https://auth0.com/docs/scopes
      }
    }
  });
  lock.show();
</script>