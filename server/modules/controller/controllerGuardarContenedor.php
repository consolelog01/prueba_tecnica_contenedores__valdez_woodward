<?

// ---- Conexión a BD.
include 'conexion_db/Conexion.php';
include_once '././Routes.php';
include './functions/GeneralFunctions.php';
include '../server/modules/model/ModelGuardarContenedor.php';
include '../views/controllerContenedor/ViewHTMLListaContenedor.php';

class ControllerGuardarContenedor
{

    private $tipoError;
    private $ModelGuardarContenedor;
    private $ViewHTMLListaContenedor;
    private $GeneralFunctions;
    private $error;
    private $db;

    function __construct()
    {
        $this->tipoError = "server";
        $this->error = "warning";
        $this->ModelGuardarContenedor = new ModelGuardarContenedor();
        $this->ViewHTMLListaContenedor = new ViewHTMLListaContenedor();
        $this->GeneralFunctions = new GeneralFunctions();
        $Conexion = new Conexion();
        $this->db = $Conexion->BD();
    }

    // ---- Cargar lista de contenedores
    public function cargarListaContenedores()
    {
        try {

            // --- Filtros de búsqueda.
            $numeroContenedor = trim($_POST["numeroContenedor"]);
            $tamanioContenedor = trim($_POST["tamanioContenedor"]);

            $query = "SELECT idcontenedorpk, numero_contenedor, tamanio_contenedor FROM contenedor";

            if (!empty($numeroContenedor) || !empty($tamanioContenedor)) {
                $query .= " WHERE";
            }
            if (!empty($numeroContenedor)) {
                $query .= " numero_contenedor = :numero_contenedor";
            }
            if (!empty($tamanioContenedor)) {
                $query .= " && tamanio_contenedor = :tamanio_contenedor";
            }

            $query .= " ORDER BY idcontenedorpk DESC";

            $sql = $this->db->prepare($query);

            if (!empty($numeroContenedor)) {
                $sql->bindParam(':numero_contenedor', $numeroContenedor, PDO::PARAM_STR);
            }
            if (!empty($tamanioContenedor)) {
                $sql->bindParam(':tamanio_contenedor', $tamanioContenedor, PDO::PARAM_STR);
            }

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            // ---- Mostrando resultados.
            $arrayContenedores = $sql->fetchAll(PDO::FETCH_ASSOC);
            $this->ViewHTMLListaContenedor->ViewHTMLListaContenedor($arrayContenedores);
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => $this->error, 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
        }
    }

    // --- Guardar modificar contenedor.
    public function guardarModificarContenedor()
    {
        try {

            $idContenedorConsulta = trim($_POST["idContenedorConsulta"]);
            $numeroContenedor = trim($_POST["numeroContenedor"]);
            $tamanioContenedor = trim($_POST["tamanioContenedor"]);

            /**
             * 
             * --- Validaciones
             * 
             */

            if ($idContenedorConsulta != "" && ($idContenedorConsulta <= 0 || !$this->GeneralFunctions->validarNumeroEntero($idContenedorConsulta))) {
                throw new PDOException("El contenedor que está intentando actualizar no existe.");
            }

            if ($numeroContenedor == "" || !$this->GeneralFunctions->validarNumeroContenedor($numeroContenedor)) {
                throw new PDOException("El número del contenedor que esta intentando registrar es incorrecto.");
            }

            if ($tamanioContenedor != "20HC" && $tamanioContenedor != "40HC") {
                throw new PDOException("El tamaño del contenedor que esta intentando registrar es incorrecto.");
            }

            if (empty($idContenedorConsulta)) {

                // ---- Se valida que el contenedor no exista.
                $sql = $this->db->prepare("SELECT idcontenedorpk FROM contenedor WHERE numero_contenedor = :numero_contenedor");

                $sql->bindParam(':numero_contenedor', $numeroContenedor, PDO::PARAM_STR);

                if (!$sql->execute()) {
                    $this->error = 'error';
                    throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
                }

                $rowContenedores = $sql->fetchAll(PDO::FETCH_ASSOC);

                if (count($rowContenedores) == 0) {

                    $estadoConsulta = $this->ModelGuardarContenedor->guardarContenedor($numeroContenedor, $tamanioContenedor);

                    if ($estadoConsulta) {
                        $arrayMensajeConsulta = array('estado' => 'success', 'mensaje' => "Contenedor registrado/actualizado correctamente.");
                    }
                } else {
                    $estadoConsulta = true;
                    $arrayMensajeConsulta = array('estado' => 'warning', 'mensaje' => "El contenedor que esta intentado registrar ya existe.");
                }
            } else {

                $estadoConsulta = $this->ModelGuardarContenedor->modificarContenedor($numeroContenedor, $tamanioContenedor, $idContenedorConsulta);

                if ($estadoConsulta) {
                    $arrayMensajeConsulta = array('estado' => 'success', 'mensaje' => "Contenedor registrado/actualizado correctamente.");
                }
            }

            // --- Respuesta
            if ($estadoConsulta) {
                print_r(json_encode($arrayMensajeConsulta));
            }
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => $this->error, 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
        }
    }

    // --- Consultar contenedor.
    public function consultarContenedor()
    {
        try {

            $idContenedorConsulta = trim($_POST["idContenedorConsulta"]);

            // Validaciones
            if ($idContenedorConsulta <= 0 || !$this->GeneralFunctions->validarNumeroEntero($idContenedorConsulta)) {
                throw new PDOException("El contenedor que está intentando consultar no existe.");
            }

            $sql = $this->db->prepare("SELECT idcontenedorpk as idContenedor, numero_contenedor, tamanio_contenedor FROM contenedor WHERE idcontenedorpk = :idcontenedorpk");

            $sql->bindParam(':idcontenedorpk', $idContenedorConsulta, PDO::PARAM_INT);

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            $rowContenedores = $sql->fetch(PDO::FETCH_ASSOC);
            $arrayMensajeConsulta = array('estado' => 'warning', 'mensaje' => "El contenedor que esta intentando consultar no existe.");

            if (count($rowContenedores) > 0) {
                $arrayMensajeConsulta = array('estado' => 'success', "idContenedor" => $rowContenedores["idContenedor"], "numero_contenedor" => $rowContenedores["numero_contenedor"], "tamanio_contenedor" => $rowContenedores["tamanio_contenedor"]);
            }

            print_r(json_encode($arrayMensajeConsulta));
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => $this->error, 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
        }
    }

    // --- Eliminar contenedor.
    public function eliminarContenedor()
    {
        try {

            // Activando la transacción, se desactiva el modo autocommit.
            $this->db->beginTransaction();

            $idContenedorConsulta = trim($_POST["idContenedorConsulta"]);

            // Validaciones
            if ($idContenedorConsulta <= 0 || !$this->GeneralFunctions->validarNumeroEntero($idContenedorConsulta)) {
                throw new PDOException("El contenedor que está intentando eliminar no existe.");
            }

            $sql = $this->db->prepare("CALL eliminarEntradaAlmacen(:idContenedor)");

            $sql->bindParam(':idContenedor', $idContenedorConsulta, PDO::PARAM_INT);

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            // Se aplican los cambios.
            $this->db->commit();

            $arrayMensajeConsulta = array('estado' => 'success', 'mensaje' => "Contenedor eliminado correctamente.");
            print_r(json_encode($arrayMensajeConsulta));
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => $this->error, 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
            // Se deshacen los cambios.
            $this->db->rollBack();
        }
    }

    /**
     * 
     * ---- ENTRADAS Y SALIDAS CONTENEDOR
     * 
     */

    // --- Registrar entrada contenedor.
    public function registrarEntradaContenedor()
    {
        try {

            $idContenedorConsulta = trim($_POST["idContenedorConsulta"]);
            $numeroEconomico = trim($_POST["numeroEconomico"]);
            $fechaEntrada = trim($_POST["fechaEntrada"]);

            /**
             * 
             * --- Validaciones
             * 
             */

            if ($idContenedorConsulta == "" || $idContenedorConsulta <= 0 || !$this->GeneralFunctions->validarNumeroEntero($idContenedorConsulta)) {
                throw new PDOException("El contenedor al que está intentando registrar la entrada no existe.");
            }

            if (empty($numeroEconomico)) {
                throw new PDOException("El número económico no puede ser vacío.");
            }

            if (!$this->GeneralFunctions->validarFecha($fechaEntrada, 'Y-m-d')) {
                throw new PDOException("La fecha de entrada es incorrecta.");
            }

            if ($fechaEntrada != date("Y-m-d")) {
                throw new PDOException("La fecha de entrada al almacen del contenedor no puede ser mayor o menor a la fecha actual.");
            }

            // --- Validar que el contenedor exista.
            $sql = $this->db->prepare("SELECT idcontenedorpk FROM contenedor WHERE idcontenedorpk = :idcontenedorpk");

            $sql->bindParam(':idcontenedorpk', $idContenedorConsulta, PDO::PARAM_INT);

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            $rowContenedores = $sql->fetchAll(PDO::FETCH_ASSOC);

            if (count($rowContenedores) == 0) {
                throw new PDOException("El contenedor al que está intentando registrar la entrada no existe.");
            }


            // --- Validar que exista el contenedor dentro del almacen y no exista una salida.
            $sql = $this->db->prepare("CALL validarContenedorAlmacenSinSalida(:idcontenedorpk)");

            $sql->bindParam(':idcontenedorpk', $idContenedorConsulta, PDO::PARAM_INT);

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            $rowContenedores = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            if (count($rowContenedores) > 0) {
                $estadoPeticion = true;
                $arrayMensajeConsulta = array('estado' => 'warning', 'mensaje' => "Este contenedor ya se encuentra dentro del almacen pero aún no a salido.");
            } else {
                // --- Se registra entrada
                $estadoPeticion = $this->ModelGuardarContenedor->registrarEntradaContenedor($idContenedorConsulta, $numeroEconomico, $fechaEntrada);
                if ($estadoPeticion) {
                    $arrayMensajeConsulta = array('estado' => 'success', 'mensaje' => "Se registro la entrada del contenedor al almacen correctamente.");
                }
            }

            if ($estadoPeticion) {
                print_r(json_encode($arrayMensajeConsulta));
            }
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => $this->error, 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
        }
    }

    // --- Registrar entrada contenedor.
    public function registrarSalidaContenedor()
    {
        try {

            $estadoPeticion = false;
            $idContenedorConsulta = trim($_POST["idContenedorConsulta"]);
            $idEntradaAlmacen = trim($_POST["idEntradaAlmacen"]);
            $numeroEconomico = trim($_POST["numeroEconomico"]);
            $fechaSalida = trim($_POST["fechaSalida"]);

            /**
             * 
             * --- Validaciones
             * 
             */

            if ($idContenedorConsulta == "" || $idContenedorConsulta <= 0 || !$this->GeneralFunctions->validarNumeroEntero($idContenedorConsulta)) {
                throw new PDOException("El contenedor al que está intentando registrar la entrada no existe.");
            }

            if ($idEntradaAlmacen == "" || $idEntradaAlmacen <= 0 || !$this->GeneralFunctions->validarNumeroEntero($idEntradaAlmacen)) {
                throw new PDOException("La entrada a almacen es incorrecta.");
            }

            if (empty($numeroEconomico)) {
                throw new PDOException("El número económico no puede ser vacío.");
            }

            if (!$this->GeneralFunctions->validarFecha($fechaSalida, 'Y-m-d')) {
                throw new PDOException("La fecha de salida es incorrecta.");
            }

            if ($fechaSalida < date("Y-m-d")) {
                throw new PDOException("La fecha de salida del contenedor del almacen no puede ser menor a la fecha actual.");
            }

            // --- Validar que el contenedor exista.
            $sql = $this->db->prepare("SELECT idcontenedorpk FROM contenedor WHERE idcontenedorpk = :idcontenedorpk");

            $sql->bindParam(':idcontenedorpk', $idContenedorConsulta, PDO::PARAM_INT);

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            $rowContenedores = $sql->fetchAll(PDO::FETCH_ASSOC);

            if (count($rowContenedores) == 0) {
                throw new PDOException("El contenedor al que está intentando registrar la salida no existe.");
            }

            // --- Validar que el id de la entrada exista en la tabla de la entrada.
            $sql = $this->db->prepare("SELECT entrada_almacenpk, fecha_entrada FROM entrada_almacen WHERE entrada_almacenpk = :entrada_almacenpk");

            $sql->bindParam(':entrada_almacenpk', $idEntradaAlmacen, PDO::PARAM_INT);

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            $rowContenedores = $sql->fetch(PDO::FETCH_ASSOC);

            if (!$rowContenedores) {
                throw new PDOException("No existe ninguna estrada para este contenedor.");
            }

            $fechaEntradaAlmacen = $rowContenedores["fecha_entrada"];

            if ($fechaSalida < $fechaEntradaAlmacen) {
                throw new PDOException("La fecha de salida no puede ser menor a la fecha de entrada.");
            }

            if ($fechaSalida > $fechaEntradaAlmacen) {
                throw new PDOException("La fecha de salida no puede ser mayor que la fecha actual o la fecha de entrada.");
            }

            /**
             * 
             * --- Proceso registro salida.
             * 
             */

            // --- Validar que exista el contenedor dentro del almacen y no exista una salida con el id de la entrada asignada a la tabla de salida.
            $query = "SELECT idcontenedorpk, entrada_almacenpk, salida_almacenpk FROM contenedor INNER JOIN entrada_almacen ON entrada_almacen.idcontenedorfk = contenedor.idcontenedorpk LEFT JOIN salida_almacen ON salida_almacen.entrada_almacenfk = entrada_almacen.entrada_almacenpk WHERE idcontenedorpk = :idcontenedorpk AND salida_almacen.fecha_salida AND entrada_almacenfk = :entrada_almacenfk";

            $sql = $this->db->prepare($query);

            $sql->bindParam(':idcontenedorpk', $idContenedorConsulta, PDO::PARAM_INT);
            $sql->bindParam(':entrada_almacenfk', $idEntradaAlmacen, PDO::PARAM_INT);

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            $rowContenedores = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            if (count($rowContenedores) > 0) {
                $estadoPeticion = true;
                $arrayMensajeConsulta = array('estado' => 'warning', 'mensaje' => "No existe ninguna entrada al almacen para este contenedor.");
            } else {
                // --- Se registra salida.
                $estadoPeticion = $this->ModelGuardarContenedor->registrarSalidaContenedor($idContenedorConsulta, $idEntradaAlmacen, $numeroEconomico, $fechaSalida);
                if ($estadoPeticion) {
                    $arrayMensajeConsulta = array('estado' => 'success', 'mensaje' => "Se registro la salida del contenedor correctamente.");
                }
            }

            if ($estadoPeticion) {
                print_r(json_encode($arrayMensajeConsulta));
            }
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => $this->error, 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
        }
    }

    // --- Lista de entradas y salidas de contenedores.
    public function cargarListaEntradaSalidaContenedor()
    {
        try {
            // --- Validar que exista el contenedor dentro del almacen y no exista una salida.
            $sql = $this->db->prepare("SELECT idcontenedorpk, entrada_almacenpk, numero_contenedor, tamanio_contenedor, fecha_entrada, flujo_entrada, entrada_almacen.numero_economico AS numEcoEntrada, fecha_salida, flujo_salida, salida_almacen.numero_economico AS numEcoSalida FROM contenedor INNER JOIN entrada_almacen ON entrada_almacen.idcontenedorfk = contenedor.idcontenedorpk LEFT JOIN salida_almacen ON salida_almacen.entrada_almacenfk = entrada_almacen.entrada_almacenpk ORDER BY entrada_almacenpk DESC");

            if (!$sql->execute()) {
                $this->error = 'error';
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            $arrayContenedores = $sql->fetchAll(PDO::FETCH_ASSOC);
            $this->ViewHTMLListaContenedor->ViewHTMLListaContenedorEntradaSalida($arrayContenedores);
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => $this->error, 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
        }
    }
}
