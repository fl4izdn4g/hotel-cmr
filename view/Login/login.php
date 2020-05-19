<?php 

$form_action = array('controller' => 'Login', 'action' => 'login');
$return = View::pobierz('return');
if(!empty($return)) {
	$form_action['return'] = $return;
}

?>


<div class="login-box">
<div class="login-logo">
	<a href="<?= Router::utworz_link(array('controller' => 'Home')); ?>"><b>HOTEL</b></a>
</div>
<!-- /.login-logo -->
<div class="login-box-body">
<p class="login-box-msg lead">Zaloguj się w systemie</p>

<form name="logowanie" action="<?= Router::utworz_link($form_action); ?>" method="post">
	<div class="form-group has-feedback">
		<input name="email" type="email" class="form-control" placeholder="Email">
		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
	</div>
	<div class="form-group has-feedback">
		<input name="password" type="password" class="form-control" placeholder="Hasło">
		<span class="glyphicon glyphicon-lock form-control-feedback"></span>
	</div>
	<div class="row">
		<div class="col-xs-8">
			
		</div>
		<!-- /.col -->
		<div class="col-xs-4">
			<button type="submit" name="logowanie" value="login" class="btn btn-primary btn-block btn-flat">Zaloguj się</button>
		</div>
		<!-- /.col -->
	</div>
</form>

<a href="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'remind')); ?>">Zapomniałem hasła</a><br>
<a href="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'register')); ?>" class="text-center">Utwórz konto</a>

</div>
<!-- /.login-box-body -->
<div class="box-footer">
 	<?php Html::pokaz_alerty(); ?>
</div>
</div>
<!-- /.login-box -->
