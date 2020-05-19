<div class="login-box">
<div class="login-logo">
<a href="<?= Router::utworz_link(array('controller' => 'Home')); ?>"><b>HOTEL</b></a>
</div>
<!-- /.login-logo -->
<div class="login-box-body">
<p class="login-box-msg lead">Przypomnij hasło</p>

<form name="logowanie" action="<?= Router::utworz_link(array('controller' => 'Login', 'action' => 'remind')); ?>" method="post">
	<p>Wpisz adres email zarejestrowany w systemie, na który przyjdzie wiadomość umożliwiająca zresetowanie hasła</p>
	<div class="form-group has-feedback">
		<input name="email" type="email" class="form-control" placeholder="Email">
		<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<button type="submit" name="przypomnij" value="remind" class="btn btn-primary btn-block btn-flat">Przypomnij hasło</button>
		</div>
		<!-- /.col -->
	</div>
</form>
</div>
<!-- /.login-box-body -->
<div class="box-footer">
 	<?php Html::pokaz_alerty(); ?>
</div>
</div>
<!-- /.login-box -->
