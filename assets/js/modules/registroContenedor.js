window.addEventListener("DOMContentLoaded", function () {

    const datetimepickerFechaEntrada = $('#datetimepickerFechaEntrada');

    datetimepickerFechaEntrada.datetimepicker({
        format: 'YYYY-MM-DD',
        locale: 'es',
        useCurrent: false,
    });

    // ---- Actualización de tabla.
    actualizarTablaContenedores();

    function actualizarTablaContenedores() {

        let respuestaSolicitud = "warning";
        let imagenSolicitud = "warning.png";

        try {

            const numeroContenedor = "";
            const tamanioContenedor = "";

            $.ajax({

                url: "../../../server/Routes.php",
                method: "POST",
                data: { numeroContenedor: numeroContenedor, tamanioContenedor: tamanioContenedor, rute: "cargarListaContenedores" },
                dataType: "html",
                success: function (data) {
                    $(".contenedor-tabla-contenedores-registrados").empty().html(data);
                }

            });

        } catch (mensajeSolicitud) {
            mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
        }

    }

    $(document).on("click", ".btnActualizarTablaContenedores", function () {
        actualizarTablaContenedores();
    });

    // ---- Campos {filtros}
    function getFiltros() {

        let campoFiltroNombreAdministrador = $("input[name='f-nombreAdministrador']");
        let campoFiltroTipoAdministrador = $("select[name='f-tipoAdministrador']");
        let valorFiltroNombreAdministrador = campoFiltroNombreAdministrador.val().trim();
        let valorFiltroTipoAdministrador = campoFiltroTipoAdministrador.val().trim();
        let boolAplicarFiltros = false;

        if (valorFiltroNombreAdministrador != "" && valorFiltroNombreAdministrador.length > 3) {
            boolAplicarFiltros = true;
        } if (valorFiltroTipoAdministrador == "A" || valorFiltroTipoAdministrador == "SA") {
            boolAplicarFiltros = true;
        }

        return {
            valorFiltroNombreAdministrador: valorFiltroNombreAdministrador,
            valorFiltroTipoAdministrador: valorFiltroTipoAdministrador,
            boolAplicarFiltros: boolAplicarFiltros
        };

    }

    // ---- Guardar administrador
    $(document).on("click", ".guardarContenedor", function () {
        guardarContenedor();
    });

    function guardarContenedor() {

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

            let idContenedorConsulta = $("input[name='idContenedorConsulta']").val().trim();
            if (idContenedorConsulta != "" && (idContenedorConsulta <= 0 || !validarNumeroEntero(idContenedorConsulta))) {
                throw "El contenedor que está intentando actualizar no existe.";
            }

            const campoNumeroContenedor = $("input[name='numeroContenedor']");
            const valNumeroContenedor = campoNumeroContenedor.val().trim();
            if (valNumeroContenedor == "" || !validarNumeroContenedor(valNumeroContenedor)) {
                campoNumeroContenedor.addClass("campo-invalido");
                throw "El número del contenedor que esta intentando registrar es incorrecto.";
            } campoNumeroContenedor.val(valNumeroContenedor.toUpperCase());

            const campoTamanioContenedor = $("input[name='tamanioContenedor']");
            const valTamanioContenedor = campoTamanioContenedor.val().trim();
            if (valTamanioContenedor != "20HC" && valTamanioContenedor != "40HC") {
                campoTamanioContenedor.addClass("campo-invalido");
                throw "El tamaño del contenedor que esta intentando registrar es incorrecto.";
            }

            let frmRegistroPanel = $("#frmRegistroPanel").serialize();

            $.ajax({

                url: "../../../server/Routes.php",
                method: "POST",
                data: frmRegistroPanel + "&rute=guardarModificarContenedor",
                dataType: "JSON",
                success: function (data) {

                    let estadoSolicitud = data["estado"];
                    let mensajeSolicitud = data["mensaje"];

                    try {

                        if (estadoSolicitud == "success") {
                            claseSolicitud = "exito-solicitud";
                            imagenSolicitud = "exito-solicitud.png";
                            respuestaSolicitud = "success";
                            $(".modalRegistroPanel").modal("hide");
                            $("form .campo-form").val("");
                        }

                        if (estadoSolicitud == "error") {
                            claseSolicitud = "error-interno-solicitud";
                            imagenSolicitud = "error-interno-solicitud.png";
                            respuestaSolicitud = "errorInterno";
                        }

                        throw mensajeSolicitud;

                    } catch (mensajeSolicitud) {
                        // --- Actualizar tabla administradores.
                        actualizarTablaContenedores();
                        mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
                    }

                }

            });

        } catch (mensajeSolicitud) {
            mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
        }

    }

    // ---- Editar contenedor
    $(document).on("click", ".btnEditarContenedor", function () {
        const idContenedorConsulta = $(this)[0].dataset.idContenedor;
        consultarContenedor(idContenedorConsulta);
    });

    function consultarContenedor(idContenedorConsulta = 0) {

        let claseSolicitud = "warning-solicitud";
        let imagenSolicitud = "warning.png";
        let respuestaSolicitud = "warning";

        try {

            /**
             * 
             * --- Validaciones
             * 
             */

            if (idContenedorConsulta == "" || idContenedorConsulta <= 0 || !validarNumeroEntero(idContenedorConsulta)) {
                throw "El contenedor que está intentando consultar no existe.";
            }

            $.ajax({

                url: "../../../server/Routes.php",
                method: "POST",
                data: "idContenedorConsulta=" + idContenedorConsulta + "&rute=consultarContenedor",
                dataType: "JSON",
                success: function (data) {

                    let estadoSolicitud = data["estado"];
                    let mensajeSolicitud = data["mensaje"];
                    const idContenedor = data["idContenedor"];
                    const numero_contenedor = data["numero_contenedor"];
                    const tamanio_contenedor = data["tamanio_contenedor"];

                    try {

                        if (estadoSolicitud == "success") {
                            $(".modalRegistroPanel").modal("show");
                            $("input[name='numeroContenedor']").val(numero_contenedor);
                            $("input[name='tamanioContenedor']").val(tamanio_contenedor);
                            $("input[name='idContenedorConsulta']").val(idContenedor);
                            return;
                        }

                        if (estadoSolicitud == "error") {
                            claseSolicitud = "error-interno-solicitud";
                            imagenSolicitud = "error-interno-solicitud.png";
                            respuestaSolicitud = "errorInterno";
                        }

                        throw mensajeSolicitud;

                    } catch (mensajeSolicitud) {
                        // --- Actualizar tabla administradores.
                        actualizarTablaContenedores();
                        mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
                    }

                }

            });

        } catch (mensajeSolicitud) {
            mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
        }

    }

    // ---- Limpiar formulario
    $(document).on("click", ".btnLimpiarFormulario", function () {
        $("input[name='numeroContenedor']").val("");
        $("input[name='tamanioContenedor']").val("");
        $("input[name='idContenedorConsulta']").val("");
    });

    // ---- Eliminar contenedor.
    $(document).on("click", ".btnEliminarContenedor", function () {
        const idContenedorConsulta = $(this)[0].dataset.idContenedor;
        eliminarContenedor(idContenedorConsulta);
    });

    function eliminarContenedor(idContenedorConsulta = 0) {

        let claseSolicitud = "warning-solicitud";
        let imagenSolicitud = "warning.png";
        let respuestaSolicitud = "warning";

        try {

            /**
             * 
             * --- Validaciones
             * 
             */

            if (idContenedorConsulta == "" || idContenedorConsulta <= 0 || !validarNumeroEntero(idContenedorConsulta)) {
                throw "El contenedor que está intentando eliminar no existe.";
            }

            bootbox.confirm({
                message: "¿En realidad desea eliminar este contenedor?",
                buttons: {
                    confirm: {
                        label: 'Si',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn btn-danger'
                    }
                },
                callback: function (boolEliminar) {

                    if (boolEliminar) {

                        $.ajax({

                            url: "../../../server/Routes.php",
                            method: "POST",
                            data: "idContenedorConsulta=" + idContenedorConsulta + "&rute=eliminarContenedor",
                            dataType: "JSON",
                            success: function (data) {

                                let estadoSolicitud = data["estado"];
                                let mensajeSolicitud = data["mensaje"];

                                try {

                                    if (estadoSolicitud == "success") {
                                        claseSolicitud = "exito-solicitud";
                                        imagenSolicitud = "exito-solicitud.png";
                                        respuestaSolicitud = "success";
                                        $(".modalRegistroPanel").modal("hide");
                                        $("form .campo-form").val("");
                                    }

                                    if (estadoSolicitud == "error") {
                                        claseSolicitud = "error-interno-solicitud";
                                        imagenSolicitud = "error-interno-solicitud.png";
                                        respuestaSolicitud = "errorInterno";
                                    }

                                    throw mensajeSolicitud;

                                } catch (mensajeSolicitud) {
                                    // --- Actualizar tabla administradores.
                                    actualizarTablaContenedores();
                                    mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
                                }

                            }

                        });

                    }

                }

            });

            mostrarIconoNotificacion("warning.png");

        } catch (mensajeSolicitud) {
            mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
        }

    }

    // ---- Registrar entrada de contenedor.
    $(document).on("click", ".btnNumeroEconomico", function () {
        const dataset = $(this)[0].dataset;
        const idContenedorConsulta = dataset.idContenedor;
        $("input[name='idContenedorConsulta']").val(idContenedorConsulta);
        $("input[name='numeroEconomico']").val("").removeClass("campo-invalido");
        $("input[name='fechaEntrada']").val("").removeClass("campo-invalido");
        $(".contenedorNumeroContenedor").text(dataset.numeroContenedor);
    });

    $(document).on("click", ".btnRegistrarEntradaContenedor", function () {
        registrarEntradaContenedor();
    });

    function registrarEntradaContenedor() {

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
                throw "El contenedor al que está intentando registrar la entrada no existe.";
            }

            const campoNumeroEconomico = $("input[name='numeroEconomico']");
            const valorNumeroEconomico = campoNumeroEconomico.val().trim();
            if (valorNumeroEconomico == "") {
                campoNumeroEconomico.addClass("campo-invalido");
                throw "El número económico no puede ser vacío.";
            }

            const fechaEntrada = $("input[name='fechaEntrada']");
            const valorFechaEntrada = fechaEntrada.val().trim();
            if (!moment(valorFechaEntrada, "YYYY-MM-DD", true).isValid()) {
                fechaEntrada.addClass("campo-invalido");
                throw "La fecha de entrada es incorrecta.";
            }

            if (moment(valorFechaEntrada).format("YYYY-MM-DD") != moment(new Date()).format("YYYY-MM-DD")) {
                fechaEntrada.addClass("campo-invalido");
                throw "La fecha de entrada al almacen del contenedor no puede ser mayor o menor a la fecha actual.";
            }

            $.ajax({

                url: "../../../server/Routes.php",
                method: "POST",
                data: "rute=registrarEntradaContenedor" + "&idContenedorConsulta=" + valorContenedorConsulta + "&numeroEconomico=" + valorNumeroEconomico + "&fechaEntrada=" + valorFechaEntrada,
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
                            fechaEntrada.val("");
                        }

                        if (estadoSolicitud == "error") {
                            claseSolicitud = "error-interno-solicitud";
                            imagenSolicitud = "error-interno-solicitud.png";
                            respuestaSolicitud = "errorInterno";
                        }

                        throw mensajeSolicitud;

                    } catch (mensajeSolicitud) {
                        // --- Actualizar tabla administradores.
                        actualizarTablaContenedores();
                        mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
                    }

                }

            });

        } catch (mensajeSolicitud) {
            mostrarNotificacionSolicitud(respuestaSolicitud, mensajeSolicitud, imagenSolicitud);
        }

    }

});