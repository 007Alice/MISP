<?php
echo $this->Session->flash('auth');
echo $this->Form->create('User', array('action' => 'login'));
echo $this->Form->inputs(array(
	'legend' => __('Login', true),
	'email' => array('autocomplete' => 'off'),
	'password' => array('autocomplete' => 'off')
));

echo $this->Form->end('Login');