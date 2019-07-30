<?php
require_once 'core/init.php';

 if (Session::exists('home')) {
	echo '<p>' . Session::flash('home') . '</p>';
}
//echo Session::get(Config::get('session/session_name'));

$user = new User();
//echo $user->data()->username;

if($user->isLoggedIn()) {
	?>
	<p></p>
	<?php
}

 
 
