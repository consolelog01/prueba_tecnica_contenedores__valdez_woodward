window.addEventListener("DOMContentLoaded", function () {

    // Inputs only time
    if (window.tempusDominus != undefined) {

        const arrayElementosTime = $('.date-time-tempus');

        for (const elementoTime of arrayElementosTime) {
            new tempusDominus.TempusDominus(
                elementoTime,
                {
                    display: {
                        theme: 'dark',
                        viewMode: 'clock',
                        components: {
                            decades: false,
                            year: false,
                            month: false,
                            date: false,
                            hours: true,
                            minutes: true,
                            seconds: false
                        }
                    },
                }
            );
        }

    }

    $(document).ajaxStart(function () {
        $(".spinner-proceso-carga").removeClass("d-none");
        $(".contenedorBotonesAcciones .btnPrincipal").attr({ disabled: true });
    });

    $(document).ajaxSuccess(function (event, xhr, settings) {

        let data = xhr.responseJSON;
        let status = xhr.status;

        let inter = $("input[name='inter']");
        if (inter.length > 0) {
            let valInter = inter.val(inter[0].defaultValue);
            inter.attr("disabled", true);
        }

        $(".contenedorBotonesAcciones .btnPrincipal").attr({ disabled: false });
        $(".spinner-proceso-carga").removeClass("d-flex").addClass("d-none");

        // --- Error interno
        if (Array.isArray(data) || (typeof data == 'object')) {

            if (data.solicitud != "login" && (data[0] == false || data.estado == false)) {

                bootbox.alert({
                    message: data[1] || data.mensaje,
                    className: 'd-flex align-items-center modal-http-500'
                });

                mostrarIconoNotificacion("warning.png");

            }

        }

        // --- Si hay una sesión activa.
        if (data != undefined && data.estadoSesion) {
            window.location = "/panel/crear-rubricas";
        }

        // --- Instancia tooltips.
        inicializarToolTips();

    });

    $(document).ajaxError(function (event, jqxhr, settings, thrownError) {

        if (Object.values(jqxhr).length > 0) {

            let data = jqxhr.responseJSON;
            let status = jqxhr.status;
            let statusText = jqxhr.status;

            if (statusText == "unknown status") {
                cerrarSesion();
            }

            if (status == 500) {

                bootbox.alert({
                    message: "¡Error interno server!",
                    className: 'd-flex align-items-center modal-http-500 colorFondoError'
                });

                mostrarIconoNotificacion("error-interno-solicitud.png");

            }

            if (status == 404) {

                bootbox.alert({
                    message: "¡Recurso no encontrado!",
                    className: 'd-flex align-items-center modal-http-404 colorFondoWarning'
                });

                mostrarIconoNotificacion("warning.png");

            }

            if (status == 405) {

                bootbox.alert({
                    message: "¡Método no permitido!",
                    className: 'd-flex align-items-center modal-http-500 colorFondoError'
                });

                mostrarIconoNotificacion("warning.png");

            }

            $(".contenedorBotonesAcciones .btnPrincipal").attr({ disabled: false });
            $(".spinner-proceso-carga").removeClass("d-flex").addClass("d-none");

        }

    });

    // --- Recetear campos invalidos.
    $(document).on("keyup", "input, textarea", function () {
        let valorCampo = $(this)[0].value.trim();
        if (valorCampo.length > 0) {
            $(this)[0].classList.remove("campo-invalido");
        }
    });

    // --- Recetear campos invalidos.
    $(document).on("change", "select", function () {
        let valorCampo = $(this)[0].value.trim();
        if (valorCampo != 0) {
            $(this)[0].classList.remove("campo-invalido");
        }
    });

    $(document).on("click", "input[type='checkbox'], input[type='radio']", function () {
        let elemento = $(this)[0];
        let valorCampo = elemento.value.trim();
        if (valorCampo != 0 && elemento.checked) {
            $(this)[0].classList.remove("campo-invalido");
        }
    });

});

$(document).on("click", ".btnCerrarNotificacion", function () {
    $(".notificacionesError").addClass("d-none");
});


// --- Cargar spiner
function cargarSpiner(contenedorInformacion = ".contenedor-proceso-carga-tabla") {

    // --- Proceso de carga {tabla}.
    let contenedorCargaProcesoTabla = Ele.ele("div").attr({ class: "carga-proceso-tabla" });
    let imgCarga = Ele.ele("img").attr({ src: window.location.origin + "/imagenes/spiner/spiner_parte_b.svg" });
    contenedorCargaProcesoTabla.append(imgCarga);
    $(contenedorInformacion).empty().append(contenedorCargaProcesoTabla);

}

/**
 * Tooltip
 */
function inicializarToolTips() {
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
}

// --- Limpiar formulario
$(document).on("click", ".btnLimpiarFormulario", function () {
    limpiarFormulario();
});

function limpiarFormulario() {

    let formulario = $("form");
    if (formulario.length == 0) {

        bootbox.alert({
            message: "¡No hay nada para limpiar!",
            className: 'd-flex align-items-center modal-http-500'
        });

        mostrarIconoNotificacion("error-interno-solicitud.png");

    }

    let arrayElementosForm = formulario[0].elements;
    for (const elemento of arrayElementosForm) {
        elemento.value = "";
    }

}