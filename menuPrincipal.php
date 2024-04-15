<?
$arrayServer = explode("/", $_SERVER["PHP_SELF"]);
$longitudArray = count($arrayServer) - 1;
$moduloAccesado = $arrayServer[$longitudArray];
// --- Hover opciones
$hoverModuloContenedoresRegistrados = $moduloAccesado == "registroContenedor.php" ? " menuOpcionHover" : "";
$hoverModuloEntradaSalidaAlmacenContenedor = $moduloAccesado == "registroEntradaSalidaAlmacenContenedor.php" ? " menuOpcionHover" : "";
?>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center my-5" href="registroContenedor.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa fa-id-container"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Entradas salidas de contenedores</div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Heading -->
    <div class="sidebar-heading">
        <h6>MÓDULOS:</h6>
    </div>
    <!-- Nav Item - Menu -->
    <li class="nav-item <?= $hoverModuloContenedoresRegistrados ?>">
        <a class="nav-link w-100" href="./registroContenedor.php">
            <i class="fa fa-list-alt"></i>
            <span>Registro de contenedor</span>
        </a>
    </li>
    <li class="nav-item <?= $hoverModuloEntradaSalidaAlmacenContenedor ?>">
        <a class="nav-link w-100" href="./registroEntradaSalidaAlmacenContenedor.php">
            <i class="fa fa-list-alt"></i>
            <span>Entrada y salida del almacen</span>
        </a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
</ul>