<?

class ViewHTMLListaContenedor
{

    public function ViewHTMLListaContenedor($arrayContenedores)
    {
        // var_dump($arrayContenedores[0]["idcontenedorpk"]);
        // return;
        if (count($arrayContenedores) == 0) {
?>
            <tr>
                <td colspan="6">
                    <span class="badge bg-info text-white w-100 p-3">
                        <h5 class="mb-0">
                            <i class="fa fa-info circle-information mx-auto"></i>
                            ¡No hay contenedores registrados para mostrar!
                        </h5>
                    </span>
                </td>
            </tr>
            <?
        } else {
            foreach ($arrayContenedores as $contenedor) {

                $idContenedor = $contenedor["idcontenedorpk"];
                $numeroContenedor = $contenedor["numero_contenedor"];
                $tamanioContenedor = $contenedor["tamanio_contenedor"];

            ?>
                <tr>
                    <td><?= $idContenedor ?></td>
                    <td><?= $numeroContenedor ?></td>
                    <td><?= $tamanioContenedor ?></td>
                    <td>
                        <button class="btn btn-success btnNumeroEconomico" data-bs-toggle="modal" data-bs-target="#numeroEconomico" title="Registrar entrada" data-numero-contenedor="<?=$numeroContenedor?>" data-id-contenedor="<?= $idContenedor ?>">
                            <i class="fa fa-truck" aria-hidden="true"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-primary btnEditarContenedor" title="Editar contenedor" data-id-contenedor="<?= $idContenedor ?>">
                            <i class="fa fa-edit"></i>
                        </button>
                    </td>
                    <td>
                        <button class="btn btn-danger btnEliminarContenedor" title="Eliminar contenedor" data-id-contenedor="<?= $idContenedor ?>">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?
            }
        }
    }

    // --- Estado entrada y salidas de contenedores.
    public function ViewHTMLListaContenedorEntradaSalida($arrayContenedores)
    {
        // var_dump($arrayContenedores[0]["idcontenedorpk"]);
        // return;
        if (count($arrayContenedores) == 0) {
            ?>
            <tr>
                <td colspan="10">
                    <span class="badge bg-info text-white w-100 p-3">
                        <h5 class="mb-0">
                            <i class="fa fa-info circle-information mx-auto"></i>
                            ¡No hay salidas/entradas de contenedores para mostrar!
                        </h5>
                    </span>
                </td>
            </tr>
            <?
        } else {
            foreach ($arrayContenedores as $contenedor) {

                $idContenedor = $contenedor["idcontenedorpk"];
                $idEntradaAlmacen = $contenedor["entrada_almacenpk"];
                $numeroContenedor = $contenedor["numero_contenedor"];
                $tamanioContenedor = $contenedor["tamanio_contenedor"];
                $flujoEntrada = $contenedor["flujo_entrada"];
                $fechaEntrada = date("d-m-Y", strtotime($contenedor["fecha_entrada"]));
                $numEcoEntrada = $contenedor["numEcoEntrada"];
                $flujoSalida = $contenedor["flujo_salida"];
                $fechaSalida = $contenedor["fecha_salida"];
                $numEcoSalida = $contenedor["numEcoSalida"];

                $flujoEntrada = $flujoEntrada == "S" ? "Si" : "---";
                $flujoSalida = $flujoSalida == "S" ? "Si" : "No";
                $fechaSalida = !empty($fechaSalida) ? date("d-m-Y", strtotime($fechaSalida)) : "---";
                $numEcoSalida = empty($numEcoSalida) ? "---" : $numEcoSalida;

            ?>
                <tr>
                    <td><?= $idEntradaAlmacen ?></td>
                    <td><?= $numeroContenedor ?></td>
                    <td><?= $tamanioContenedor ?></td>
                    <td><?= $flujoEntrada ?></td>
                    <td><?= $numEcoEntrada ?></td>
                    <td><?= $fechaEntrada ?></td>
                    <td><?= $flujoSalida ?></td>
                    <td><?= $numEcoSalida ?></td>
                    <td><?= $fechaSalida ?></td>
                    <td>
                        <button class="btn btn-success btnNumeroEconomico" data-bs-toggle="modal" data-bs-target="#numeroEconomico" title="Registrar salida" data-numero-contenedor="<?=$numeroContenedor?>" data-id-contenedor="<?= $idContenedor ?>" data-id-entrada-almacen="<?= $idEntradaAlmacen ?>">
                            <i class="fa fa-truck" aria-hidden="true"></i>
                        </button>
                    </td>
                </tr>
<?
            }
        }
    }
}
