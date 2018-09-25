<?php

	$name_page = "Inicio";

	include("includes/doc_header.php");

	include("includes/site_header.php");

	$juegos = Doctrine_Query::create()->from("Videojuegos")
		->execute();


?>
<style>
	.thumbnail {
		box-shadow: 2px 2px 5px #999;
	}
</style>


<div class="container">

	<div class="page-header">
  		<h1>Games <small>Compra y alquiler de videojuegos</small></h1>
	</div>

	<div class="row">	
		<?php foreach ($juegos as $juego): 

			$genero = Doctrine_Query::create()->from("Generos g")
				->where("g.id = ?", $juego->genero_id)
				->execute()
				->getFirst();

			$plataforma = Doctrine_Query::create()->from("Plataformas p")
				->where("p.id = ?", $juego->plataforma_id)
				->execute()
				->getFirst();

		?>
			<div class="col-sm-6 col-md-3">
				<div class="thumbnail">

					<img src="imagenes/imagen<?= $juego->id ?>.jpg" class="img_rounded" >

					<div class="caption">
						<h4><?= $juego->titulo ?></h4>
						<h4>
							<span class="label label-primary">
								<?= $plataforma->nombre ?>
							</span>
						</h4>
						<p>
							<a href="view.php?id=<?= $juego->id?>" class="btn btn-default">
								<span class="glyphicon glyphicon-eye-open"></span>
							</a>
						</p>
					</div>
				</div>
			</div>
			
		<?php endforeach; ?>

	</div>
</div>



<?php

	include("includes/doc_end.php");
?>

