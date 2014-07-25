<?php 
include_once(''._LOCAL_.'/model/defaultmodel.class.php');

class actividadesadminmodel extends defaultmodel{
	/* Aquí se implementan todos los métodos relacionados con el Gestor de Actividades */
	
	static function getinfoactividad($idactividad) {
		$conn = parent::db_connect(); // Conexión a base de datos
		$query = "SELECT * FROM actividades WHERE IdActividad=$idactividad"; 
		$result = mysql_query($query); //A la base de datos le manda la consulta y retorna el array de valores.
		parent::db_desconnect($conn);
		return $result;
	}
	
	static function getInfoActividadMicrosites() {
		$conn = parent::db_connect(); // Conexión a base de datos
		$query = "SELECT IdActividad, Titular FROM actividades"; 
		$result = mysql_query($query); //A la base de datos le manda la consulta y retorna el array de valores.
		parent::db_desconnect($conn);
		return $result;
	}
		
	static function listarActividades($clausulaWhere,$clausulaOrder,$clausulaLimit) { // Función que recoge todas las actividades de la Base de Datos
		$conn = parent::db_connect(); // Conexión a base de datos
		// Select: Palabra clave *: es la columna donde queremos buscar from: palabra clave Actividades: Nombre de la tabla.
		$query = "SELECT * FROM actividades $clausulaWhere ORDER BY $clausulaOrder LIMIT $clausulaLimit"; 
		$result = mysql_query($query); //A la base de datos le manda la consulta y retorna el array de valores.
		parent::db_desconnect($conn);
		return $result; // Retornamos la consulta que deberemos procesar en el controller. 
	}
	
	static function getNumOfActividades($clausulaWhere) {
		$conn= defaultmodel::db_connect();
		$query="SELECT COUNT(*) as count FROM actividades $clausulaWhere";
		$result=mysql_query($query) or die("Error");
		parent::db_desconnect($conn);
		return($result);	
	}
	
	static function actualizarActividad($id_actividad,$clausulaset){ //Función encargada de actualizar la información de una actividad
		$conn=parent::db_connect();
		$query="update actividades SET ".$clausulaset." where IdActividad='$id_actividad'";
		mysql_query($query) or die("Error");
		parent::db_desconnect($conn);
	}
	
	static function eliminarActividad($id_actividad){
		//Función encargada de actualizar la información de una actividad
		$conn=parent::db_connect();
		$query="delete FROM actividades where IdActividad='$id_actividad'";
		echo $query;
		mysql_query($query) or die("Error");
		parent::db_desconnect($conn);
	}
	
	public static function cambiarContenido($id_actividad,$contenido){
		$conn=parent::db_connect();
		$query="update actividades set Contenido='$contenido' WHERE IdActividad='$id_actividad'";
		mysql_query($query) or die("Error");
		parent::db_desconnect($conn);
	}
	
	static function get_subscripciones_a_actividad($idactividad) {
		$conn = parent::db_connect(); // Conexión a base de datos
		$query = "SELECT * FROM subscripciones_actividad WHERE IdActividad=".$idactividad;
		parent::db_desconnect($conn);
		return mysql_query($query);
	}
	
	
	
	static function anadirActividad ($estado,$categoria,$fecha,$titular,$entradilla,$contenido,$email,$lugar,$limite,$mostrarhome,$opcionesmenudetalles){
		$conn=parent::db_connect();
		$query="INSERT INTO actividades set Estado='$estado',Categoria='$categoria', Titular='$titular', Entradilla='$entradilla', 
			Contenido='$contenido', Texto_emails_recomendacion='$email', Fecha='$fecha', Lugar='$lugar', 
			Limite_de_acompanantes='$limite', Mostrar_en_home='$mostrarhome', Opciones_menu_detalles='$opcionesmenudetalles'";
		//echo $query;
		mysql_query($query) or die ($query);//("Error al insertar el registro");
		$idnuevo = mysql_insert_id();		
		parent::db_desconnect($conn);
		return $idnuevo;
	}
	
	static function db_update($destino_rel,$consulta){
		$conn=parent::db_connect();
		$query="update actividades SET Imagen='$destino_rel' WHERE IdActividad='$consulta'";
		mysql_query($query) or die("Error");
		parent::db_desconnect($conn);
	}
	
	
	
	static function db_update_listado($destino_rel,$consulta){
		$conn=parent::db_connect();
		$query="update actividades SET Imagen_listado='$destino_rel' WHERE IdActividad='$consulta'";
		mysql_query($query) or die("Error");
		parent::db_desconnect($conn);
	}
	
	static function obtenerRutaImagenActividad($idactividad){
		$conn=parent::db_connect();
		$query="SELECT Imagen FROM actividades WHERE IdActividad='$idactividad'";
		$result = mysql_query($query) or die("Error");
		parent::db_desconnect($conn);
		return $result;
	}
	
	
	
	// Para cuando se muestra la tabla de Actividades en la p‡gina de la GALERIA MULTIMEDIA
	
	static function listarActividadesMultimedia ($clausulaWhere,$clausulaOrder,$clausulaLimit) {
		$conn = parent::db_connect();
		if ($clausulaWhere=="") {
			$query = "SELECT actividades.* FROM actividades, recursos_multimedia WHERE actividades_IdActividad=IdActividad
				 GROUP BY IdActividad ORDER BY $clausulaOrder LIMIT $clausulaLimit";
		}
		else { 
			$query = "SELECT actividades.* FROM actividades, recursos_multimedia $clausulaWhere and actividades_IdActividad=IdActividad
				 GROUP BY IdActividad ORDER BY $clausulaOrder LIMIT $clausulaLimit";		
		}		
		$result = mysql_query($query); 
		parent::db_desconnect($conn);
		return $result;
	}
	
	static function getNumOfActividadesMultimedia ($clausulaWhere) {
		$conn= defaultmodel::db_connect();
		if ($clausulaWhere=="") {
			$query = "SELECT COUNT(*) as count FROM actividades, recursos_multimedia WHERE 
				actividades_IdActividad=IdActividad GROUP BY IdActividad";
		}
		else { 
			$query = "SELECT COUNT(*) as count FROM actividades, recursos_multimedia $clausulaWhere and 
				actividades_IdActividad=IdActividad GROUP BY IdActividad";
		}		
		$result=mysql_query($query) or die($query);  //die("Error")
		parent::db_desconnect($conn);
		return mysql_num_rows($result);	
	}
	
	
	
	// Para cuando se muestra la tabla de Actividades en la p‡gina de PARTICIPANTES
		
	static function listarActividadesParticipantes ($clausulaWhere,$clausulaOrder,$clausulaLimit) {
		$conn = parent::db_connect();
		if ($clausulaWhere=="") {
			$query = "SELECT actividades.* FROM actividades, subscripciones_actividad WHERE subscripciones_actividad.IdActividad=actividades.IdActividad
				GROUP BY actividades.IdActividad ORDER BY $clausulaOrder LIMIT $clausulaLimit";
		}
		else { 
			$query = "SELECT actividades.* FROM actividades, subscripciones_actividad $clausulaWhere and 
				subscripciones_actividad.IdActividad=actividades.IdActividad GROUP BY actividades.IdActividad ORDER BY $clausulaOrder LIMIT $clausulaLimit";		
		}
		$result = mysql_query($query) or die($query);
		parent::db_desconnect($conn);
		return $result;
	}
	
	static function getNumOfActividadesParticipantes ($clausulaWhere) {
		$conn= defaultmodel::db_connect();
		if ($clausulaWhere=="") {
			$query = "SELECT COUNT(*) as count FROM actividades, subscripciones_actividad WHERE 
				subscripciones_actividad.IdActividad=actividades.IdActividad GROUP BY actividades.IdActividad";
		}
		else { 
			$query = "SELECT COUNT(*) as count FROM actividades, subscripciones_actividad $clausulaWhere and 
				subscripciones_actividad.IdActividad=actividades.IdActividad GROUP BY actividades.IdActividad";
		}
		$result=mysql_query($query) or die("Error");
		parent::db_desconnect($conn);
		return mysql_num_rows($result) ;	
	}
	
}
?>