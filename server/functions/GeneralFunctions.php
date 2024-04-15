<?
class GeneralFunctions
{

    // --- Función para validar números enteros.
    public function validarNumeroEntero($numero)
    {

        if (preg_match('/^[0-9]+$/', $numero)) {
            return true;
        }

        return false;
    }

    // Función para validar número de contenedor.
    public function validarNumeroContenedor($numeroContenedor)
    {
        if (preg_match('/([a-zA-Z]{3})([UJZujz])(\d{6})(\d)/', $numeroContenedor)) {
            return true;
        }

        return false;
    }

    // --- Función para validar que las fechas tengan un formato válido.
    public function validarFecha($fecha, $formato = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($formato, $fecha);
        return $d && $d->format($formato) == $fecha;
    }
    // 
}
