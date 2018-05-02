<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

/*
Extiende CI_model para hacer accesibles los metodos CRUD a todos los modelos
*/

class MY_Model extends CI_Model 
{
    public $table_CRUD;
    public $table_read;
    public $primary_id; // el campo que se toma como id unico
    public $primary_field; // el campo que se tomo como descriptivo del cada registro, para forms
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
            $cond           = $attr;
            $output_unique  = TRUE;
            // definir si las condiciones son AND o OR
            $orwhere        = MODEL_DEFAULT_ORWHERE;
            // definir el campo por el cual ordener
            $order_by       = $this->primary_order_by;
            // numero de resultados para mostrar
            $results        = MODEL_DEFAULT_RESULTS_PER_PAGE;
            // limit offset
            $limit_offset   = 0;
            // select
            $select         = NULL;
            // definir como se devuelven los datos
            $output         = MODEL_DEFAULT_OUTPUT;
            // group_by
            $group_by         = !empty($attr['group_by']) ? $attr['group_by'] : FALSE;
        }   
        else
        {
            $cond       = !empty($attr['cond']) ? $attr['cond'] : NULL;
            // definir si las condiciones son AND o OR
            $orwhere        = !empty($attr['orwhere']) ? $attr['orwhere'] : MODEL_DEFAULT_ORWHERE;
            // definir el campo por el cual ordener
            $order_by       = !empty($attr['order_by']) ? $attr['order_by'] : $this->primary_order_by;
            // numero de resultados para mostrar
            $results        = !empty($attr['results']) ? $attr['results'] : MODEL_DEFAULT_RESULTS_PER_PAGE;
            if (!empty($attr['page']))
            {
                // si defino page $page definir el offset en base a ese valor
                // mostrar a partir de que elemento, para paginacion
                $limit_offset   = $results * $attr['page'] - $results;
            }
            else
            {
                // si defino offset lo defino con esa variable
                // mostrar a partir de que elemento, para paginacion
                $limit_offset   = !empty($attr['limit_offset']) ? $attr['limit_offset'] : 0;
            }
            // select
            $select         = !empty($attr['select']) ? is_array($attr['select']) ? implode(',', $attr['select']) : $attr['select'] : NULL;
            // group_by
            $group_by         = !empty($attr['group_by']) ? $attr['group_by'] : FALSE;
            // definir como se devuelven los datos
            $output         = !empty($attr['output']) ? $attr['output'] : MODEL_DEFAULT_OUTPUT;
        }

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
                            // pregunto si uso el operador ~ para definir que busco un like
                            if(preg_match('/\s~$/',$campo))
                            {
                                $eval = 'like';
                                $campo = preg_replace('/\s~$/','',$campo);
                            }
                            else
                            {
                                $eval = 'where';
                            }
                            // si valor es un no es array estoy buscando un solo valor para ese campo
                            // si orwhere es true la opcione se adjunta con OR, si no con AND
                            $metodo = $orwhere ? 'or_'.$eval : $eval;
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
        $this->db->select('SQL_CALC_FOUND_ROWS null as total_items ',FALSE);

        if ($select)
        {
            $this->db->select($select);
        }
        else
        {
            $this->db->select($this->table_read.'.*');
        }


        // definir orderby
        $this->db->order_by($order_by);

        // definir group_by
        if($group_by)
        {
            $this->db->group_by($group_by);
        }

        // definir los limites del query
        $this->db->limit($results, $limit_offset);

        $result = $this->db->get($this->table_read);
        
        $paginacion = $this->db->query('SELECT FOUND_ROWS() total_items')->result_array();

        
        if ($result AND $result->num_rows() > 0)
        {
            $result = $result->result_array();
            foreach ($result as $key => $value) 
            {
                $result[$key]['total_results'] = $paginacion[0]['total_items'];
            }
            return $result;
        }
        
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
        // definir el id del admin, para ver si es web o admin
        $admin_id = isset($_SESSION['user']['admin_id']) AND $_SESSION['user']['admin_id'] == 0 ? 3 : $_SESSION['user']['admin_id'];
        
        if ($id === FALSE) 
        {    
            // se creae el registro cuando no se define un id
            if (!$this->isAssoc($data)) 
            {
                // si es un array de arrays hacer el batch
                // agregar campo creado y autor
                foreach ($data as $key => $value) 
                {
                    $data[$key]['creado'] = date(SYS_DATETIMEFULL_FORMAT);
                    $data[$key]['autor_admin_id'] = $admin_id;
                }

                // ejecutar el insert
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
                $data['autor_admin_id'] = $admin_id;
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
	public function delete($ids = NULL)
	{   
        if($ids === NULL) return FALSE;

        $data['borrado'] = date(SYS_DATETIMEFULL_FORMAT);
        
        // si paso un solo id o un array de ids
        if(is_numeric($ids) OR isset($ids[0]))
        {
            $where[$this->primary_id] = $ids;
        }
        elseif(is_array($ids))
        {
            // si paso un array aosiciativo para definir el borrado en funcion de otros campos y valores
            foreach ($ids as $field => $value)
            {
                $where[$field] = $value;
            }
        }
        return $this->db->where($where)->set($data)->update($this->table_CRUD);
    }

    /// agregar un creiterio al cuando no se borra por id
    public function delete_by($key = FALSE, $value = FALSE)
    {
        $data['borrado'] = date(SYS_DATETIMEFULL_FORMAT);

        if ($key === FALSE OR $value = FALSE) 
            return FALSE;
        
        $this->db->where(htmlentities($key), htmlentities($value))->set($data)->update($this->table_CRUD);
    }

    // tomar datos apra inputs key primery_key => primery_text
    public function get_for_input($attr = array())
    {
        // tomar los atributos de getforinput y sacarlos del array que paso al modelo get
            // el caso de inputgroup que espera un campo
        if (isset($attr['inputgroup']) AND is_string($attr['inputgroup']))
        {
            $inputgroup = (isset($attr['inputgroup']) AND is_string($attr['inputgroup'])) ? $attr['inputgroup'] : NULL;
            unset($attr['inputgroup']);
        }
        else
        {
            $inputgroup = FALSE;
        }
        $attr['results'] = 1000;
        $registros = $this->get($attr);
        
        if(empty($registros)) return FALSE;

        // looperar y asociar de cada registro solo el primery text con su id
        foreach ($registros as $registro) 
        {
            if($inputgroup)
                $return[$registro[$inputgroup]][$registro[$this->primary_id]] = $registro[$this->primary_field];
            else
                $return[$registro[$this->primary_id]] = $registro[$this->primary_field];
        }


        return $return;
    }

    // get min and max values for a column in de table
    /*
        returns: array($campo_min = min, $campo_max = max)
    */
    public function minmax($fields = NULL)
    {
        if($fields === NULL) return FALSE;

        // definir como se devuelven los datos
        $output         = !empty($attr['output']) ? $attr['output'] : MODEL_DEFAULT_OUTPUT;
        if(is_array($fields))
        {
            foreach($fields as $field)
            {
                $this->db->select_max($field,$field.'_max'); 
                $this->db->select_min($field,$field.'_min');
            }
        }
        else
        {
            $this->db->select_max($fields,$fields.'_max'); 
            $this->db->select_min($fields,$fields.'_min');
        }
        $result = $this->db->get($this->table_read); 

        // Return results
        if($result)
        {
            $result_array = $result->result_array();
            return $this->switch_output($result_array[0], $output);
        }
        else
        {
            return FALSE;
        }
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

    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
?>