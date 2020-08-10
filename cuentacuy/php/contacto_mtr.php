<?php
  header("Access-Control-Allow-Origin: *");

  include('./db/database.php');


	date_default_timezone_set("America/Lima");
	$date = date("Y-m-d H:i:s");    
  $errorMSG = "";
  $info = "No hay información adicional";
  $subj = "";

  $msg = "Registro completo - ";
  $r_type = $_POST["v_place"];

  $arr = array();
  $conn = Database::getInstance();
  
  switch($r_type){
    case 'cuenta-cuy':
        $subj = "Cuenta Cuy";    
        $msg .= $subj; 
      break;
    case 'buyer':
        $r_mayorista = $_POST['v_mayorista'];
        $r_restaurante = $_POST['v_restaurante'];
        $r_exportacion = $_POST['v_exportacion'];
        $r_consumo = $_POST['v_consumo'];

        if(!empty($r_mayorista)) array_push($arr, $r_mayorista);
        if(!empty($r_restaurante)) array_push($arr, $r_restaurante);
        if(!empty($r_exportacion)) array_push($arr, $r_exportacion);
        if(!empty($r_consumo)) array_push($arr, $r_consumo);
        
        foreach($arr as $id){
            $query = "SELECT name FROM ccuy_type WHERE id='$id'";
            $conn->query($query);
        }

        if(count($arr) > 0) $info = '';
        foreach ((array) $conn->results() as $row) {
           $info .= $row['name'] . '</br>';
        }

        $subj = "Compradores";
        $msg .= $subj; 
      break;
    case 'rearing':
        $r_repmacho = $_POST['v_repmacho'];
        $r_rephembra = $_POST['v_rephembra'];
        $r_pie = $_POST['v_pie'];
        $r_parrentero = $_POST['v_parrentero'];
        $r_parrtrozado = $_POST['v_parrtrozado'];
        $r_vacio = $_POST['v_vacio'];

        if(!empty($r_repmacho)) array_push($arr, $r_repmacho);
        if(!empty($r_rephembra)) array_push($arr, $r_rephembra);
        if(!empty($r_pie)) array_push($arr, $r_pie);
        if(!empty($r_parrentero)) array_push($arr, $r_parrentero);
        if(!empty($r_parrtrozado)) array_push($arr, $r_parrtrozado);
        if(!empty($r_vacio)) array_push($arr, $r_vacio);

        
        foreach($arr as $id){
            $query = "SELECT name FROM ccuy_type WHERE id='$id'";
            $conn->query($query);
        }

        if(count($arr) > 0) $info = '';
        foreach ((array) $conn->results() as $row) {
           $info .= $row['name'] . '</br>';
        }

        $subj = "Criadores";
        $msg .= $subj; 
      break;
    default:
        $msg .= "Otro"; 
      break;
  }


	/* NOMBRE */
	if (empty($_POST["v_nombre"])) {
		$errorMSG = "<li>Debes ingresar tu nombre y apellido o razón social</<li>";
	} else {
		$r_nombre = $_POST["v_nombre"];
	}
	/* DNI RUC */
	if (empty($_POST["v_dniruc"])) {
		$errorMSG = "<li>Debes ingresar tu número de DNI o RUC</li>";
	} 
	else {
		$r_dniruc = $_POST["v_dniruc"];
	}
	/* CELLULAR */
	if (empty($_POST["v_celular"])) {
		$errorMSG = "<li>Debes ingresar tu número de celular</li>";
	} else {
		$r_celular = $_POST["v_celular"];
	}
	/* CORREO */
	if (empty($_POST["v_email"])) {
		$errorMSG = "<li>Debes ingresar el correo</li>";
	} else {
		$r_email = $_POST["v_email"];
		if(!email_validation($r_email)) $errorMSG = "Email invalido";
	}
	/* DIRECTION */
	if (empty($_POST["v_direccion"])) {
		$errorMSG = "<li>Debes ingresar la dirección del lugar de crianza</li>";
	} else {
		$r_direccion = $_POST["v_direccion"];
  }

	/* PROVINCIA */
	if ($_POST["v_province"] == -1) {
		$errorMSG = "<li>Debes ingresar la provincia donde está el lugar de crianza</li>";
	} else {
		$r_provincia = $_POST["v_province"];
	}
	
	/* DISTRITO */
	if ($_POST["v_district"] == -1) {
		$errorMSG = "<li>Debes ingresar el distrito donde está el lugar de crianza</li>";
	} else {
		$r_distrito = $_POST["v_district"];
	}
	
	/* DEPARTMENT */
	if ($_POST["v_department"] == -1) {
		$errorMSG = "<li>Debes ingresar el departamento donde está el lugar de crianza</li>";
	} else {
		$r_departamento = $_POST["v_department"];
	}

	/* CONSULT */
	if (empty($_POST["v_consulta"])) {
		$r_consulta = "";
	} else {
		$r_consulta = $_POST["v_consulta"];
	}
	
	/* MASINFO */
	if (empty($_POST["v_masinfo"])) {
		$r_masinfo = "No";
	} else {
		$r_masinfo = $_POST["v_masinfo"];
	}
	
	if(empty($errorMSG)){
    
		CreateContacto();
    CreateEmail();
		
		header('Content-Type: application/json');
		echo json_encode(['code'=>200, 'msg'=>$msg]);
		exit;
	}
	else {
		echo json_encode(['code'=>404, 'msg'=>$errorMSG]);
		exit;
  }
  
  function email_validation($str) { 
    return (!preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str)) ? FALSE : TRUE; 
  } 

	function CreateContacto() {
		global $r_nombre;
		global $r_dniruc;
		global $r_celular;
		global $r_email;
		global $r_direccion;
		global $r_provincia;
		global $r_distrito;
		global $r_departamento;
		global $r_consulta;
		global $r_masinfo;
		global $r_type;
		global $info;
		global $arr;
		global $conn;

		$date = $GLOBALS['date'];

		$query = "INSERT INTO ccuy_contacto set razon_social = '$r_nombre', dni_ruc = '$r_dniruc', cellular = '$r_celular', 
				  correo_electronico = '$r_email', direction = '$r_direccion', province_id = '$r_provincia', 
				  district_id = '$r_distrito', department_id = '$r_departamento', comments = '$r_consulta', 
				  more_info = '$r_masinfo', date_creation='$date', info='$info', type='$r_type'";

		$r = $conn->insert($query);
		
		if($r && count($arr) > 0) {
		  $con_id = $conn->insert_id();
			foreach($arr as $id){
			  $query = "INSERT INTO ccuy_contacto_type set value='$id', contacto_id='$con_id'";
			  $conn->insert($query);
			}
		} 

	}

	function CreateEmail()
	{
		global $r_nombre;
		global $r_dniruc;
		global $r_celular;
		global $r_email;
		global $r_direccion;
		global $r_provincia;
		global $r_distrito;
		GLOBAL $r_departamento;
		global $r_consulta;
		global $r_masinfo;
		global $info;
		global $subj;
		global $conn;

		if($r_masinfo!="") $r_masinfo ="Sí, deseo más información sobre $subj";

		$query = "SELECT ccuy_ubigeo_district.name AS district, department.name AS department, province.name AS province 
					FROM ccuy_ubigeo_district 
					INNER JOIN ccuy_ubigeo_department AS department
					  ON ccuy_ubigeo_district.department_id=department.id 
					INNER JOIN ccuy_ubigeo_province AS province
					  ON ccuy_ubigeo_district.province_id=province.id
					WHERE 
					  ccuy_ubigeo_district.id='$r_distrito'";

		$conn->query($query);

		foreach ($conn->results() as $row) {
			$r_distrito = isset($row['district']) ? $row['district'] : ''; 
			$r_departamento = isset($row['department']) ? $row['department'] : '';
			$r_provincia = isset($row['province']) ? $row['province'] : ''; 
		 }

		/* Establecer Variables */
    $sitename = $subj;
		$siteaddress ="http://www.cuentacuy.com";

		//$adminaddress = "ixarlos@gmail.com";
		$adminaddress = "cuentacuy.peru@gmail.com";
		
		$headers = "Content-Type: text/html; charset=UTF-8" . "\r\n";
		$headers .= "From: " . $sitename . " <" . $adminaddress . ">" . "\r\n";
		
		$date = $GLOBALS['date'];
		
		mail($adminaddress,"Información sobre $subj (Administración)",
		"<pre>Registrado: ($date)
    
    Nombre: $r_nombre
		DNI/RUC: $r_dniruc
		Celular: $r_celular
		Email: $r_email
		Dirección: $r_direccion
		Departamento: $r_departamento
		Provincia: $r_provincia
		Distrito: $r_distrito
		Consulta: $r_consulta
		$r_masinfo</pre>
    $info
		"
		,$headers);

		mail("$r_email","Información sobre $subj (Cliente)",
		"<pre>Gracias por contactarnos y solicitar información sobre $subj. Hemos recepcionado satisfactoriamente esta información.
		
		Nombre: $r_nombre
		DNI/RUC: $r_dniruc
		Celular: $r_celular
		Email: $r_email
		Dirección: $r_direccion
		Departamento: $r_departamento
		Provincia: $r_provincia
		Distrito: $r_distrito
		</pre>"
		,$headers);

  }

  $conn->close();
