// Inicializar Select2 en el elemento select
jQuery(document).ready(function(){
    var valor_boton = $('#boton_opciones');
    var valor_lista = $('#lista_opciones');
    var boton = $('#options');
    var lista = $('#option_list');
    var btntexto = $('#texto');
    var btnboton =  $('#botones');
    var btnlista = $('#lista');
    var ocultar_todo = $('#ocultar');
    const select_botones = $("#options");
    const select_lista = $('#option_list');
   
  
    boton.select2({ tags: true, placeholder: 'Agrega las opciones'});
    lista.select2({ tags: true, placeholder: 'Agrega la lista de opciones'});

    ocultar_todo.css({ "display": "none" })
    
    btntexto.click(function(){
      ocultar_todo.css({ "display": "none" })
    });

    btnboton.click(function(){
      valor_boton.css({ "display" : "block" })
      ocultar_todo.css({ "display" : "block" })
      valor_lista.css({ "display" : "none"})
    });  

    
    btnlista.click(function(){
      valor_boton.css({ "display" : "none" })
      valor_lista.css({ "display" : "block" })
      ocultar_todo.css({ "display" : "block" })
    });

    // Agregar un controlador de eventos para escuchar cambios en la selección
    select_botones.on("change", function (e) {
      window.select_btn = select_botones.val(); // Obtenemos un array de los valores seleccionados
      // `selectedValues` contiene los valores seleccionados
      
    });
    select_lista.on("change", function (e) {
      window.select_lst = select_lista.val();
      
    })
  

    select_botones.on('select2:opening', function(event) {
      // Obtiene la cantidad de opciones seleccionadas
      var cantidadOpcionesSeleccionadas = select_botones.val().length;

      // Si se alcanza el límite de opciones seleccionadas, previene la apertura del menú
      if (cantidadOpcionesSeleccionadas >= 3) {
          event.preventDefault();
          alert('Solo puedes seleccionar 3 opciones.');
      }
    });
    select_lista.on('select2:opening', function(event) {
      // Obtiene la cantidad de opciones seleccionadas
      var cantidadOpcionesSeleccionadas = select_lista.val().length;

      // Si se alcanza el límite de opciones seleccionadas, previene la apertura del menú
      if (cantidadOpcionesSeleccionadas >= 10) {
          event.preventDefault();
          alert('Solo puedes seleccionar 10 opciones.');
      }
    });


    select_botones.on('select2:selecting', function(event) {
      // Obtiene el texto de la opción seleccionada
      var textoOpcion = event.params.args.data.text;
      
      // Este texto si excede el liite de carcteres de la consulta
      if (textoOpcion.length > 20) {
          event.preventDefault(); // Evita que la opción se seleccione
          alert('La opción seleccionada excede el límite de 20 caracteres.');
      }
    });


    select_lista.on('select2:selecting', function(event) {
      // Obtiene el texto de la opción seleccionada
      var textoOpcion = event.params.args.data.text;
      
      // Verifica si el texto excede el límite de caracteres
      if (textoOpcion.length > 24) {
        event.preventDefault(); // Evita que la opción se seleccione
        alert('La opción seleccionada excede el límite de 20 caracteres.');
      }
    }); 
});