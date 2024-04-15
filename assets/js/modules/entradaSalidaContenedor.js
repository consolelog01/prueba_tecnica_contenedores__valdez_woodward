window.addEventListener("DOMContentLoaded", function () {

    const datetimepickerFechaSalida = $('#datetimepickerFechaSalida');

    datetimepickerFechaSalida.datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'es',
        useCurrent: false,
    });

    // ---- Actualización de tabla.
    actualizarTablaEntradaSalidaContenedor();

    $(document).on("click", ".btnActualizarTablaEntradaSalidaContenedores", function () {
        actualizarTablaEntradaSalidaContenedor();
    });

    function actualizarTablaEntradaSalidaContenedor() {

        let respuestaSolicitud = "warning";
        let imagenSolicitud = "warning.png";

        try {

            $.ajax({

                url: "../../../server/Routes.php",
                method: "POST",
                data: { rute: "cargarListaEntradaSalidaContenedor" },
                dataType: "html",
                success: function (data) {
                    $(".contenedor-tabla-contenedores-registrados").empty().html(data);
                }

            });

        } catch (mensajeSolicitud) {
            mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
        }

    }

    $(document).on("click", ".btnActualizarTablaEntradaSalidaContenedor", function () {
        actualizarTablaEntradaSalidaContenedor();
    });

    /**
     * 
     * REGISTRO SALIDA
     * 
     */

    // ---- Registrar entrada de contenedor.
    $(document).on("click", ".btnNumeroEconomico", function () {
        const dataset = $(this)[0].dataset;
        const idContenedorConsulta = dataset.idContenedor;
        $("input[name='idContenedorConsulta']").val(idContenedorConsulta);
        const idEntradaAlmacen = dataset.idEntradaAlmacen;
        $("input[name='idEntradaAlmacen']").val(idEntradaAlmacen);
        $("input[name='numeroEconomico']").val("").removeClass("campo-invalido");
        $("input[name='fechaSalida']").val("").removeClass("campo-invalido");
        $(".contenedorNumeroContenedor").text(dataset.numeroContenedor);
    });

    $(document).on("click", ".btnRegistrarSalidaContenedor", function () {
        registrarSalidaContenedor();
    });

    function registrarSalidaContenedor() {

        let claseSolicitud = "warning-solicitud";
        let imagenSolicitud = "warning.png";
        let respuestaSolicitud = "warning";

        try {

            $(".campo-invalido").removeClass("campo-invalido");

            /**
             * 
             * --- Validaciones
             * 
             */

            const campoContenedorConsulta = $("input[name='idContenedorConsulta']");
            const valorContenedorConsulta = campoContenedorConsulta.val().trim();
            if (valorContenedorConsulta == "" || valorContenedorConsulta <= 0 || !validarNumeroEntero(valorContenedorConsulta)) {
                throw "El contenedor al que está intentando registrar la salida no existe.";
            }

            const campoEntradaAlmacen = $("input[name='idEntradaAlmacen']");
            const valorEntradaAlmacen = campoEntradaAlmacen.val().trim();
            if (valorEntradaAlmacen == "" || valorEntradaAlmacen <= 0 || !validarNumeroEntero(valorEntradaAlmacen)) {
                throw "La entrada a almacen es incorrecta.";
            }

            const campoNumeroEconomico = $("input[name='numeroEconomico']");
            const valorNumeroEconomico = campoNumeroEconomico.val().trim();
            if (valorNumeroEconomico == "") {
                campoNumeroEconomico.addClass("campo-invalido");
                throw "El número económico no puede ser vacío.";
            }

            const fechaSalida = $("input[name='fechaSalida']");
            const valorFechaSalida = fechaSalida.val().trim();
            if (!moment(valorFechaSalida, "YYYY-MM-DD", true).isValid()) {
                fechaSalida.addClass("campo-invalido");
                throw "La fecha de salida es incorrecta.";
            }

            if (moment(valorFechaSalida).format("YYYY-MM-DD") < moment(new Date()).format("YYYY-MM-DD")) {
                fechaSalida.addClass("campo-invalido");
                throw "La fecha de salida del contenedor del almacen no puede ser menor a la fecha actual.";
            }

            $.ajax({

                url: "../../../server/Routes.php",
                method: "POST",
                data: "rute=registrarSalidaContenedor" + "&idContenedorConsulta=" + valorContenedorConsulta + "&numeroEconomico=" + valorNumeroEconomico + "&idEntradaAlmacen=" + valorEntradaAlmacen + "&fechaSalida=" + valorFechaSalida,
                dataType: "JSON",
                success: function (data) {

                    let estadoSolicitud = data["estado"];
                    let mensajeSolicitud = data["mensaje"];

                    try {

                        if (estadoSolicitud == "success") {
                            claseSolicitud = "exito-solicitud";
                            imagenSolicitud = "exito-solicitud.png";
                            respuestaSolicitud = "success";
                            $("#numeroEconomico").modal("hide");
                            campoNumeroEconomico.val("");
                            campoContenedorConsulta.val("");
                            fechaSalida.val("");
                        }

                        if (estadoSolicitud == "error") {
                            claseSolicitud = "error-interno-solicitud";
                            imagenSolicitud = "error-interno-solicitud.png";
                            respuestaSolicitud = "errorInterno";
                        }

                        throw mensajeSolicitud;

                    } catch (mensajeSolicitud) {
                        // --- Actualizar tabla administradores.
                        actualizarTablaEntradaSalidaContenedor();
                        mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
                    }

                }

            });

        } catch (mensajeSolicitud) {
            mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
        }

    }

});