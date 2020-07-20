$(document).ready(function () {
	const _url =
		window.location.hostname === 'localhost'
			? 'http://localhost/cc_forms/cuentacuy/php/'
			: 'http://cuentacuy.com/test/php/';

	$('#submit').click(function (e) {
		e.preventDefault();

		if (!verifica()) return;

		var v_nombre = document.formdatos.v_nombre.value;
		var v_dniruc = document.formdatos.v_dniruc.value;
		var v_celular = document.formdatos.v_celular.value;
		var v_email = document.formdatos.v_email.value;
		var v_direccion = document.formdatos.v_direccion.value;

		var v_province = document.formdatos.v_province.value;
		var v_district = document.formdatos.v_district.value;
		var v_department = document.formdatos.v_department.value;

		var v_place = document.formdatos.v_place.value;
		var v_consulta = document.formdatos.v_consulta ? document.formdatos.v_consulta.value : null;
		var v_masinfo = 'No';

		let data = {};

		if ($('#v_masinfo').is(':checked')) {
			v_masinfo = 'Sí';
		}

		var v_repmacho = '';
		var v_rephembra = '';
		var v_pie = '';
		var v_parrentero = '';
		var v_parrtrozado = '';
		var v_vacio = '';

		var v_mayorista = '';
		var v_restaurante = '';
		var v_exportacion = '';
		var v_consumo = '';

		if (v_place === 'buyer') {
			if ($('#v_mayorista').is(':checked')) {
				v_mayorista = document.formdatos.v_mayorista.value;
			}
			if ($('#v_restaurante').is(':checked')) {
				v_restaurante = document.formdatos.v_restaurante.value;
			}
			if ($('#v_exportacion').is(':checked')) {
				v_exportacion = document.formdatos.v_exportacion.value;
			}
			if ($('#v_consumo').is(':checked')) {
				v_consumo = document.formdatos.v_consumo.value;
			}
			data = {
				v_mayorista,
				v_restaurante,
				v_exportacion,
				v_consumo,
			};
		}

		if (v_place === 'rearing') {
			//data
			if ($('#v_repmacho').is(':checked')) {
				v_repmacho = document.formdatos.v_repmacho.value;
			}
			if ($('#v_rephembra').is(':checked')) {
				v_rephembra = document.formdatos.v_rephembra.value;
			}
			if ($('#v_pie').is(':checked')) {
				v_pie = document.formdatos.v_pie.value;
			}
			if ($('#v_parrentero').is(':checked')) {
				v_parrentero = document.formdatos.v_parrentero.value;
			}
			if ($('#v_parrtrozado').is(':checked')) {
				v_parrtrozado = document.formdatos.v_parrtrozado.value;
			}
			if ($('#v_vacio').is(':checked')) {
				v_vacio = document.formdatos.v_vacio.value;
			}
			data = {
				v_repmacho,
				v_rephembra,
				v_pie,
				v_parrentero,
				v_parrtrozado,
				v_vacio,
			};
		}

		$.ajax({
			type: 'POST',
			url: _url + 'contacto_mtr.php',
			dataType: 'json',
			data: {
				v_nombre: v_nombre,
				v_dniruc: v_dniruc,
				v_celular: v_celular,
				v_email: v_email,
				v_direccion: v_direccion,

				//UBIGEO
				v_province: v_province,
				v_district: v_district,
				v_department: v_department,
				//END UBIGEO
				v_place: v_place,
				v_consulta: v_consulta,
				v_masinfo: v_masinfo,
				...data,
			},
			success: function (data) {
				// debugger;
				if (data.code == '200') {
					alert('Listo: ' + data.msg);
					Limpiar();
				} else {
					$('.display-error').html('<ul>' + data.msg + '</ul>');
					$('.display-error').css('display', 'block');
				}
			},
			error: function (data) {
				// debugger;
				if (data.code == '404') {
					$('.display-error').html('<ul>' + data.msg + '</ul>');
					$('.display-error').css('display', 'block');
				}
			},
		});
	});
	$.get(_url + 'masters.php?v_type=department', function (data, status) {
		$('#department').append('<option value="-1">Selecciona el departamento</option>');
		$.each(JSON.parse(data), function (index, value) {
			// APPEND OR INSERT DATA TO SELECT ELEMENT.
			$('#department').append('<option value="' + value.id + '">' + value.name + '</option>');
		});
	});
	// SHOW SELECTED VALUE.
	$('#department').change(function () {
		var _department = this.options[this.selectedIndex].value;
		var url = _url + 'masters.php?v_type=province&v_department=' + _department;

		$.get(url, function (data, status) {
			$('#province,#district').empty();
			$('#province').append('<option value="-1">Selecciona la provincia</option>');
			$.each(JSON.parse(data), function (index, value) {
				// APPEND OR INSERT DATA TO SELECT ELEMENT.
				$('#province').append('<option value="' + value.id + '" rg="' + _department + '">' + value.name + '</option>');
			});
		});
	});
	// SHOW SELECTED VALUE.
	$('#province').change(function () {
		var _department = $('option:selected', this).attr('rg');
		var _province = this.options[this.selectedIndex].value;

		var url = _url + 'masters.php?v_type=district&v_department=' + _department + '&v_province=' + _province;

		$.get(url, function (data, status) {
			$('#district').empty();
			$('#district').append('<option value="-1">Selecciona el distrito</option>');
			$.each(JSON.parse(data), function (index, value) {
				// APPEND OR INSERT DATA TO SELECT ELEMENT.
				$('#district').append('<option value="' + value.id + '">' + value.name + '</option>');
			});
		});
	});
});

function Limpiar() {
	document.formdatos.v_nombre.value = '';
	document.formdatos.v_dniruc.value = '';
	document.formdatos.v_celular.value = '';
	document.formdatos.v_email.value = '';
	document.formdatos.v_direccion.value = '';
	//UBIGEO
	document.formdatos.v_department.value = '';
	document.formdatos.v_province.value = '';
	document.formdatos.v_district.value = '';
	//END UBIGEO

	//switch clean by place
	let v_place = document.formdatos.v_place.value;
	switch (v_place) {
		case 'cuenta-cuy':
			document.formdatos.v_consulta.value = '';
			break;
		case 'rearing':
			$('#v_repmacho').prop('checked', false);
			$('#v_rephembra').prop('checked', false);
			$('#v_pie').prop('checked', false);
			$('#v_parrentero').prop('checked', false);
			$('#v_parrtrozado').prop('checked', false);
			$('#v_vacio').prop('checked', false);
			break;
		case 'buyer':
			$('#v_mayorista').prop('checked', false);
			$('#v_restaurante').prop('checked', false);
			$('#v_exportacion').prop('checked', false);
			$('#v_consumo').prop('checked', false);
			break;
	}
}

function validateEmail(mail) {
	if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) return true;
	alert('Debes colocar un email válido');
	return false;
}

function verifica() {
	var cadena13 = document.formdatos.v_nombre.value;
	if (cadena13 == '') {
		alert('Debes ingresar tu nombre y apellido o razón social');
		formdatos.v_nombre.focus();
		return false;
	}

	cadena13 = document.formdatos.v_dniruc.value;
	if (cadena13 == '') {
		alert('Debes ingresar tu número de DNI o RUC');
		formdatos.v_dniruc.focus();
		return false;
	}
	cadena13 = document.formdatos.v_celular.value;
	if (cadena13 == '') {
		alert('Debes ingresar tu número de celular');
		formdatos.v_celular.focus();
		return false;
	}
	cadena13 = document.formdatos.v_direccion.value;
	if (cadena13 == '') {
		alert('Debes ingresar la dirección del lugar de crianza');
		formdatos.v_direccion.focus();
		return false;
	}

	//UBIGEO
	cadena13 = document.formdatos.v_province.value;
	if (cadena13 == -1) {
		alert('Debes ingresar la provincia donde está el lugar de crianza');
		formdatos.v_province.focus();
		return false;
	}
	cadena13 = document.formdatos.v_district.value;
	if (cadena13 == -1) {
		alert('Debes ingresar el distrito donde está el lugar de crianza');
		formdatos.v_district.focus();
		return false;
	}
	cadena13 = document.formdatos.v_department.value;
	if (cadena13 == -1) {
		alert('Debes ingresar el departamento donde está el lugar de crianza');
		formdatos.v_department.focus();
		return false;
	}
	//END UBIGEO
	cadena13 = document.formdatos.v_email.value;
	if (cadena13 == '') {
		alert('Debes ingresar tu correo electrónico');
		formdatos.v_email.focus();
		return false;
	} else {
		return validateEmail(cadena13);
	}
}
