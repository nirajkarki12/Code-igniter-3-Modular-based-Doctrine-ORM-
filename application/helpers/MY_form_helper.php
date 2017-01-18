<?php
function get_validation_errors(){
	if($validation_errors = validation_errors('<p>','</p>'))
		echo '<div class="alert alert-danger">'.$validation_errors.'</div>';
}

function get_login_validation_errors(){
	if($validation_errors = validation_errors('<span>','</span>'))
		echo '<div class="alert alert-danger">'.$validation_errors.'</div>';
}

function getMessages(){
	
}