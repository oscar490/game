<?php

	include("includes/doc_header.php");

	include("includes/site_header.php");

	$juego = Doctrine_Query::create()->from("Videojuegos v")
		->where("v.id = ?", $_GET['id'])
		->execute()
		->getFirst();

	$plataforma = Doctrine_Query::create()->from("Plataformas p")
		->where("p.id = ?", $juego->plataforma_id)
		->execute()
		->getFirst();

	$genero = Doctrine_Query::create()->from("Generos g")
				->where("g.id = ?", $juego->genero_id)
				->execute()
				->getFirst();

	
?>
<style>
	.col-md-4 > img {
		width: 300px;
		height: 350px;
		box-shadow: 2px 2px 5px #999;
	}
</style>
<script>
	window.onload = function() {
		document.title = "<?= $juego->titulo ?>";
	}
</script>
<div class="container">

	<div class="row">
		<div class="col-md-12">
			<h1><?= $juego->titulo ?></h1>
		</div>
	</div>

	<br>

	<div class="row">
		<div class="col-md-4">
			<img src="imagenes/imagen<?= $juego->id ?>.jpg" class="img-rounded">
		</div>

		<div class="row">
			<div class="col-md-6">
				<ul class="list-group">
					<li class="list-group-item"><?= $plataforma->nombre ?></li>
					<li class="list-group-item"><?= $genero->genero ?></li>
					<li class="list-group-item"><?= $juego->fecha_lanzamiento ?></li>
				</ul>
			</div>

			<div class="col-md-6">
				<div class="well well-lg">
					<?= $juego->descripcion ?>
				</div>
			</div>

			<div class="col-md-6">
				<h1>
					<span class="label label-default">
						<?= $juego->precio ?> €
					</span>
				</h1>
			</div>
		</div>
			
	</div>
	


</div>
