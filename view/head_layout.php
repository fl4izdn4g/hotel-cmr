<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>HOTEL - Panel administracyjny</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="static/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  
  <!-- Select2 -->
  <link rel="stylesheet" href="static/css/plugins/select2/select2.min.css">
  <!-- Bootstrap3-WysiHtml5 -->
  <link rel="stylesheet" href="static/css/plugins/bootstrap3-wysihtml5/bootstrap3-wysihtml5.min.css">
    
  <!-- Theme style -->
  <link rel="stylesheet" href="static/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="static/css/skins/skin-blue.min.css">
  
  <link rel="stylesheet" href="static/css/base.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script type="text/javascript">
  	function escapeTags( str ) {
	  return String( str )
	           .replace( /&/g, '&amp;' )
	           .replace( /"/g, '&quot;' )
	           .replace( /'/g, '&#39;' )
	           .replace( /</g, '&lt;' )
	           .replace( />/g, '&gt;' );
	}
  </script>
  
  <script type="text/javascript" src="static/js/simple-ajax-uploader/SimpleAjaxUploader.min.js"></script>
  <script type="text/javascript" src="static/js/tinymce/tinymce.min.js"></script>
  
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="static/css/plugins/datepicker/bootstrap-datepicker3.min.css">
  
</head>
<?php 
	$body_class = "hold-transition skin-blue sidebar-mini";
	if(!empty(View::pobierz('body_class'))) {
		$body_class = View::pobierz('body_class');
	}
?>
<body class="<?= $body_class; ?>">