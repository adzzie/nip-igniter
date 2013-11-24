<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");

class Nip_Model extends CI_Model {
	protected $className;
	protected $tableName;
	protected $primary;

	protected $softDeletes = FALSE;
	protected $justTrash = FALSE;
	protected $timestamps = TRUE;

	protected $validator = array();
	protected $messageArray = array();
	protected $messageString;

	protected $createdField = "created";
	protected $updatedField = "updated";
	protected $deletedField = "deleted";
	
	
	public function __construct($options = array()){
		parent::__construct();
		$this->attr($options);
		$this->className = get_class($this);
	}

	public function __toString(){
		return $this->className;
	}

	public function __get($key)
	{
		$method = "get".ucfirst($key);
		if(method_exists($this, $method)){
			return $this->$method();
		}else{
			return parent::__get($key);
		}
	}
	
	public function attr($attributes = array()){
		if(is_array($attributes)){
			foreach($attributes as $key => $value){
				if(property_exists($this,$key)){
					$this->$key = $value;
				}
			}
		}
	}
	
	public function first($where = NULL, $fields = NULL){
		if($this->softDeletes){
			$this->db->where("{$this->deletedField} IS NULL");
		}else if($this->justTrash){
			$this->db->where("{$this->deletedField} IS NOT NULL");
		}
		
		if($where){
			if(is_numeric($where)){
				$this->db->where(array($this->primary => $where));
			}else{
				$this->db->where($where);
			}
		}
		if($fields){
			if(is_array($fields)){
				$this->db->select(implode(",", $fields));
			}else if(is_string($fields)){
				$this->db->select($fields);
			}
		}
		$this->db->limit(1);
		$query = $this->db->get($this->tableName);
		$data = $query->row_array();
		if(!empty($data)){
			$model = new $this->className();
			$model->attr($data);
			
			return $model;
		}
		return NULL;
	}

	public function all($where = NULL, $fields = NULL, 
				$orderBy = NULL, $limit = NULL, $offset = NULL){
	
		if($fields){
			if(is_array($fields)){
				$this->db->select(implode(",", $fields));
			}else if(is_string($fields)){
				$this->db->select($fields);
			}
		}
		
		if($this->softDeletes){
			$this->db->where("{$this->deletedField} IS NULL");
		}else if($this->justTrash){
			$this->db->where("{$this->deletedField} IS NOT NULL");
		}

		if($where){
			$this->db->where($where);
		}
		if($orderBy){
			$this->db->order_by($orderBy);
		}
		if($limit){
			if($offset){
				$this->db->limit($limit, $offset);
			}else{
				$this->db->limit($limit);
			}
			
		}
		
		$query = $this->db->get($this->tableName);

		$models = array();
		foreach($query->result_array() as $row){
			$model = new $this->className();
			$model->attr($row);
			
			array_push($models, $model);
		}
		return $models;
	}
	
	public function count($where = NULL){
		if($this->softDeletes){
			$this->db->where("{$this->deletedField} IS NULL");
		}else if($this->justTrash){
			$this->db->where("{$this->deletedField} IS NOT NULL");
		}

		if($where){
			$this->db->where($where);
		}
		return $this->db->count_all_results($this->tableName);
	}
	
	public function save(){
		if($this->{$this->primary}){
			$this->db->where(array($this->primary => $this->{$this->primary}));
			
			if($this->timestamps){
				$this->{$this->updatedField} = date("Y-m-d H:i:s");
			}

			return $this->db->update($this->tableName, $this);
		}else{
			$this->{$this->primary} = $this->getLastId();
			
			if($this->timestamps){
				$this->{$this->createdField} = date("Y-m-d H:i:s");
			}
			
			return $this->db->insert($this->tableName, $this);
		}
	}
	
	public function getLastId(){
		$this->db->select_max($this->primary);
		$query = $this->db->get($this->tableName);
		$id = ($query->row()->{$this->primary} + 1);
		return $id;
	}
	
	public function delete($where = NULL){
		if($this->softDeletes){
			if($where){
				if(is_numeric($where)){
					$this->db->where(array($this->primary => $id));
				}else if(is_array($where)){
					$this->db->where($where);
				}
				return $this->db->update($this->tableName, array("{$this->deletedField}" => date("Y-m-d H:i:s")));
			}else{
				if($this->{$this->primary}){
					$this->db->where(array($this->primary => $this->{$this->primary}));
					return $this->db->update($this->tableName, array("{$this->deletedField}" => date("Y-m-d H:i:s")));
				}
			}
		}else{
			return $this->forceDelete($where);
		}

		return FALSE;
	}

	public function forceDelete($where = NULL){	
		if($where){
			if(is_numeric($where)){
				$this->db->where(array($this->primary => $where));
			}else if(is_array($where)){
				$this->db->where($where);
			}
			return $this->db->delete($this->tableName);
		}else{
			if($this->{$this->primary}){
				$this->db->where(array($this->primary => $this->{$this->primary}));
				return $this->db->delete($this->tableName);
			}
		}
		
		return FALSE;
	}

	public function restore($where = NULL){
		if($where){
			if(is_numeric($where)){
				$this->db->where(array($this->primary => $where));
			}else if(is_array($where)){
				$this->db->where($where);
			}
			return $this->db->update($this->tableName, array("{$this->deletedField}" => NULL));
		}else{
			if($this->{$this->primary}){
				$this->db->where(array($this->primary => $this->{$this->primary}));
				return $this->db->update($this->tableName, array("{$this->deletedField}" => NULL));
			}
		}

		return FALSE;
	}

	public function validate(){
		if(!empty($this->validator)){
			$this->load->library("form_validation");
			$conf = $this->getConf();
			$this->form_validation->set_rules($conf);
			
			$temp = array();

			if($this->form_validation->run() == FALSE){
				$this->messageString = validation_errors();

				foreach($this->validator as $field => $rules){
					$temp[$field] = form_error($field);
				}
				$this->messageArray = $temp;

				return FALSE;
			}
		}
		return TRUE;
	}

	public function messageArray(){
		return $this->messageArray;
	}

	public function messageString(){
		return $this->messageString;
	}

	protected function getConf(){
		$conf = array();

		foreach ($this->validator as $field => $rules) {
			$temp["field"] = $field;
			$temp["rules"] = $rules;
			$temp["label"] = $this->label[$field]?$this->label[$field]:"fields";

			$conf[] = $temp;
		}

		return $conf;
	}

	public function getSoftDeletes(){
		return $this->softDeletes;
	}

	public function justTrash(){
		$this->softDeletes = FALSE;
		$this->justTrash = TRUE;
		return $this;
	}

	public function withTrash(){
		$this->softDeletes = FALSE;
		$this->justTrash = FALSE;
		return $this;	
	}

	public function getPrimary(){
		return $this->primary;
	}

	public function getClassName(){
		return $this->className;
	}

	public function belongsTo($modelName = NULL, $foreignKey = NULL){
		if($modelName){
			if(is_null($foreignKey)){
				$foreignKey = getStrippedClass($modelName)."_id";
			}
			
			$ci =& get_instance();
			$ci->load->model($modelName);

			$row = $ci->{$modelName}->first($this->{$foreignKey});
			return $row;
		}
		return NULL;
	}

	public function hasOne($modelName = NULL, $foreignKey = NULL ){
		if($modelName){
			if(is_null($foreignKey)){
				$foreignKey = getStrippedClass($this->className)."_id";
			}

			$ci =& get_instance();
			$ci->load->model($modelName);

			$row = $ci->{$modelName}->first(array($foreignKey => $this->{$this->primary}));
			return $row;
		}
		return NULL;
	}

	public function hasMany($modelName = NULL, $foreignKey = NULL ){
		if($modelName){
			if(is_null($foreignKey)){
				$foreignKey = getStrippedClass($this->className)."_id";
			}
			$ci =& get_instance();
			$ci->load->model($modelName);

			$array = $ci->{$modelName}->all(array($foreignKey => $this->{$this->primary}));
			return $array;
		}
		return NULL;
	}
}