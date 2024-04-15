<?

// ---- Conexión a BD.
include_once 'conexion_db/Conexion.php';

class ModelGuardarContenedor
{

    private $tipoError;
    private $bd;

    function __construct()
    {
        $Conexion = new Conexion();
        $this->bd = $Conexion->BD();
        $this->tipoError = "server";
    }

    // ---- Guardar contenedor
    public function guardarContenedor($numeroContenedor, $tamanioContenedor)
    {
        try {

            // Activando la transacción, se desactiva el modo autocommit.
            $this->bd->beginTransaction();

            $sql = $this->bd->prepare("CALL registrarContenedor(:numero_contenedor, :tamanio_contenedor)");

            $sql->bindParam(':numero_contenedor', $numeroContenedor, PDO::PARAM_STR);
            $sql->bindParam(':tamanio_contenedor', $tamanioContenedor, PDO::PARAM_STR);

            if (!$sql->execute()) {
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            // Se aplican los cambios.
            $this->bd->commit();
            return true;
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => 'error', 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
            // Se deshacen los cambios.
            $this->bd->rollBack();
        }
    }

    // ---- Modificar contenedor
    public function modificarContenedor($numeroContenedor, $tamanioContenedor, $idContenedorConsulta)
    {
        try {

            // Activando la transacción, se desactiva el modo autocommit.
            $this->bd->beginTransaction();

            $sql = $this->bd->prepare("CALL modificarContenedor(:numero_contenedor, :tamanio_contenedor, :idcontenedorpk)");

            $sql->bindParam(':numero_contenedor', $numeroContenedor, PDO::PARAM_STR);
            $sql->bindParam(':tamanio_contenedor', $tamanioContenedor, PDO::PARAM_STR);
            $sql->bindParam(':idcontenedorpk', $idContenedorConsulta, PDO::PARAM_INT);

            if (!$sql->execute()) {
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            // Se aplican los cambios.
            $this->bd->commit();
            return true;
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => 'error', 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
            // Se deshacen los cambios.
            $this->bd->rollBack();
        }
    }

    // --- Registrar entrada contenedor.
    public function registrarEntradaContenedor($idContenedorConsulta, $numeroEconomico, $fechaEntrada)
    {
        try {

            // Activando la transacción, se desactiva el modo autocommit.
            $this->bd->beginTransaction();

            $fechaHoraActual = date("Y-m-d H:i");
            $flujoEntrada = "S";

            $sql = $this->bd->prepare("CALL registrarEntradaContenedor(:idcontenedorfk, :fecha_entrada, :flujo_entrada, :numero_economico)");

            $sql->bindParam(':idcontenedorfk', $idContenedorConsulta, PDO::PARAM_INT);
            $sql->bindParam(':fecha_entrada', $fechaEntrada, PDO::PARAM_STR);
            $sql->bindParam(':flujo_entrada', $flujoEntrada, PDO::PARAM_STR);
            $sql->bindParam(':numero_economico', $numeroEconomico, PDO::PARAM_STR);

            if (!$sql->execute()) {
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            // Se aplican los cambios.
            $this->bd->commit();
            return true;
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => 'error', 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
            // Se deshacen los cambios.
            $this->bd->rollBack();
            return false;
        }
    }

    // --- Registrar entrada contenedor.
    public function registrarSalidaContenedor($idContenedorConsulta, $idEntradaAlmacen, $numeroEconomico, $fechaSalida)
    {
        try {

            // Activando la transacción, se desactiva el modo autocommit.
            $this->bd->beginTransaction();

            $fechaHoraActual = date("Y-m-d H:i");
            $flujoSalida = "S";

            $query = "CALL registrarSalidaContenedor(:entrada_almacenfk, :fecha_salida, :flujo_salida, :numero_economico)";
            $sql = $this->bd->prepare($query);

            $sql->bindParam(':entrada_almacenfk', $idEntradaAlmacen, PDO::PARAM_INT);
            $sql->bindParam(':fecha_salida', $fechaSalida, PDO::PARAM_STR);
            $sql->bindParam(':flujo_salida', $flujoSalida, PDO::PARAM_STR);
            $sql->bindParam(':numero_economico', $numeroEconomico, PDO::PARAM_STR);

            if (!$sql->execute()) {
                throw new PDOException('Error interno del servidor, si el problema persiste contacte al administrador del sistema.');
            }

            // Se aplican los cambios.
            $this->bd->commit();
            return true;
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => 'error', 'tipoError' => $this->tipoError, 'mensaje' => $e->getMessage())));
            // Se deshacen los cambios.
            $this->bd->rollBack();
            return false;
        }
    }
}
