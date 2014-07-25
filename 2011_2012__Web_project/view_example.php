<?php include_once('../../../../components/admin/constantes.php');

include_once (_LOCAL_.'/controller/defaultcontroller.class.php');
defaultcontroller::CompruebaRolAdministrador();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="es">

<head>
	
	<?php include (''._LOCAL_.'/components/admin/head.php');?>
	
	<!--  Zona de JQGrid -->
	<script type="text/javascript"> 
	// TABLA DE ACTIVIDADES
	jQuery(document).ready(function(){ // Punto de inicio de ejecución que no lo ejecuta hasta que la página se ha cargado.
		jQuery("#tablaActividades").jqGrid({ //Inicializa JQGrid dentro de esa tabla.
			url:'<?php echo _RAIZ_.'/pages/admin/actividades/'?>leerjqgridactividades.php?pagina=foros', 
			editurl:'<?php echo _RAIZ_.'/pages/admin/actividades/';?>editarjqgridactividades.php',
			colNames:['IdActividad','Titular','Categor&iacute;a','Fecha','Lugar'], 
			colModel :[
				{name:'IdActividad', index:'IdActividad', hidden:true}, 
				{name:'Titular', index:'Titular', width:210}, 
				{name:'Categoria', index:'Categoria', width:100},
				{name:'Fecha', index:'Fecha', width:90, formatter:'date', align:'center',
					formatoptions:{newformat:'d-m-Y'}, editrules:{date:true}},
				{name:'Lugar', index:'Lugar', width:80, align:'center'},
			],
			pager: jQuery('#pager'), // Número de páginas de la tabla
			sortname: 'IdActividad', // Campo que ordenara por defecto 
   			caption: 'Listado de Actividades',
   			width: '600',
   			sortname: 'Fecha',
			sortorder: 'desc',
			//subGrid : true,
			//subGridUrl: 'subgrid.php',
		    //subGridModel: [{ name:['Username','Estado','Fecha de aceptacion','Subscrito a actualizaciones'], 
		    //                width: [40,55,60,75] 
            //}],
			onSelectRow: function(idactividad) { 
				var rowdata = jQuery("#tablaActividades").getRowData(idactividad);
				var varurl = "leerjqgridposts.php?idactividad="+idactividad;
				var varcaption = "Posts del foro para la actividad "+idactividad+": "+rowdata['Titular'];
				if(idactividad == null) { 
					idactividad=0; 
					if(jQuery("#subtablaPosts").jqGrid('getGridParam','records') >0 ) { 
						jQuery("#subtablaPosts").jqGrid('setGridParam',{url:varurl,page:1}); 
						jQuery("#subtablaPosts").jqGrid('setCaption',varcaption).trigger('reloadGrid'); 
					} 
				} 
				else { 
					jQuery("#subtablaPosts").jqGrid('setGridParam',{url:varurl,page:1}); 
					jQuery("#subtablaPosts").jqGrid('setCaption',varcaption).trigger('reloadGrid'); 
				}
				$("#gbox_subtablaPosts").slideDown('slow', function() {
				   	// Animation complete.
				});
			} 
		}).navGrid("#pager",{del:false});


		jQuery("#tablaActividades").jqGrid('gridResize',{minWidth:600,maxWidth:800,minHeight:80,maxHeight:320});

		
		// SUBTABLA DE POSTS DEL FORO PARA UNA ACTIVIDAD
		var lastsel;
		jQuery("#subtablaPosts").jqGrid({ 
			//height: 100, 
		  url:"leerjqgridposts.php?idactividad=0",
          editurl:"editarjqgridposts.php",
          colNames: ['Id Post','Username','Fecha','Visible','Contenido','Acci&oacute;n'],
          colModel: [
            {name:"IdPost",index:"IdPost", width:70, align:'center'},
            {name:"Username",index:"Username", width:80},
            {name:"Fecha",index:"Fecha", width:70, align:"center", formatter:'date', formatoptions:{newformat:'d-m-Y'}, editrules:{date:true}},
            {name:"Visible",index:"Visible", editable:true, width:50, formatter:'checkbox', 
            	edittype:"checkbox", editoptions: {value:"1:0"}, align:"center"}, 
 	        {name:"Contenido",index:"Contenido", editable:true, width:300,
         		edittype:"textarea", editoptions:{rows:"3",cols:"40"}, editrules:{required:true, edithidden:true}},
			{name:'Accion', index:'Accion', width:60, align:'center'},        
          ],
   			width: '600',
			pager: '#pager_subtablaPosts',
			//pgbuttons: false,
		    //pginput: false,
		    //rowList: '',
			sortname: 'IdPost', 
			viewrecords: true,
			sortorder: "asc", 
			caption:"Posts del foro para la actividad",
			subGrid : false,
			onSelectRow: function(IdPost){ 
				jQuery('#subtablaPosts').jqGrid('editRow',IdPost,true);
				se = "<input style='width:16px; height:16px;' type='image' src='<?php echo _RAIZ_; ?>/img/admin/botGuardar.gif'  title='Guardar' onclick=\"jQuery('#subtablaPosts').saveRow('"+IdPost+"'); jQuery('#subtablaPosts').jqGrid('setRowData',"+IdPost+",{Accion:''});\" />"; 
				jQuery("#subtablaPosts").jqGrid('setRowData',IdPost,{Accion:se});
				if(IdPost && IdPost!==lastsel) { 
					jQuery("#subtablaPosts").jqGrid('setRowData',lastsel,{Accion:''});	
					jQuery('#subtablaPosts').jqGrid('restoreRow',lastsel); 
					//jQuery("#tablaUsuarios").jqGrid('setSelection',IdPost,false);
	
					lastsel=IdPost;
				}
			}/* ,
			gridComplete: function(){
				var ids = jQuery("#subtablaPosts").jqGrid('getDataIDs');
				for(var i=0;i < ids.length;i++){
					var cl = ids[i];
					//var contenido=con['Contenido'];
					se = "<input style='width:16px; height:16px;' type='image' src='<?php echo _RAIZ_; ?>/img/admin/botGuardar.gif'  title='Guardar' onclick=\"jQuery('#subtablaPosts').saveRow('"+cl+"');\" />"; 
					//ce = "<input style='height:22px;width:20px;' type='button' value='C' onclick=\"jQuery('#tablaNoticias').restoreRow('"+cl+"');\" />"; 
					jQuery("#subtablaPosts").jqGrid('setRowData',ids[i],{Accion:se});
				}	
			}*/
			//subGridRowColapsed: function(subgrid_id, row_id) {
			//	$('.interaction').hide();
			//}
		}).navGrid('#pager_subtablaPosts',{del:false}); 

		
		//jQuery("#tablaUsuarios").jqGrid("searchGrid",{Reset:'Borrar'});
		// Campos uniformes en la mayoría de las tablas: datatype: "json", mtype: 'GET', rowNum:10,
		// rowList:[10,20,30], sortorder: "asc", viewrecords: true	});

		jQuery("#subtablaPosts").jqGrid('gridResize',{minWidth:600,maxWidth:800,minHeight:80,maxHeight:320});
		$("#gbox_subtablaPosts").hide();
		
	});
	</script>


 
</head>

<body>
	
	<div class="container">
		
	<!--	<form method="post" action="<?php echo _RAIZ_.'/pages/admin/actividades/';?>exportarexcelactividades.php?" target="_blank">
	  		<input type="hidden" name="csvBuffer" id="csvBuffer" value="" />
		</form> -->
		
	<?php include (''._LOCAL_.'/components/admin/header.php');?>
		
		<?php include (''._LOCAL_.'/components/admin/login.php');?>
		
		<div id="cuerpo" class="span-24 last">
		
			<?php include (''._LOCAL_.'/components/admin/menu.php');?>
			
			<div id="layout" class="span-17 last">
				<div id="mapaweb"> Usted est&aacute; en: <?php echo '<a href="'._RAIZ_.'">Inicio</a>' ?>  -  <?php echo '<a href="'._RAIZ_.'/pages/admin/actividades/">Gestor de actividades</a>' ?>  -  <?php echo '<a href="'._RAIZ_.'/pages/admin/actividades/foros">Foros</a>' ?> </div> <br />
			
				<div style="margin-top:30px;"><!-- Con esta instrucción bajamaos la tabla 30px -->
					<div id="tablaListado">
						<table id="tablaActividades" class="scroll"></table> 
						<div id="pager" class="scroll" style="text-align:center;"></div>
						<br />
						<table id="subtablaPosts"></table> 
						<div id="pager_subtablaPosts"></div>
					</div> <!-- id=tablaListado -->
				</div>

			</div>
		</div>
			<?php include (''._LOCAL_.'/components/admin/footer.php');?>
	</div>
	
</body>

</html>