<?php

interface iRifa
{
    public function get_grupo_boleto($cantidad_boletos);
    public function validar_boletos_antes_compra($boletos, $id_rifa);
    public function apartar_boletos($boletos, $info_folio);
    public function obtener_boletos_de_regalo($numeros_seleccionados);
    public function existen_suficientes_Boletos_de_regalo($cant_boletos_seleccionados);

    //only getters
    public function get_id_rifa();
    public function get_numero_rifa();
    public function get_is_disponible();
    public function get_comienzo_numeros_reservados();
    public function get_tipo_rifa();

    //getters y setters

    public function get_grupos_boleto();
    public function set_grupos_boleto($grupos_boleto);

    public function get_whatsapp_number();
    public function set_whatsapp_number($nuevo_numero);

    public function get_status_img_url();
    public function set_status_img_url($new_url);

    public function get_url_img_payment();
    public function set_url_img_payment($new_url);

    public function get_grid_cant_seleccion();
    public function set_grid_cant_seleccion($grid_cant_seleccion);
    public function get_grid_cant_seleccion_str();

    public function set_form_configs($form_configs);
    public function get_form_configs();

    public function set_randoms_por_numero($randoms_por_numero);
    public function get_randoms_por_numero();
    public function get_randoms_a_agregar($cantidad);

    public function set_cant_boletos_regalo_tipo_3($cant_boletos);

    //especiales
    public function get_precio_de_grupo($cantidad);
}

class Grupo_Boleto
{

    public $cantidad;
    public $precio;

    function __construct($cantidad, $precio)
    {
        $this->cantidad = $cantidad;
        $this->precio = $precio;
    }
}

class Form_Config
{

    public $cantidad;
    public $tipo;
    public $price;

    function __construct($cantidad, $tipo, $price)
    {
        $this->cantidad = $cantidad;
        $this->tipo = $tipo;
        $this->price = $price;
    }
}

class Random_por_Numero
{

    public $cantidad;
    public $nuevos_random;

    function __construct($cantidad, $nuevos_random)
    {
        $this->cantidad = $cantidad;
        $this->nuevos_random = $nuevos_random;
    }
}

class Rifas
{

    public $rifas;

    function __construct($rifas)
    {
        $this->rifas = $rifas;
    }

    function get_rifa($id)
    {
        $rifa = null;

        $rifas_length = sizeof($this->rifas);
        for ($i = 0; $i < $rifas_length; $i++) {
            if ($this->rifas[$i]->get_id_rifa() === $id) {
                $rifa = $this->rifas[$i];
            }
        }

        return $rifa;
    }

    function get_first_rifa()
    {
        if (sizeof($this->rifas) != 0) {
            return $this->rifas[0];
        } else {
            return null;
        }
    }

    function get_first_rifa_activa()
    {
        $rifas_length = sizeof($this->rifas);
        for ($i = 0; $i < $rifas_length; $i++) {
            if ($this->rifas[$i]->get_is_disponible()) {
                return $this->rifas[$i];
            }
        }
        return null;
    }

    function get_cant_rifas_activas()
    {
        $cant = 0;
        $rifas_length = sizeof($this->rifas);
        for ($i = 0; $i < $rifas_length; $i++) {
            if ($this->rifas[$i]->get_is_disponible()) {
                $cant = $cant + 1;
            }
        }
        return $cant;
    }
}
