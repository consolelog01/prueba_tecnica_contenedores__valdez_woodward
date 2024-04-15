<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Módulo | entrada y salida de almacen">
    <title>Módulo | entrada y salida de almacen</title>
    <!-- Custom fonts for this template-->
    <link href="assets/js/library/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="assets/fontawesome-5.15/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="assets/css/estilosPersonalizados.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="assets/css/bootboxEstilosPersonalizados.css">
    <link rel="stylesheet" href="assets/js/plugins/tempus-dominus/dist/css/tempus-dominus.css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/estilos-template-personalizados.css">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <? include 'menuPrincipal.php'; ?>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800"></h1>
                    </div>
                    <div class="row">
                        <div class="card shadow mb-4 w-100">
                            <div class="card-header py-3">
                                <div class="row justify-content-center w-100 filtros-busqueda-panel"></div>
                                <h3 class="text-center">Entradas y salidas del almacen</h3>
                                <div class="col-12 d-flex justify-content-center my-2">
                                    <button class="btn btn-success btnActualizarTablaEntradaSalidaContenedores" type="button">Actualizar tabla</button>
                                </div>
                                <div class="col-12 mb-2">
                                    <div class="table-responsive contenedorTablaRegistros">
                                        <table class="table table-striped">
                                            <thead>
                                                <th>#</th>
                                                <th>Número del contenedor</th>
                                                <th>Tamaño del contenedor</th>
                                                <th>Entrada</th>
                                                <th>No. económico entrada</th>
                                                <th>Fecha entrada</th>
                                                <th>Salida</th>
                                                <th>No. económico salida</th>
                                                <th>Fecha salida</th>
                                                <th>Registrar salida</th>
                                            </thead>
                                            <tbody class="contenedor-tabla-contenedores-registrados">
                                                <tr>
                                                    <td colspan="10">
                                                        <span class="badge bg-info text-white w-100 p-3">
                                                            <h5 class="mb-0">
                                                                <i class="fa fa-info circle-information mx-auto"></i>
                                                                Cargando...
                                                            </h5>
                                                        </span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span><i class="fa fa-copyright" aria-hidden="true"></i> 2024 Copyright, All Rights Reserved.</span>
                    </div>
                </div>
            </footer>
            <!-- Modal -->
            <div class="modal fade modalRegistroPanel" id="modalRegistroPanel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalRegistroPanelLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="modalRegistroPanelLabel">Registro de contenedor</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="frmRegistroPanel" method="POST">
                                <div class="row">
                                    <div class="form-group col-12 col-md-6">
                                        <label for="numeroContenedor">Número de contenedor:</label>
                                        <input type="text" name="numeroContenedor" class="form-control campo-form">
                                        <span class="campoObligatorio">Campo obligatorio</span>
                                    </div>
                                    <div class="form-group col-12 col-md-6">
                                        <label for="tamanioContenedor">Tamaño del contenedor:</label>
                                        <input type="text" name="tamanioContenedor" class="form-control campo-form">
                                        <span class="campoObligatorio">Campo obligatorio</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-success btnLimpiarFormulario" type="button">Limpiar
                                            formulario</button>
                                    </div>
                                </div>
                                <input type="hidden" name="idContenedorConsulta">
                                <input type="hidden" name="idEntradaAlmacen">
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary guardarContenedor">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="numeroEconomico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="numeroEconomicoLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="numeroEconomicoLabel">Registro de salida del almacen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label for="numeroEconomico">Número de contenedor:</label>
                                    <br>
                                    <span class="contenedorNumeroContenedor"></span>
                                </div>
                                <hr>
                                <div class="form-group col-12 col-md-6">
                                    <label for="numeroEconomico">Número económico:</label>
                                    <input type="text" name="numeroEconomico" class="form-control campo-form">
                                    <span class="campoObligatorio">Campo obligatorio</span>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="numeroEconomico">Fecha de salida:</label>
                                    <div class="input-group date" id="datetimepickerFechaSalida" data-target-input="nearest">
                                        <input type="text" class="form-control elementoObligatorio campoFormulario campoFormularioSoloTexto datetimepicker-input" name="fechaSalida" id="fechaSalida" data-target="#datetimepickerFechaSalida">
                                        <div class="input-group-append" data-target="#datetimepickerFechaSalida" data-toggle="datetimepicker">
                                            <div class="input-group-text d-block">
                                                <img src="../assets/imagenes/inputs/calendariocheck.png" alt="Seleccionar día festivo">
                                            </div>
                                        </div>
                                    </div>
                                    <span class="campoObligatorio">Campo obligatorio</span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary btnRegistrarSalidaContenedor">Aceptar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <div class="spinner-proceso-carga row justify-content-center align-items-center d-none">
        <img src="assets/imagenes/notificaciones/spinner-de-puntos.png" alt="Spinner carga">
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Bootstrap core JavaScript-->
    <script src="assets/js/library/jquery/dist/jquery.min.js"></script>
    <script src="assets/js/library/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/plugins/popperjs/core/dist/umd/popper.min.js"></script>
    <script src="assets/js/library/bootbox/dist/bootbox.all.min.js"></script>
    <script src="assets/js/plugins/moment/moment.js"></script>
    <script src="assets/js/plugins/moment/locale.js"></script>
    <script src="assets/js/plugins/tempus-dominus/dist/js/tempus-dominus.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="assets/js/plugins/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="assets/js/template/sb-admin-2.min.js"></script>
    <script src="assets/js/generalFunctions.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/modules/entradaSalidaContenedor.js"></script>
</body>

</html>