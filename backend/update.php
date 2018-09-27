<?php

	$name_page = 'Modificar';

	include("includes/doc_header.php");

	include("includes/site_header.php");

	$videojuego = Doctrine_Query::create()->from("Videojuegos v")
		->where("v.id = ?", $_GET['id'])
		->execute()
		->getFirst();

	$generos = Doctrine_Query::create()->from("Generos")->execute();

	$plataformas = Doctrine_Query::create()->from("Plataformas")->execute();

	$imagen = Doctrine_Query::create()->from("Uploads u")
		->where("u.id = ?", $videojuego->imagen_id)
		->execute()
		->getFirst();

	
	//	Modificación del videojuego
	if (!empty($_POST)) {

		//	Modificación de imagen.
		if (!$_FILES['imagen']['size'] == 0) {
			$dir_subida = "/storage/ssd4/742/7053742/public_html/imagenes/";
			$name_fichero = basename("imagen" . $videojuego->id . ".jpg");
			$fichero_subido = $dir_subida . $name_fichero;

			//	Borrado de imágen antigua y añadido de imágen nueva.
			unlink($fichero_subido);
			move_uploaded_file($_FILES['imagen']['tmp_name'], $fichero_subido);

		
		}

		
		foreach ($_POST as $k => $v) {
			$videojuego[$k] = $v;
		}

		$videojuego->save();
	}
		
	
?>

<style>
	div#imagen_video > img {
		width: 400px;
		height: 500px;
		box-shadow: 2px 2px 5px #999;
	}

	div#imagen_video {
		text-align: center;
	}
</style>
<div class="container">

	<ol class="breadcrumb">
		<li><a href="index.php">Inicio</a></li>
		<li><a href="videojuegos.php">Videojuegos</a></li>
		<li class="active"><?= $videojuego->titulo ?></li>
	</ol>

	<h1><?= $videojuego->titulo ?></h1>
	<br>

	<!-- Formulario de modificación -->
	<div class="row">
		<div class="col-md-6">

			<form action="" method="post" enctype="multipart/form-data">
				<!-- Titulo -->
				<div class="form-group">
					<label for="titulo">Titulo</label>
    				<input type="text" class="form-control" name="titulo" id="titulo"  value="<?= $videojuego->titulo ?>">
				</div>

				<!-- Descripción -->
				<div class="form-group">
					<label for="descripcion">Descripción</label>
    				<textarea rows="5" class="form-control" name="descripcion" id="descripcion"><?= $videojuego->descripcion ?></textarea>
				</div>

				<!-- Descripción -->
				<div class="form-group">
					<label for="precio">Precio</label>
    				<input type="number" class="form-control" name="precio" id="precio" value="<?= $videojuego->precio ?>">
				</div>

				<!-- Género -->
				<div class="form-group">
					<label for="genero">Género</label>
    				<select class="form-control" name="genero_id" id="genero">
    					<?php foreach ($generos as $genero): ?>
    						<option value="<?= $genero->id ?>" 
								<?= $genero->id == $videojuego->genero_id ? "selected" : "" ?>
								><?= $genero->genero ?>
							</option>
    					<?php endforeach; ?>
    				</select>
				</div>

				<!-- Plataforma -->
				<div class="form-group">
					<label for="plataforma">Plataforma</label>
    				<select class="form-control" name="plataforma_id" id="plataforma">
    					<?php foreach ($plataformas as $plataforma): ?>
    						<option value="<?= $plataforma->id ?>" 
								<?= $plataforma->id == $videojuego->plataforma_id ? "selected" : "" ?>
								><?= $plataforma->nombre ?>
							</option>
    					<?php endforeach; ?>
    				</select>
				</div>

				<!-- Fecha Lanzamiento -->
				<div class="form-group">
					<label for="fecha_lanzamiento">Fecha de Lanzamiento</label>
    				<input type="date" name="fecha_lanzamiento" class="form-control" id="fecha_lanzamiento" value="<?= $videojuego->fecha_lanzamiento ?>">
				</div>

				<!-- Imagen -->
				<div class="form-group">
					<label for="imagen">Imágen</label>
    				<input type="file"  name="imagen" id="imagen">
				</div>

				<button type="submit" class="btn btn-success">Modificar</button>

			</form>

		</div>

		<!-- Imágen -->
		<div class="col-md-6 centrado">
			<div id="imagen_video">
				<img src="../imagenes/<?= $imagen->nombre ?>" class="img-rounded sombra">
			</div>
		</div>
	</div>


</div>


<?php

	include("includes/doc_end.php");

?>