<?
// ---- Controladores
include_once './modules/controller/ControllerGuardarContenedor.php';

class Routes
{

    public $ControllerGuardarContenedor;

    public function __construct()
    {
        $this->ControllerGuardarContenedor = new ControllerGuardarContenedor();
    }

    public function get($rute = "")
    {
        switch ($rute) {
            case 'guardarModificarContenedor':
                $this->ControllerGuardarContenedor->guardarModificarContenedor();
                break;
            case 'cargarListaContenedores':
                $this->ControllerGuardarContenedor->cargarListaContenedores();
                break;
            case 'eliminarContenedor':
                $this->ControllerGuardarContenedor->eliminarContenedor();
                break;
            case 'consultarContenedor':
                $this->ControllerGuardarContenedor->consultarContenedor();
                break;
                // ---- Entrada/Salida del Contenedor
            case 'registrarEntradaContenedor':
                $this->ControllerGuardarContenedor->registrarEntradaContenedor();
                break;
            case 'registrarSalidaContenedor':
                $this->ControllerGuardarContenedor->registrarSalidaContenedor();
                break;
            case 'cargarListaEntradaSalidaContenedor':
                $this->ControllerGuardarContenedor->cargarListaEntradaSalidaContenedor();
                break;
            default:
                var_dump("404 Not Found.");
                break;
        }
    }
}


$Routes = new Routes();
$Routes->get(trim($_POST["rute"]));
