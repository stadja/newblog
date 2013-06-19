navigator.id.watch({
  loggedInUser: currentUser,
  onlogin: function(assertion) {
    $.ajax({
      type: 'POST',
      url: '/auth/login', 
      data: {assertion: assertion},
      success: function(res, status, xhr) { window.location.reload(); },
      error: function(xhr, status, err) {
        	navigator.id.logout();
      }
    });
  },
  onlogout: function() {
    $.ajax({
      type: 'POST',
      url: '/auth/logout',
      success: function(res, status, xhr) { window.location.reload(); },
      error: function(xhr, status, err) { }
    });
  }
});

var signinLink = document.getElementById('signin');
if (signinLink) {
  signinLink.onclick = function() { navigator.id.request(); return false;};
}

var signoutLink = document.getElementById('signout');
if (signoutLink) {
  signoutLink.onclick = function() { navigator.id.logout(); return false;};
}