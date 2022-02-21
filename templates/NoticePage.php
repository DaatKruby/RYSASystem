<?php
/*
    *Template Name: Notice Page
*/
get_header();
global $img_folder;
global $page_url;
$titulo = "Ah ocurrido un error";
$mensaje = "Ah ocurrido un error, vuelve a intentarlo o contactese con el administrador";

if (isset($_GET["titulo"]) && isset($_GET["mensaje"])){
    $titulo = $_GET["titulo"];
    $mensaje = $_GET["mensaje"];
}
?>

<div class="container" style="height: 65vh!important;">
    <div class="h-100 row d-flex justify-content-center align-items-center">
        <div class="col-10 col-md-6 text-center">
            <img class="mb-3" width="30%" src="<?php echo $img_folder.'/warning-icon.png' ?>" />
            <h1 class="font-weight-bold mb-3"><?php echo $titulo ?></h1>
            <p class="h6"><?php echo $mensaje ?></p>
            <hr/>
            <div class="d-flex mt-3 justify-content-center">
                <a href="<?php echo $page_url ?>">
                    <button>Pagina Principal</button>
                </a>
                <button class="ml-3" onclick="regresar()">Regresar</button>
            </div>
        </div>
    </div>
</div>

<script>
    function regresar () {
        window.history.back();
    }
</script>