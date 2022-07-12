<?php

require_once("config/database.php");
date_default_timezone_set("America/Lima");


$database = new Database();
$db = $database->getConnection();

$nombre = $_POST["nombre"];
$email = $_POST["email"];
$asunto = $_POST["asunto"];
$mensaje = $_POST["mensaje"];
$fecha = date("y-m-d H:i:s");

//if(isset($nombre) and !empty($nombre))
//{
//	if(isset($email) and !empty($email))
//	{
//		if(isset($asunto) and !empty($asunto))
//		{
//			if(isset($mensaje) and !empty($mensaje))
//			{
//				echo "Se registro ok";
//			}else{
//				echo "Ingrese msg";
//			}	;
//		}else{
//			echo "Ingrese asunto";
//		}	
//	}else{
//		echo "Ingrese email";
//	}
//}else{
//	echo "Ingrese nombre";
//}
$respuesta = array();
$listaerrores = array();

//if(isset($nombre) and !empty($nombre))
//{
//	if(isset($email) and !empty($email))
//	{
//		if(isset($asunto) and !empty($asunto))
//		{
//			if(isset($mensaje) and !empty($mensaje))
//			{
//				$respuesta["tipo"] = 1;
//				$respuesta["mensaje"] = "Se registro satisfactoriamente";
//			}else{
//				$respuesta["tipo"] = 2;
//				$respuesta["mensaje"] = "Ingrese mensaje";
//			}	
//		}else{
//			$respuesta["tipo"] = 2;
//			$respuesta["mensaje"] = "Ingrese asunto";
//		}	
//	}else{
//		$respuesta["tipo"] = 2;
//		$respuesta["mensaje"] = "Ingrese email";
//	}
//}else{
//	$respuesta["tipo"] = 2;
//	$respuesta["mensaje"] = "Ingrese nombre";
//}

function is_ajax()
{
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) and strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return true;
    }

    return false;
}

if(!isset($nombre) or empty($nombre))
{
	array_push($listaerrores, array(
		"id" => "nombre",
		"mensaje" => "por favor, ingresar nombre"
	));	
}

if(!isset($email) or empty($email))
{
	array_push($listaerrores, array(
		"id" => "email",
		"mensaje" => "por favor, ingresar email"
	));
}

if(!isset($asunto) or empty($asunto))
{
	array_push($listaerrores, array(
		"id" => "asunto",
		"mensaje" => "por favor, ingresar asunto"
	));
}

if(!isset($mensaje) or empty($mensaje))
{
	array_push($listaerrores, array(
		"id" => "mensaje",
		"mensaje" => "por favor, ingresar mensaje"
	));
}

if(is_ajax())
{	
	if(count($listaerrores) > 0)
	{
		$respuesta["tipo"] = 2;
		$respuesta["errores"] = $listaerrores;
	}else{

		$declaracion = $db->prepare("INSERT INTO tb_contacto(nombre,email,asunto,mensaje,fecha) VALUES(:nombre,:email,:asunto,:mensaje,:fecha)");

		$declaracion->bindParam(":nombre",$nombre,PDO::PARAM_STR);
		$declaracion->bindParam(":email",$email,PDO::PARAM_STR);
		$declaracion->bindParam(":asunto",$asunto,PDO::PARAM_STR);
		$declaracion->bindParam(":mensaje",$mensaje,PDO::PARAM_STR);
		$declaracion->bindParam(":fecha",$fecha,PDO::PARAM_STR);
		$declaracion->execute();

		$ultimoid = $db->lastInsertid();

		if($ultimoid)
		{
			$respuesta["tipo"] = 1;
			$respuesta["mensaje"] = "Se registro satisfactoriamente";
		}else{
			$respuesta["tipo"] = 3;
			$respuesta["mensaje"] = "Problema de insercion";
		}	

			
	}

}else{
	$respuesta["tipo"] = 3;
	$respuesta["mensaje"] = "Problema de insercion";
}

echo json_encode($respuesta);
?>
