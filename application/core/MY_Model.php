<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
Extiende CI_model para hacer accesibles los metodos CRUD a todos los modelos
*/

class MY_Model extends CI_Model 
{
    public $table_CRUD;
    public $table_read;
    public $primary_id;
    public $primary_order_by;

	function __construct()
	{
		parent::__construct();

	}

    // estaria bueno
    public function get($attr = NULL) 
    {
        // defualts
        $output_unique  = FALSE;
        // verificar los atrubutos pasados y override defaults si hace falta
            // condiciones
        if(is_numeric($attr))  // si paso solo un id, defino el cond
        {
            $cond       = $attr;
            $output_unique  = TRUE;
        }   
        else
            $cond       = !empty($attr['cond']) ? $attr['cond'] : NULL;
            // definir si las condiciones son AND o OR
        $orwhere        = !empty($attr['orwhere']) ? $attr['orwhere'] : MODEL_DEFAULT_ORWHERE;
            // definir el campo por el cual ordener
        $order_by       = !empty($attr['order_by']) ? $attr['order_by'] : $this->primary_order_by;
            // numero de resultados para mostrar
        $results        = !empty($attr['results']) ? $attr['results'] : MODEL_DEFAULT_RESULTS_PER_PAGE;
            // mostrar a partir de que elemento, para paginacion
        $limit_offset   = !empty($attr['limit_offset']) ? $attr['limit_offset'] : 0;
            // select
        $select         = !empty($attr['select']) ? is_array($attr['select']) ? implode(',', $attr['select']) : $attr['select'] : NULL;
            // definir como se devuelven los datos
        $output         = !empty($attr['output']) ? $attr['output'] : MODEL_DEFAULT_OUTPUT;

        if ($cond)
        {   
            if (!is_array($cond)) 
            {
                // pregunto si es un array para ver si hay varias ids para buscar o varios criterios, si no es asumo que es un id
                if(is_numeric($cond))
                {
                    $this->db->where($this->primary_id, (int)$cond);
                    $output_unique = TRUE;
                }
            }
            else 
            {   
                if (isset($cond[0])) 
                {
                    // si el primer elemento del array es 0 asumo que no es multidemsional y estoy buscando ids
                   $this->db->where_in($this->primary_id, $cond);
                }
                else
                {
                    // si no, el primer elemento representa la busqueda por campo y hago el loop
                    foreach ($cond as $campo => $valor) 
                    {
                        if (is_array($valor)) 
                        {
                            // si valor es un array estoy buscando multiples valores para ese campo
                            // si orwhere es true la opcione se adjunta con OR, si no con AND
                            $metodo = $orwhere ? 'or_where_in' : 'where_in';
                            $this->db->$metodo($campo, $valor);
                        }
                        else
                        {
                            // si valor es un no es array estoy buscando un solo valor para ese campo
                            // si orwhere es true la opcione se adjunta con OR, si no con AND
                            $metodo = $orwhere ? 'or_where' : 'where';
                            $this->db->$metodo($campo, $valor);
                        }
                    }
                }
            }
        }

        // no mostrar registros marcados como borrados
        $active["borrado IS NULL"] = NULL;
        $this->db->where($active);

        // select
        if ($select)
            $this->db->select($select);

        // definir orderby
        $this->db->order_by($order_by);

        // definir los limites del query
        $this->db->limit($results, $limit_offset);

        $result = $this->db->get($this->table_read);
        
        // Return results
        if($result)
        {
            $result_array = $result->result_array();
            return $this->switch_output($result_array, $output, $output_unique);
        }
        else
        {
            return FALSE;
        }
        
    }

    public function get_like($cond = NULL)
    {
        if ($cond === NULL OR empty($cond) OR !is_array($cond) OR isset($cond[0])) return FALSE;

        $this->db->like($cond);
        $this->db->order_by(key($cond), 'ASC');

        $result_array = $this->db->get($this->table_read)->result_array();

        return $result_array;
    }

	// update / insert un registro por id o multiples ids
	public function save($data, $id = FALSE) 
	{
        if ($id === FALSE) 
        {    
            // se creae el registro cuando no se define un id
            if (!isAssoc($data)) 
            {
                // si es un array de arrays hacer el batch
                // agregar capo creado
                foreach ($data as $key => $value) 
                {
                    $data[$key]['creado'] = date(SYS_DATETIMEFULL_FORMAT);
                    $data[$key]['autor'] = $_SESSION['user']['admin_id'];
                }

                // ejecutar elcoando insert
                $this->db->insert_batch($this->table_CRUD, $data);
                
                // cargar los ids creados para devolverlos
                $insert_id=(int)$this->db->insert_id();
                for ($i = $insert_id; $i < count($data)+$insert_id; $i++) 
                { 
                    $return_id[] = $i;
                }
            }
            else
            {
                $data['creado'] = date(SYS_DATETIMEFULL_FORMAT);
                $data['autor'] = $_SESSION['user']['admin_id'];
                $this->db->set($data)->insert($this->table_CRUD);
                $return_id = $this->db->insert_id();
            }
        }
        else 
        {   
            // definiendo un id se actualiza
            // en el caso de que $id no sea un array forzarlo para que ande para los dos casos
            $ids = !is_array($id) ? array($id) : $id;
            foreach ($ids as $id) 
            {
	            if ($id) 
	            {
	                $this->db->set($data)->where($this->primary_id, $id)->update($this->table_CRUD);
                    $return_id[] = $id;
	            }
	        }

        }
        
        // Return the ID, si hay un solo resultado devolver el id, si no el array con los ids
        return $return_id;
    }

	// borrar un registro por id o multiples ids
	public function delete($ids)
	{    
        $filter = $this->primaryFilter; 
        $ids = ! is_array($ids) ? array($ids) : $ids;
        $data['borrado'] = date(SYS_DATETIMEFULL_FORMAT);
        foreach ($ids as $id) 
        {
            $id = $filter($id);
            if ($id) 
            {
                $this->db->where($this->primary_id, $id)->set($data)->update($this->table_CRUD);
            }
        }
    }

    /// agregar un creiterio al cuando no se borra por id
    public function delete_by($key = FALSE, $value = FALSE)
    {
        $data['borrado'] = date(SYS_DATETIMEFULL_FORMAT);

        if ($key === FALSE OR $value = FALSE) 
            return FALSE;
        
        $this->db->where(htmlentities($key), htmlentities($value))->set($data)->update($this->table_CRUD);
    }

    // helpers
    private function switch_output ($array, $output, $unique = FALSE)
    {
        // si hay un solo valor y defino $unique TRUE tomo un solo valor de autput
        if ($unique === TRUE) $array = $array[0];

        // proceso la respuesta
        switch (strtolower($output)) {
            case 'json':
                return json_encode($array);
                break;
            case 'result_array':
            default:
                return $array;
                break;
        }
    }
}
?>