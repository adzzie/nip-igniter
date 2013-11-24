<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(BASEPATH."core/Model.php");
require_once(APPPATH."libraries/nip-igniter/class/ModelGenerator.php");
require_once(APPPATH."libraries/nip-igniter/class/CrudGenerator.php");

class NipIgniter extends Nip_Controller {
	
	public $pageTitle = "Welcome To NipIgniter Generator";
	
	public $msg = array(
			"success" => array(
				"status" => 1,
				"message" => "Success"
			),
			"failed" => array(
				"status" => 2,
				"message" => "Failed"
			)
		);

	public $ModelGenerator;
	public $CrudGenerator;

	public function __construct(){
		parent::__construct();
		$this->ModelGenerator = new ModelGenerator();
		$this->CrudGenerator = new CrudGenerator();
	}

	public function index(){
		$this->render($this->view);
	}

	public function generateModel(){
		if(isset($_POST['table_name'])){
			$tableName = $_POST['table_name'];
			$isCrud = isset($_POST['is_crud'])?TRUE:FALSE;
			$status = $this->ModelGenerator->createModel($tableName, $isCrud);
			if($status !== FALSE){
				$this->msg["success"]["content"] = "<pre>".htmlspecialchars($status["template"])."</pre>";
				if($isCrud){
					$this->msg['success']['fields'] = $this->fields($status['primary'],$status['classname'],$status['fields']);
				}
				echo json_encode($this->msg["success"]);
				exit();
			}
			echo json_encode($this->msg["failed"]);
			exit();
		}
	}

	protected function fields($primary, $className, $fields){
		$data['primary'] = $primary;
		$data['classname'] = $className;
		$data['fields'] = $fields;
		
		return $this->renderPartial("nip-igniter/fields",$data , TRUE);
	}

	public function generateCrud(){
		$primary = $this->input->post("primary");
		$className = $this->input->post("classname");
		$fields = $this->input->post("fields");
		$this->CrudGenerator->createCrud($primary, $className, $fields);
		echo json_encode($this->msg["success"]);
	}

}