if (!navigator.onLine) {
  $('#connectionOrAdd').hide();
}

$('#login').click(function() {
	if ($('#login').html() == '+') {
		$('#login').html('-');
		$('#loginForm').show();
	} else {
		$('#login').html('+');
		$('#loginForm').hide();
	}
});

var loginSubmit = document.getElementById('loginSubmit');
if (loginSubmit) {
  	loginSubmit.onsubmit = function() {
		$.ajax({
	      type: 'POST',
	      url: '/auth/login', 
	      data: {email: $('#email').val(), 'password': $('#password').val()},
	      success: function(res, status, xhr) { window.location.reload(); }
	    });
  		return false;
  	}
}

var logoutLink = document.getElementById('logout');
if (logoutLink) {
  logoutLink.onclick = function() {
    $.ajax({
      type: 'POST',
      url: '/auth/logout',
      success: function(res, status, xhr) { window.location.reload(); },
      error: function(xhr, status, err) { }
    });
  }
}