<?php
	
  //  Secciones de cabecera.
	$opciones_nav = [
		[
			'name' => 'Inicio',
			'link' => 'index.php',
		],
		[
			'name' => 'Videojuegos',
			'link' => 'videojuegos.php'
		],
	];

?>

<nav class="navbar navbar-inverse">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php" style="padding-top: 9px;">
        <p style="font-weight: 1000">
          <img src="favicon.png" alt="Brand" style="width: 40px; height: 34px; margin-right: 6px;">
          Administración
        </p>
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">

      	<?php foreach ($opciones_nav as $opcion): ?>
      		<li>
      			<a href="<?= $opcion['link'] ?>"><?= $opcion['name'] ?></a>
      		</li>
      	<?php endforeach; ?>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>