/**
 * 
 * --- FUNCIONES DE VALIDACIÓN
 * 
 */

// --- Función para validar números enteros.
function validarNumeroEntero(numero) {
    const regex = new RegExp(/^[0-9]+$/);
    return regex.test(numero) ? true : false;
}

// --- Función para validar números enteros y decimales.
function validarNumeroDecimalEntero(numero) {
    const regex = new RegExp(/^\d{1,3}(,?\d{3})*(\.\d{1,2})?$/);
    return regex.test(numero) ? true : false;
}

// --- Validar número contenedor.
function validarNumeroContenedor(numero) {
    const regex = new RegExp(/([a-zA-Z]{3})([UJZujz])(\d{6})(\d)/);
    return regex.test(numero) ? true : false;
}

// --- Otras funciones

function obtenerAnioActual() {
    return moment(new Date()).format("YYYY-MM-DD");
}

// --- Notificación solicitud
function mostrarNotificacionSolicitud(respuestaSolicitud = "errorInterno", mensajeSolicitud = "", imagenSolicitud = "warning.png") {

    let colorFondoNotificacion = "colorFondoError";

    if (respuestaSolicitud == "success") {
        colorFondoNotificacion = "colorFondoSuccess";
    }

    if (respuestaSolicitud == "warning") {
        colorFondoNotificacion = "colorFondoWarning";
    }

    bootbox.alert({
        message: mensajeSolicitud,
        className: 'd-flex align-items-center ' + colorFondoNotificacion
    });

    mostrarIconoNotificacion(imagenSolicitud);

}

// ---- Función para cargar la imágen correpondientes según el caso.
function mostrarIconoNotificacion(imagenNotificacion = "warning.png") {

    $(".bootbox .modal-body").append(
        function () {
            var contenedorIconoNotificacion = $("<div>", { class: "contenedorIconoNotificacion" });
            const urlImagenNotificacion = "assets/imagenes/notificaciones/" + imagenNotificacion;
            var iconoNotificacion = $("<img>", { src: urlImagenNotificacion, class: "icononotificacion" });
            contenedorIconoNotificacion.append(iconoNotificacion);
            return contenedorIconoNotificacion;
        }
    );

}