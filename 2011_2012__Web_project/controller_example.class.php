<?php
include_once _LOCAL_.'/model/front/actividades/actividadesfrontmodel.class.php';
include_once _LOCAL_.'/controller/defaultcontroller.class.php';
class actividadesfrontcontroller extends defaultcontroller{
	
	static function datosONG(){
		$result = actividadesfrontmodel::datosONG();
		return $result;	
	}
	
	static function consultamenu($id){
	$result=actividadesfrontmodel::consultamenu($id);
	return $result;
	}


	static function resgistradoractividad($iduser){
		$result = actividadesfrontmodel::resgistradoractividad($iduser);
		return $result;
		
	}
	static function sepuedeinscribirexperto($idactividad,$iduser){
		$sepuede=true;
		$result = actividadesfrontmodel::actividadesinscritasanyocurso($iduser);
		$result2 = actividadesfrontmodel::actividadesexpertos($idactividad);
		
		while ($rowresult=mysql_fetch_array($result)){
			while ($rowresult2=mysql_fetch_array($result2)){
				if($rowresult2["IdActividad"]==$rowresult["IdActividad"]) $sepuede=false;	
			}
		}
		return $sepuede;
	}
		
	static function donacion($post,$iduser){
		$result = actividadesfrontmodel::donacion($post,$iduser);
		return $result;
	}

	static function compruebasemilla($semilla){
		$result = actividadesfrontmodel::compruebasemilla($semilla);
		return $result;
	}


	static function confirmaciondonacion($semilla){
		$result = actividadesfrontmodel::confirmaciondonacion($semilla);
		return $result;
	}
	

	static function eliminaciondonacion($semilla){
		$result = actividadesfrontmodel::eliminaciondonacion($semilla);
		return $result;
	}
	
	
	
	static function diselo($post,$iduser,$Fecha){
		$result = actividadesfrontmodel::diselo($post,$iduser,$Fecha);
		return $result;		
	}

	// Esta función se utiliza para diferenciar entre actividades realizadas e histórico de actividades
	/*static function restarFechas($fecha){
		$a=explode(" ",$fecha);
		$datebasedatos = new DateTime($a[0]);
		$dateactual = new DateTime( date('Y-m-d'));
		$intervalo = $datebasedatos->diff($dateactual);
		$intervalo->format('%R%a dias');
		return $intervalo;
	}*/
	
	static function inscribete($post,$iduser,$Fecha){
		$result = actividadesfrontmodel::inscribete($post,$iduser,$Fecha);
		return $result;		
	}
	
	static function mostraractividadescarrusel(){
		$result = actividadesfrontmodel::mostrarResumenActividades();
		return $result;		
	}
	
	static function Leerpost($id){
		return actividadesfrontmodel::Leerpost($id);
	}
	
	static function LeerUltimosPosts(){
		return actividadesfrontmodel::LeerUltimosPosts();
	}
	
	static function  Leerusuario($id){
		$result=actividadesfrontmodel::Leerusuario($id);
		return $result;
	}
	
	static function  eliminarpost($id,$idusuario){
		$result=actividadesfrontmodel::eliminarpost($id,$idusuario);
		return $result;
	}
	
	static function mostrarTodasActividades(){
		$result=actividadesfrontmodel::mostrarTodasActividades();
		return $result;
	}
	
	static function DetallesActividadesController($id){
		$result=actividadesfrontmodel::DetallesActividades($id);
		return $result;
		
	}
	
	static function mostrarProximasActividades(){
		$result=actividadesfrontmodel::mostrarProximasActividades();
		return $result;
	}
	
	static function mostrarEncursoActividades(){
		$result=actividadesfrontmodel::mostrarEncursoActividades();
		return $result;
	}
	
	static function mostrarRealizadasActividades(){
		$result=actividadesfrontmodel::mostrarRealizadasActividades();
		return $result;
	}
	
	static function mostrarHistoricoActividades(){
		return actividadesfrontmodel::mostrarHistoricoActividades();
	}
	
	static function mostrarCalendarioActividades(){
		$result=actividadesfrontmodel::mostrarCalendarioActividades();
		return $result;
	}
	
	static function mostrarCalendarioActividadesDetalle($id){
		$result=actividadesfrontmodel::mostrarCalendarioActividadesDetalle($id);
		return $result;
	}
	
	static function mostrarDetallesActividades($id){
		$result=actividadesfrontmodel::mostrarDetallesActividades($id);
		return $result;
	}

	static function fechaFormateadaDetalleActividades($fechaEntrada) {
		$diasSemana = array('','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado','Domingo');
		
		$diaSemana = date("N",strtotime($fechaEntrada));
	    $mes = date("m",strtotime($fechaEntrada));
	    $diaMes = date("d",strtotime($fechaEntrada));
	    $anio = date("Y",strtotime($fechaEntrada));

	    $fechaSalida = "$diasSemana[$diaSemana], $diaMes.$mes.$anio";
	    return $fechaSalida;		
	}
	
	
	static function subscritoForo($idactividad,$IdUsuario){
		$result= actividadesfrontmodel::subscritoForo($idactividad,$IdUsuario);
		while ($rowresult=mysql_fetch_array($result)){
			return $rowresult["Subscrito_foro"];
		}
	}
	static function subscribirseForo($idactividad,$IdUsuario,$subscrito){
		if($subscrito=="true") $subscrito=1;
		else $subscrito=0;
		$result= actividadesfrontmodel::subscribirseForo($idactividad,$IdUsuario,$subscrito);
		
	}
	
	static function guardadoPost($contenido,$Fecha,$Visible,$temas_idtopic,$usuarios_IdUsuario){
		actividadesfrontmodel::guardarPost($contenido, $Fecha, $Visible, $temas_idtopic, $usuarios_IdUsuario);
	}
	
	static function fechacometario($fechaEntrada){
		$hora = date("H",strtotime($fechaEntrada));
		$minuto = date("i",strtotime($fechaEntrada));
		//$diaSemana = date("N",strtotime($fechaEntrada));
	    $mes = date("m",strtotime($fechaEntrada));
	    $diaMes = date("d",strtotime($fechaEntrada));
	    $anio = date("Y",strtotime($fechaEntrada));
	    
	     $fechaSalida = "($diaMes/$mes/$anio-$hora:$minuto)";
	    return $fechaSalida;	
		
	}
	


	
static function guardarImagenGaleria($post,$file) {
		
		$tipo = $file["cimagen"]['type'];
		$archivo = $file["cimagen"]['name'];
		$temp=$file["cimagen"]['tmp_name'];
		$archivo = str_replace(" ", "", $archivo);
		$archivotrozos = explode(".", $archivo);
		$nombrearchivo = $archivotrozos[0];
		$id = $post['idactividad'];
		$id_user = $post['iduser'];
		// Obtengo la extension de la imagen
		$trozos = explode("/", $tipo); 
		$extension = strtolower(end($trozos)); 
	     $nombrearchivo = preg_replace('([^A-Za-z0-9])', '', $nombrearchivo);	     					
		// Compruebo si la extension de la imagen es valida
if ((($_FILES["cimagen"]["type"] == "image/gif")
|| ($_FILES["cimagen"]["type"] == "image/jpeg")
|| ($_FILES["cimagen"]["type"] == "image/png")
|| ($_FILES["cimagen"]["type"] == "image/x-png")
|| ($_FILES["cimagen"]["type"] == "image/pjpeg")))		{
	
	if(($_FILES["cimagen"]["type"] == "image/x-png")){
		$extension = 'png';
	}
	if(($_FILES["cimagen"]["type"] == "image/pjpeg")){
		$extension = 'jpg';
	}
	
			// Si el directorio de imagenes no esta creado, lo creo
			$directorio = "/img/actividades/".$id."/galeria";
			if (!is_dir(_LOCAL_.$directorio)) {
				mkdir(_LOCAL_.$directorio);
			}
		
			// Obtengo las rutas que necesito
			$destino_rel = $directorio."/".$id_user."_".$nombrearchivo;
			$destinoBig = htmlspecialchars(_LOCAL_.$destino_rel."_big.png");
			//$destinoMedium = htmlspecialchars(_LOCAL_.$destino_rel."_medium.png");
			$destinoSmall = htmlspecialchars(_LOCAL_.$destino_rel."_small.png");
			
			// Guardo la imagen
			if (defaultcontroller::cropImage(1024, 640, $temp, $extension, $destinoBig) &&
				//defaultcontroller::cropImage(590, 150, $temp, $extension, $destinoMedium) &&
				defaultcontroller::cropImage(110, 69, $temp, $extension, $destinoSmall)) 
			{
				$post['ruta'] = $id_user."_".$nombrearchivo;
				$consulta = actividadesfrontmodel::insertgaleria($post);           		
				$resultado = "Imagen subida con exito";
        	}
        	else {
        		$resultado="Error al subir el archivo";
        	}	
			return $resultado;
		}
			//}
		else {
			$resultado ="La extensi&oacute;n no es la correcta. Solo se admiten archivos jpeg, png, gif, jpg";
			return $resultado;
		}
	
	}
	
	static function galeriaguardarfotos($post,$file){
		$resultado = actividadesfrontcontroller::guardarImagenGaleria($post,$file);
	}
	
	static function galeriaguardarvideos($post){
		$video = $post['ruta'];
		if(stristr($video, 'youtu.be') || stristr($video, 'youtube.com') ){
		 if(stristr($video, 'youtu.be') === FALSE) {
		$corte1 = explode('v=', $video);
		$cortetemp = $corte1[1];
		$corte2 = explode('&', $cortetemp);
		 }else{
		 $corte1 = explode("be/", $video);	
		 $cortetemp = $corte1[1];
		 $corte2 = explode("?", $cortetemp);		
		 }
		$post['ruta']=$corte2[0];
		actividadesfrontmodel::insertgaleria($post);}
	}
	
	static function montargaleria($id,$tipo,$pagina){
		$resultado = actividadesfrontmodel::montargaleria($id,$tipo,$pagina);
		return $resultado;
	}
	
	static function paginadormultimedia($id,$tipo){
		$resultado = 0;
		$resultadotemp = actividadesfrontmodel::paginadormultimedia($id,$tipo);
		while($row=mysql_fetch_array($resultadotemp)){
			$resultado++;
		}
		if($tipo == "imagen"){
			$resultado = $resultado/16;
		}else{
			$resultado = $resultado/9;
		}
			
			$entero = explode(".",$resultado);
			$final = $entero[0];
		return $final;
	}
	
	static function obtenerIdActividadMicrosite() {
		preg_match('/\d/i', $_SERVER['REQUEST_URI'], $result2); // coge el id de la actividad
		//echo $result2[0];
		return $result2[0]; //$_GET['id'];
	}
		
}
?>
