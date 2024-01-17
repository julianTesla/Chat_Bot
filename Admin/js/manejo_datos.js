jQuery(document).ready(function () {
  mostrar_flujos(0, 0);

  $('#guardar_mensaje').on('click', function () {
    guardarMensage();
  });

  $('#guardar_flujo').on('click', function () {
    guardar_flujo();
  });

  $('#nuevo_flujo').on('click', function () {
    nuevo_flujo();
  });
});


async function extraer_mensaje_principal(id) {
  return new Promise((resolve, reject) => {
    const param = JSON.stringify({
      id_flujo: id,
      funcion: 'extraer_mensaje_principal'
    });

    const recibir = peticion_datos(param);
    recibir.then(
      (response) => {
        const data = response;
        resolve(data); // Resuelve la promesa con los datos
      },
      (error) => {
        reject(error); // Rechaza la promesa en caso de error
      }
    );
  });
}


function nuevo_flujo() {
  $('#modal_id_flujo').val(0);
  $('#nombre_flujo').val('');
  $('#flujo_palabra_clave').val('');
}


function limpiar_modal_mensaje() {
  window.select_lst = [];
  window.select_btn = [];
  $('#contenido_mensaje').data('msjid', 0);
  $('#nombre_mensage').val("");
  $('#encabezado').val("");
  $('#cuerpo').val("");
  $('#pie').val("");
  $('#flujo_id_modal').val(0);
  $('#options').empty();
  $('#option_list').empty();
  $('#texto').click();
}


function nuevo_mensaje(flujo_id) {
  limpiar_modal_mensaje();
  $('#flujo_id_modal').val(flujo_id);
}


function guardar_flujo() {

  let id_flujo = parseInt($('#modal_id_flujo').val());
  let nombre_flujo = $('#nombre_flujo').val();
  let palabra_clave = $('#flujo_palabra_clave').val();

  const param = JSON.stringify({
    id_flujo: id_flujo,
    nombre_flujo: nombre_flujo,
    palabra_clave: palabra_clave,
    estado: 0,
    funcion: "guardar_flujo"
  });

  //console.log(param);
  const recibir = peticion_datos(param);
  recibir.then(
    (response) => {// "Success!"
      const data = response;
      // console.log(data);
      mostrar_flujos(0, 0);
    },
    (error) => {
      console.log(error); // "Error!"
    }
  );
  $('#cerrar_modal_flujo').click();
}


function modificar_flujo() {
  const id_flujo = $('#id_flujo');
  const nombre_flujo = $('#nombre_flujo');
  const palabra_clave = $('#flujo_palabra_clave');

  const param = JSON.stringify({
    id_flujo: id_flujo,
    nombre_flujo: nombre_flujo,
    palabra_clave: palabra_clave,
    funcion: "guardar_flujo"
  });

  const recibir = peticion_datos(param);
  recibir.then(
    (response) => {// "Success!"
      const data = response;
      console.log(data);
    },
    (error) => {
      console.log(error); // "Error!"
    }
  );
}


async function mostrar_flujos(id, accion) {
  let id_mensaje_principal = "";
  let html_flujo = "";
  if (accion === 1) {
    let flujo_extraido = await extraer_flujos(id);
    flujo_extraido = JSON.parse(flujo_extraido);
    //console.log(flujo_extraido);

    flujo_extraido.forEach(element => {
      $('#modal_id_flujo').val(element.id_flujo);
      $('#nombre_flujo').val(element.nombre_flujo);
      $('#flujo_palabra_clave').val(element.palabra_clave);
    });
  }
  if (accion === 0) {
    var html_final = "";
    let flujo_extraido = await extraer_flujos(id);
    flujo_extraido = JSON.parse(flujo_extraido);
    //console.log(flujo_extraido);

    for (let element of flujo_extraido) {
      let id_msg = 0;
      let nombre_msg = "";


      id_mensaje_principal = await extraer_mensaje_principal(element.id_flujo);
      id_mensaje_principal = JSON.parse(id_mensaje_principal);
      //console.log(id_mensaje_principal[0].id_msg);
      if (id_mensaje_principal[0].id_msg != 'undefined') {
        id_msg = id_mensaje_principal[0].id_msg;
        nombre_msg = id_mensaje_principal[0].nombre_msg;
      }
      html_flujo = flujos_html(element.id_flujo, element.nombre_flujo, id_msg, nombre_msg);
      html_final = html_final + html_flujo;
    };
    $('#flujos_container').html(html_final);
  }
}


function extraer_flujos(id) {
  let id_flujo = 0;
  if (id != 0) {
    id_flujo = id
  } else {
    id_flujo = 0;
  }

  const param = JSON.stringify({
    id_flujo: id_flujo,
    funcion: 'extraer_flujos'
  });

  const recibir = peticion_datos(param);
  recibir.then(
    (response) => {// "Success!"
      const data = response;
    },
    (error) => {
      console.log(error); // "Error!"
    }
  );
  return recibir;
}


async function peticion_datos(param) {
  const response = await fetch('back/flujos_mensajes/resipiente.php', {
    method: 'POST',
    body: param
  });

  if (response.ok) {
    const data = await response.text();
    return data;
  }
}


async function guardarMensage() {
  var opcion = 0;
  var datos = extraer_datos_modal();
  switch (datos.TIPO) {
    case "1": opcion = 0; break;
    case "2": opcion = datos.OPCIONES_BTN; break;
    case "3": opcion = datos.OPCIONES_LIST; break;
  }

  const param = JSON.stringify({
    id: datos.ID,
    nombre: datos.NOMBRE_MENSAJE,
    encabezado: datos.ENCABEZADO,
    cuerpo: datos.CUERPO,
    pie: datos.PIE,
    tipo: datos.TIPO,
    estado: datos.ESTADO,
    opciones: opcion,
    id_flujo: datos.FLUJO_ID,
    funcion: "guardar_mensaje"
  });

  const recibir = peticion_datos(param);
  recibir.then(
    (response) => {// "Success!"
      const data = response;
    },
    (error) => {
      console.log(error); // "Error!"
    }
  );
  await extraer_contenido_mensaje(datos.ID);

  if (datos.ID == 0) {
    setTimeout(function () {
      mostrar_flujos(0, 0);
    }, 1000);
  }

  window.select_lst = [];
  window.select_btn = [];
  $('#cerrar_modal').click();
}

async function agregar_opcion (id) {

  let datos_mensaje = await extraer_mensajes(id);
  datos_mensaje = JSON.parse(datos_mensaje);
  let mensaje = datos_mensaje.mensaje[0];
  

  $('#contenido_mensaje').data('msjid', mensaje.id_msg);
  $('#nombre_mensage').val(mensaje.nombre_msg);
  $('#encabezado').val(mensaje.encabezado_msg);
  $('#cuerpo').val(mensaje.cuerpo_msg);
  $('#pie').val(mensaje.pie_msg);
  $('#flujo_id_modal').val(mensaje.flujo_id);
  $('#options').empty();
  $('#option_list').empty();


  let datos = extraer_datos_modal();
  const param = JSON.stringify({
    id: datos.ID,
    nombre: datos.NOMBRE_MENSAJE,
    encabezado: datos.ENCABEZADO,
    cuerpo: datos.CUERPO,
    pie: datos.PIE,
    tipo: datos.TIPO,
    estado: datos.ESTADO,
    opciones: 0,
    id_flujo: datos.FLUJO_ID,
    funcion: "guardar_opcion"
  });
  
  const recibir = peticion_datos(param);
  recibir.then(
    (response) => {// "Success!"
      const data = response;
    },
    (error) => {
      console.log(error); // "Error!"
    }
  );
  await extraer_contenido_mensaje(id);
}


async function extraer_mensajes(param_id) {
  let ID = param_id;

  return new Promise((resolve, reject) => {
    const param = JSON.stringify({
      id: ID,
      nombre: "",
      encabezado: "",
      cuerpo: "",
      pie: "",
      tipo: 1,
      estado: 0,
      id_flujo: 0,
      funcion: "extraer_mensaje"
    });

    const recibir = peticion_datos(param);
    recibir.then(
      (response) => {
        const data = response;
        resolve(data); // Resuelve la promesa con los datos
      },
      (error) => {
        reject(error); // Rechaza la promesa en caso de error
      }
    );
  });
}


function extraer_datos_modal() {
  var datos = {
    ID: $('#contenido_mensaje').data('msjid'),
    NOMBRE_MENSAJE: $('#nombre_mensage').val(),
    ENCABEZADO: $('#encabezado').val(),
    CUERPO: $('#cuerpo').val(),
    PIE: $('#pie').val(),
    TIPO: $("input[name='tipo']:checked").val(),
    FLUJO_ID: $("#flujo_id_modal").val(),
    ESTADO: 0,
    OPCIONES_BTN: window.select_btn,
    OPCIONES_LIST: window.select_lst
  };
  return datos;
}


async function extraer_mostrar_msj(param_id) {
  limpiar_modal_mensaje();

  const ID = param_id;
  try {
    let datos_mensaje = await extraer_mensajes(ID);
    datos_mensaje = JSON.parse(datos_mensaje);

    const opciones_existentes = [];
    let datos_opciones = [];
    let i = 0;
    let mensaje = datos_mensaje.mensaje[0];
    let opciones = datos_mensaje.opciones;

    $('#contenido_mensaje').data('msjid', mensaje.id_msg);
    $('#nombre_mensage').val(mensaje.nombre_msg);
    $('#encabezado').val(mensaje.encabezado_msg);
    $('#cuerpo').val(mensaje.cuerpo_msg);
    $('#pie').val(mensaje.pie_msg);
    $('#flujo_id_modal').val(mensaje.flujo_id);
    $('#options').empty();
    $('#option_list').empty();

    opciones.forEach(element => {
      datos_opciones[i] =
      {
        id: element.opciones_id,
        text: element.nombre_msg,
        selected: true
      };

      opciones_existentes[i] = element.opciones_id;
      i++;
    });

    //console.log(opciones_existentes);

    switch (mensaje.tipo_msg) {
      case 1: $('input[name="tipo"][value="1"]').prop("checked", true);
        $('#texto').click();
        break;
      case 2: $('input[name="tipo"][value="2"]').prop("checked", true);
        window.select_btn = opciones_existentes;
        $('#options').select2({ tags: true, placeholder: 'Agrega las opciones', data: datos_opciones });
        $('#botones').click();
        break;
      case 3: $('input[name="tipo"][value="3"]').prop("checked", true);
        window.select_lst = opciones_existentes;
        $('#options').select2({ tags: true, placeholder: 'Agrega las opciones' });
        $('#option_list').select2({ tags: true, placeholder: 'Agrega las opciones', data: datos_opciones });
        $('#lista').click();
        break;
    }
  } catch (error) {
    console.error(error);
  }
}


async function extraer_contenido_mensaje(param_id) {

  const ID = param_id;
  try {
    let datos_mensaje = await extraer_mensajes(ID);
    datos_mensaje = JSON.parse(datos_mensaje);

    let i = 0;
    let mensaje = datos_mensaje.mensaje[0];
    let opciones = datos_mensaje.opciones;
    let contenedor = '#contenido_mensaje' + mensaje.id_msg;

    let html_card =
      '<div id="idmensage"> <b>' +
      mensaje.encabezado_msg + '<b> <br>' +
      mensaje.cuerpo_msg + '<br><small>' +
      mensaje.pie_msg + '</small>'
      + '</div>';

    $(contenedor).html(html_card);

    opciones.forEach(element => {

      let html_opciones = crear_html_opciones(element.opciones_id, element.nombre_msg);
      $(contenedor).append(html_opciones);
      i++;
    });

    if(mensaje.tipo_msg == 1){
      mensaje_principal = '<button id="nuevo_mensaje" type="button" class="btn btn-info" onclick="agregar_opcion('+mensaje.id_msg+')">'+
      'Añadir opcion'+
      '</button>';
      $(contenedor).append(mensaje_principal);
    }
      
  } catch (error) {
    console.log(error);
  }

}


function crear_html_opciones(id, nombre_opcion) {

  const html_mensaje = '<!-- INICIO DE SECCION DE CARTA -->' +
    '<div class="col-md">' +
    '<div class="card card-primary collapsed-card">' +
    '<div class="card-header">' +
    '<h3 class="card-title">' + nombre_opcion + '</h3>' +
    '<div class="card-tools">' +
    '<button type="button" class="btn btn-tool" data-toggle="modal" data-target="#modal-xl" onclick="extraer_mostrar_msj(' + id + ')">' +
    '<i class="nav-icon fas fa-edit"></i>' +
    '</button>' +
    '<button type="button" class="btn btn-tool" data-card-widget="collapse" onclick="extraer_contenido_mensaje(' + id + ')">' +
    '<i class="fas fa-plus"></i>' +
    '</button>' +
    '<button type="button" class="btn btn-tool" data-card-widget="maximize">' +
    '<i class="fas fa-expand"></i>' +
    '</button>' +
    '</div>' +
    '<!-- /.card-tools -->' +
    '</div>' +
    '<!-- /.card-header -->' +
    '<div class="card-body" style="display: none;">' +
    '<div id="contenido_mensaje' + id + '">' +

    '</div>' +
    '</div>' +
    '<!-- /.card-body -->' +
    '</div>' +
    '<!-- /.card -->' +
    '</div>' +
    '<!-- FIN DE SECCION DE CARTA -->';
  return html_mensaje;
}

function flujos_html(id, nombre_flujo, id_mensaje, nombre_mensaje) {

  let mensaje_principal = "";
  if (id_mensaje == 0) {
    mensaje_principal = '<button id="nuevo_mensaje" type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-xl" onclick="nuevo_mensaje(' + id + ')">'+
      'Nuevo mensaje'+
      '</button>';
  } else {
    mensaje_principal = crear_html_opciones(id_mensaje, nombre_mensaje);
  }

  var html_flujos = '<div class="col-md">' +
    '<div class="card card-success collapsed-card">' +
    '<div class="card-header">' +
    '<h3 class="card-title">' + nombre_flujo + '</h3>' +
    '<div class="card-tools">' +
    '<button type="button" class="btn btn-tool" data-toggle="modal"  data-target="#modal-lg" onclick="mostrar_flujos(' + id + ',1)">' +
    '<i class="nav-icon fas fa-edit"></i>' +
    '</button>' +
    '<button type="button" class="btn btn-tool" data-card-widget="collapse" onclick="crear_html_opciones(' + id_mensaje + ',' + nombre_mensaje + ')">' +
    '<i class="fas fa-plus"></i>' +
    '</button>' +
    '</div>' +
    '<!-- /.card-tools -->' +
    '</div>' +
    '<!-- /.card-header -->' +
    '<div class="card-body" style="display: none;">' +

    mensaje_principal +

    '</div>' +
    '<!-- /.card-body -->' +
    '</div>' +
    '<!-- /.card -->' +
    '</div>';
  return html_flujos;
}