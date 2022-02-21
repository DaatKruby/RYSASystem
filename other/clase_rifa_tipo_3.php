<?php
class Rifa_Tipo_3 implements iRifa
{

    private $id_rifa;
    private $numero_rifa;
    private $grupos_boleto;
    private $grid_cant_seleccion_str;
    private $is_disponible;
    private $whatsapp_number;
    private $status_img_url;
    private $url_img_payment;
    private $form_configs;
    private $grid_cant_seleccion;
    private $randoms_por_numero;
    private $comienzo_numeros_reservados;
    private $tipo_rifa;
    private $cant_boletos_regalo;

    function __construct($id_rifa, $numero_rifa, $is_disponible, $comienzo_numeros_reservados)
    {
        $this->tipo_rifa = 3;
        $this->id_rifa = $id_rifa;
        $this->numero_rifa = $numero_rifa;
        $this->is_disponible = $is_disponible;
        $this->comienzo_numeros_reservados = $comienzo_numeros_reservados;

        //DEFAULT
        $this->status_img_url = "https://i.ibb.co/MZ0rZ9r/image0.jpg";
        $this->url_img_payment = "https://via.placeholder.com/1000x1600";
        $this->whatsapp_number = "7778888999";
        $this->grupos_boleto = array(
            new Grupo_Boleto(2, 500),
            new Grupo_Boleto(4, 1000),
        );
        $this->form_configs = array(
            new Form_Config(1, 1, 300),
            new Form_Config(2, 1, 1000),
        );
        $grid_cant_seleccion = array(
            1,
            2
        );
        $this->randoms_por_numero = array(
            new Random_por_Numero(1, 1),
            new Random_por_Numero(2, 2)
        );
        $this->cant_boletos_regalo = 1;

        $this->set_grid_cant_seleccion($grid_cant_seleccion);
    }

    function get_grid_cant_seleccion()
    {
        return $this->grid_cant_seleccion;
    }

    function set_grid_cant_seleccion($grid_cant_seleccion)
    {
        $this->grid_cant_seleccion = $grid_cant_seleccion;
        $grid_cant_seleccion_str_aux = "";
        $grid_cant_seleccion_size = sizeof($this->grid_cant_seleccion);

        foreach ($grid_cant_seleccion as $key => $cantidad) {
            $grid_cant_seleccion_str_aux = $grid_cant_seleccion_str_aux . strval($cantidad);
            if ($key !== $grid_cant_seleccion_size - 1) {
                $grid_cant_seleccion_str_aux = $grid_cant_seleccion_str_aux . ",";
            }
        }
        $this->grid_cant_seleccion_str = $grid_cant_seleccion_str_aux;
    }

    function get_grupo_boleto($cantidad_boletos)
    {
        $conjunto = null;
        $conjuntos_length = sizeof($this->grupos_boleto);
        for ($i = 0; $i < $conjuntos_length; $i++) {
            if ($this->grupos_boleto[$i]->cantidad === $cantidad_boletos) {
                $conjunto = $this->grupos_boleto[$i];
                break;
            }
        }

        return $conjunto;
    }

    function validar_boletos_antes_compra($boletos, $id_rifa)
    {
        global $wpdb;
        $result = $wpdb->get_row("select cant_boletos from tbl_rifa where id_rifa=$id_rifa");
        $cant_boletos_rifa = ($result->cant_boletos);

        $count_boletos = count($boletos);
        $continue = false;
        $grupos_boleto = $this->grupos_boleto;

        /*Checar que todos los boletos esten en el rango de boletos que maneja la rifa*/
        foreach ($boletos as $boleto) {
            if ($boleto < 0 || $boleto > $cant_boletos_rifa) {
                throw new Exception('Boleto fuera del rango permitido');
            }
        }

        /*Checar que la cantidad exista en un grupo de boletos*/
        foreach ($grupos_boleto as $grupo) {
            if ($count_boletos == $grupo->cantidad) {
                $continue = true;
            }
        }

        if (!$continue) {
            throw new Exception('Cantidad de boletos no existe en ningun grupo');
        }

        /*Checar que haya relacion entre cant regalados y seleccionados*/
        $boletos_de_regalo = [];
        $boletos_seleccionados = [];
        foreach ($boletos as $key => $boleto) {
            if ($boleto >= $this->comienzo_numeros_reservados) {
                $boletos_de_regalo [] = $boleto;
            } else {
                $boletos_seleccionados [] = $boleto;
            }
        }
        if (count($boletos_de_regalo) != (count($boletos_seleccionados) * $this->cant_boletos_regalo)) {
            throw new Exception('Cantidad de numeros seleccionados y de regalos irregular');
        }

        /*Checar que cada boleto tenga sus hermanos*/
        $cant_grupos_hermanos_correcta = $count_boletos / ($this->cant_boletos_regalo + 1);
        $cant_grupos_hermanos=0;
        for ($i=0; $i < count($boletos_seleccionados); $i++) { 
            $cant_hermanos_aux = 0;
            $boletoMasCantidad = $boletos_seleccionados[$i] + $this->comienzo_numeros_reservados;

            for ($h=0; $h < $this->cant_boletos_regalo; $h++) {

                for ($j=0; $j < count($boletos_de_regalo); $j++) {
                    if ($boletoMasCantidad == $boletos_de_regalo[$j]){
                        $cant_hermanos_aux=$cant_hermanos_aux + 1;
                    }
                }
                $boletoMasCantidad = $boletoMasCantidad + $this->comienzo_numeros_reservados;   
            }
            if ($cant_hermanos_aux == $this->cant_boletos_regalo) {
                $cant_grupos_hermanos = $cant_grupos_hermanos + 1;
            }

        }
        if ($cant_grupos_hermanos!=$cant_grupos_hermanos_correcta) {
            throw new Exception('Numeros hermanos incorrectos');
        }
    }

    function apartar_boletos($boletos, $info_folio)
    {
        global $wpdb;

        try {
            $wpdb->query("start transaction;");

            $this->validar_boletos_antes_compra($boletos, $this->id_rifa);

            $result = $wpdb->get_row("call sp_obtener_folio_unico ($this->id_rifa);");
            $folio = $result->result;
            if (is_bool($result) && $result == false) {
                throw new Exception('Error Apartar Boleto - Error al obtener folio unico');
            }

            $result = $wpdb->get_row("select now() as 'date';");
            $fecha = $result->date;

            $nombres = $info_folio[0];
            $apellidos = $info_folio[1];
            $celular = $info_folio[2];
            $estado = $info_folio[3];
            $correo = $info_folio[4];
            
            $query = $wpdb->prepare("call sp_insert_folio (%d, %d, %s, %s, %s, %s, %s);", [$folio, $this->id_rifa, $nombres, $apellidos, $celular, $estado, $correo]);
            $result = $wpdb->query($query);
            if (is_bool($result) && $result == false) {
                throw new Exception('Error Apartar Boleto - Error al insertar folio');
            }

            $query = $wpdb->prepare("update tbl_metadata_folio set ultimo_valor_folio= %d where id_rifa= %d", [$folio, $this->id_rifa]);
            $result = $wpdb->query($query);
            if (is_bool($result) && $result == false) {
                throw new Exception('Error Apartar Boleto - Error al modificar metadata del folio');
            }

            foreach ($boletos as $boleto) {
                $query = $wpdb->prepare("call sp_apartar_boleto (%d, %d, %d, %s);", [$this->id_rifa, $folio, $boleto, $fecha]);
                $result = $wpdb->query($query);
                if (is_bool($result) && $result == false) {
                    throw new Exception('Error Apartar Boleto - Error al apartar');
                }
            }

            $wpdb->query("commit;");
            return $folio;
        } catch (\Throwable $e) {
            $wpdb->query("rollback;");
            echo $e;
        }
        return null;
    }

    function obtener_boletos_de_regalo($numeros_seleccionados)
    {
        $numeros_regalo = [];
        foreach ($numeros_seleccionados as $numero_seleccionado) {
            $aux = $numero_seleccionado;
            for ($i=0; $i < $this->cant_boletos_regalo; $i++) { 
                $aux = $aux +$this->comienzo_numeros_reservados;
                $numeros_regalo[] = $aux;   
            }
        }
        return $numeros_regalo;
    }

    function existen_suficientes_Boletos_de_regalo($cant_boletos_seleccionados)
    {
        return true;
    }

    function get_id_rifa()
    {
        return $this->id_rifa;
    }
    function get_numero_rifa()
    {
        return $this->numero_rifa;
    }
    function get_grupos_boleto()
    {
        return $this->grupos_boleto;
    }
    function set_grupos_boleto($grupos_boleto)
    {
        $this->grupos_boleto = $grupos_boleto;
    }
    function get_max_cantidad_boletos()
    {
        return $this->max_cantidad_boletos;
    }
    function get_is_disponible()
    {
        return $this->is_disponible;
    }
    function get_whatsapp_number()
    {
        return $this->whatsapp_number;
    }
    function set_whatsapp_number($nuevo_numero)
    {
        $this->whatsapp_number = $nuevo_numero;
    }
    function get_status_img_url()
    {
        return $this->status_img_url;
    }
    function set_status_img_url($new_url)
    {
        $this->status_img_url = $new_url;
    }
    function get_url_img_payment()
    {
        return $this->url_img_payment;
    }
    function set_url_img_payment($new_url)
    {
        $this->url_img_payment = $new_url;
    }
    function get_comienzo_numeros_reservados()
    {
        return $this->comienzo_numeros_reservados;
    }
    function set_randoms_por_numero($randoms_por_numero)
    {
        $this->randoms_por_numero = $randoms_por_numero;
    }
    function get_randoms_por_numero()
    {
        return $this->randoms_por_numero;
    }

    //especiales
    function get_precio_de_grupo($cantidad)
    {
        $grupos_boleto = $this->grupos_boleto;
        foreach ($grupos_boleto as $key => $grupo) {
            if ($grupo->cantidad == $cantidad) {
                return $grupo->precio;
            }
        }
        return null;
    }
    function get_randoms_a_agregar($cantidad)
    {
        foreach ($this->randoms_por_numero as $key => $element) {
            if ($element->cantidad == $cantidad) {
                return $element->nuevos_random;
            }
        }
        return null;
    }
    function get_grid_cant_seleccion_str()
    {
        return $this->grid_cant_seleccion_str;
    }
    function set_form_configs($form_configs)
    {
        $this->form_configs = $form_configs;
    }
    function get_form_configs()
    {
        return $this->form_configs;
    }
    function get_tipo_rifa()
    {
        return $this->tipo_rifa;
    }
    function set_cant_boletos_regalo_tipo_3($cant_boletos) {
        $this->cant_boletos_regalo = $cant_boletos;
    }
}
