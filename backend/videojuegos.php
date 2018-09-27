<?php
	$name_page = "Videojuegos";


	include("includes/doc_header.php");

	include("includes/site_header.php");

	$videojuegos = Doctrine_Query::create()->from("Videojuegos")
		->execute();

?>


<div class="container">

	<ol class="breadcrumb">
		<li><a href="index.php">Inicio</a></li>
		<li class="active">Videojuegos</li>
	</ol>

	<h1>Lista de videojuegos</h1>
	<br>

	<div class="row">
	<div class="col-md-12">
		<table class="table">
			<thead>
				<tr>
					<th>Título</th>
					<th>Precio</th>
					<th>Género</th>
					<th>Plataforma</th>
					<th>Lanzamiento</th>
					<th>Acciones</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($videojuegos as $juego): 

					$genero = Doctrine_Query::create()->from("Generos g")
						->where("g.id = ?", $juego->genero_id)
						->execute()
						->getFirst();

					$plataforma = Doctrine_Query::create()->from("Plataformas p")
						->where("p.id = ?", $juego->plataforma_id)
						->execute()
						->getFirst();
				?>
					<tr>
						<td><?= $juego->titulo ?></td>
						<td><?= $juego->precio ?></td>
						<td><?= $genero->genero ?></td>
						<td><?= $plataforma->nombre ?></td>
						<td><?= $juego->fecha_lanzamiento ?></td>
						<td>
							<a href="update.php?id=<?= $juego->id ?>" class="btn btn-xs btn-primary">
								<span class="glyphicon glyphicon-pencil"></span>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
</div>




<?php
	
	include("includes/doc_end.php");
?>