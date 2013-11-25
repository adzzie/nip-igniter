<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ModelGenerator extends CI_Model {
	public $pathFolderTheme;
	public $pathModelTheme;
	public $pathModelTarget;
	
	public function __construct($options = array()){
		parent::__construct($options);
		$this->pathFolderTheme = APPPATH."libraries/nip-igniter/views";
		$this->pathModelTheme = "/theme/model.php";
		$this->pathModelTarget = APPPATH."models/";
	}
	public function createModel($tableName = NULL, $isCrud = FALSE){
		if($tableName){
			$fields = $this->getFields($tableName);
			$primary = $this->getPrimary($fields);
			$template = file_get_contents($this->pathFolderTheme.$this->pathModelTheme);
			$className = $this->changeClassName($tableName);
			
			$variable = '';
			$validator = '';
			$label = '';

			$ignoreField = array($primary,"created","updated","deleted");

			foreach($fields as $field){
				$variable .= "public \${$field->name};\n\t";
				if(!in_array($field->name, $ignoreField)){
					
					$validator .= "'{$className}[{$field->name}]' => 'required";
					if($field->type == 'int'){
						$validator .= "|numeric";
					}
					if(!empty($field->max_length)){
						$validator .= "|max_length[{$field->max_length}]";
					}
					if(strpos(strtolower($field->name), 'email') !== false){
						$validator .= "|valid_email";
					}

					$validator .= "',\n\t\t\t";
					
					$label .= "'{$className}[{$field->name}]' => '".getLabel($field->name)."',\n\t\t\t";
				}
			}
			
			$template = str_replace("{content:class}", $className, $template);
			$template = str_replace("{content:variable}", $variable, $template);
			$template = str_replace("{content:validator}", $validator, $template);
			$template = str_replace("{content:label}", $label, $template);
			$template = str_replace("{content:tableName}", $tableName, $template);
			$template = str_replace("{content:primary}", $primary, $template);
			
			$fileName = $this->pathModelTarget.$className.".php";
			if($this->generate($fileName, $template)){
				return array(
					"fields" => $fields,
					"template" => $template,
					"primary" => $primary,
					"classname" => $className
				);
			}
		}
		return FALSE;
	}

	protected function getFields($table){
		if ($this->db->table_exists($table)){
   			return $this->db->field_data($table);
		}
		return NULL;
	}
	
	protected function getPrimary($fields = array()){
		foreach($fields as $field){
			if($field->primary_key==1){
				return $field->name;
			}
		}
		return NULL;
	}

	public function changeClassName($name){
		$array = explode("_", $name);
		$arrayUpperCase = array_map("ucwords", $array);
		$string = implode("", $arrayUpperCase);
		return $string;
	}
	
	public function generate($fileName, $content){
		if(is_writable($this->pathModelTarget)){
			if(!$file = fopen($fileName,'w')){
				return FALSE;
			}
			if(!fwrite($file, $content)){
				return FALSE;
			}
			fclose($file);
			return TRUE;
		}
		return NULL;
	}
}