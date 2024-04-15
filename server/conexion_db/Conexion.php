<?
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('America/Mexico_City');
// Unix
setlocale(LC_TIME, 'es_ES.UTF-8');
// Windows
setlocale(LC_TIME, "spanish");

class Conexion
{

    private $dotenv;

    function __construct()
    {
        $this->dotenv = Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
        $this->dotenv->load();
        // --- Se valida que no falte una variable de entorno.
        $this->dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
    }

    public function BD()
    {

        try {

            $this->dotenv = $this->dotenv->load();

            $DB_HOST = $_ENV["DB_HOST"];
            $DB_NAME = $_ENV["DB_NAME"];
            $DB_USER = $_ENV["DB_USER"];
            $DB_PASS = $_ENV["DB_PASS"];

            $db = new PDO('mysql:host=' . $DB_HOST . ';dbname=' . $DB_NAME . '', $DB_USER, $DB_PASS, array(PDO::ATTR_PERSISTENT => true));
            return $db;
            // 
        } catch (PDOException $e) {
            print_r(json_encode(array('estado' => 'error', 'tipoError' => 'server', 'mensaje' => $e->getMessage())));
            die();
        }
    }
}
