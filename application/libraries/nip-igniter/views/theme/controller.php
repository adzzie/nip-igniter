<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class {content:classname}Controller extends Nip_Controller {
	public $pageTitle = "{content:classname} Controller Page";
	
	public $msg = array(
			"success" => array(
				"status" => 1,
				"message" => "Success",
				"param" => "success"
			),
			"failed" => array(
				"status" => 2,
				"message" => "Failed",
				"param" => "danger"
			),
			"invalid" => array(
				"status" => 3,
				"message" => "Invalid",
				"param" => "warning"
			),
		);

	public $Model;

	public $limit = 10;

	public function __construct(){
		parent::__construct();
		$this->load->model("{content:classname}");
		{content:loadmodel}
		$this->Model = new {content:classname}();
	}

	public function index(){
		$offset = 0;
		$uri = 3;
		$baseUrl = site_url("{$this->controller}/page");

		$rows = $this->Model->all(null, null, null, $this->limit, $offset);
		$total = $this->Model->count();

		$pagination = $this->getPaginate($baseUrl, $total, $this->limit, $uri);

		$data['rows'] = $rows;
		$data['offset'] = $offset;
		$data['pagination'] = $pagination;
		$this->render($this->view, $data);
	}

	public function page($offset = 0){
		$uri = 3;
		$baseUrl = site_url("{$this->controller}/page");

		$rows = $this->Model->all(null, null, null, $this->limit, $offset);
		$total = $this->Model->count();

		$pagination = $this->getPaginate($baseUrl, $total, $this->limit, $uri);

		$data['rows'] = $rows;
		$data['offset'] = $offset;
		$view = $this->renderPartial("{$this->controller}/page", $data, TRUE);

		echo json_encode(array(
				'pagination' => $pagination,
				'view' => $view
			)
		);
	}

	public function search($keyword = null, $offset = 0){
		if(empty($keyword)){
			if(isset($_GET['keyword'])){
				$keyword = $_GET['keyword'];
			}else{
				$keyword = "";
			}
		}

		$uri = 4;
		$baseUrl = site_url("{$this->controller}/search/{$keyword}");

		$where = $this->getWhereCondition($keyword);

		$rows = $this->Model->all($where, null, null, $this->limit, $offset);
		$total = $this->Model->count($where);

		$pagination = $this->getPaginate($baseUrl, $total, $this->limit, $uri);

		$data['rows'] = $rows;
		$data['offset'] = $offset;
		$view = $this->renderPartial("{$this->controller}/page", $data, TRUE);

		echo json_encode(array(
				'pagination' => $pagination,
				'view' => $view
			)
		);
	}

	protected function getWhereCondition($keyword = null){
		if($keyword){
			$string = "(";
			$i=0;
			foreach(get_object_vars($this->Model) as $key => $value){
				if($i==0){
					$string .= "{$key} like '%{$keyword}%' ";
				}else{
					$string .= "OR {$key} like '%{$keyword}%' ";
				}
				$i++;
			}
			$string .=")";
			return $string;
		}
		return "";
	}

	public function edit(){
		if(isset($_POST["{content:primary}"])){
			${content:primary} = $_POST["{content:primary}"];
			
			if(empty(${content:primary})){
				$model = new $this->Model();
			}else{
				$model = $this->Model->first(array("{content:primary}"=>${content:primary}));
			}

			if(isset($_POST["{content:classname}"])){
				$fields = $_POST["{content:classname}"];
				$model->attr($fields);

				if($model->validate()){
					if($model->save()){
						echo json_encode($this->msg['success']);
					}else{
						echo json_encode($this->msg['failed']);
					}
					exit();
				}else{
					$this->msg['invalid']['message'] = $model->messageString();
					echo json_encode($this->msg['invalid']);
					exit();
				}
			}

			{content:foreignkey}
			$data["{content:primary}"] = ${content:primary};
			$data["model"] = $model;
			$this->renderPartial("{$this->controller}/edit", $data);
		}
	}

	public function view(){
		if(isset($_POST["{content:primary}"])){
			${content:primary} = $_POST["{content:primary}"];
			$model = $this->Model->first(array("{content:primary}"=>${content:primary}));

			$data['model'] = $model;
			$this->renderPartial("{$this->controller}/view", $data);
		}
	}

	public function delete(){
		if(isset($_POST["{content:primary}"])){
			${content:primary} = $_POST["{content:primary}"];
			$model = $this->Model->first(array("{content:primary}"=>${content:primary}));

			if($model->delete()){
				if($model->getSoftDeletes()){
					$this->msg['success']['message'] = 'Data has been successfully removed. <button class="btnRestore btn btn-warning btn-xs" data-id="'.${content:primary}.'" data-url="'.site_url("{$this->controller}/restore").'">Undo</button> if this action is a mistake.';
				}
				echo json_encode($this->msg['success']);
				exit();
			}
			echo json_encode($this->msg['failed']);
		}	
	}

	public function restore(){
		if(isset($_POST["{content:primary}"])){
			${content:primary} = $_POST["{content:primary}"];

			if($this->Model->restore(${content:primary})){
				$this->msg['success']['message'] = 'Data has been successfully restored';
				echo json_encode($this->msg['success']);
				exit();
			}
			echo json_encode($this->msg['failed']);
		}
	}

	public function trash(){
		$offset = 0;
		$uri = 3;
		$baseUrl = site_url("{$this->controller}/trash-page");

		$rows = $this->Model->justTrash()->all(null, null, null, $this->limit, $offset);
		$total = $this->Model->justTrash()->count();

		$pagination = $this->getPaginate($baseUrl, $total, $this->limit, $uri);

		$data['rows'] = $rows;
		$data['offset'] = $offset;
		$data['pagination'] = $pagination;
		$this->render("{$this->controller}/trash/index", $data);
	}

	public function trashPage($offset = 0){
		$uri = 3;
		$baseUrl = site_url("{$this->controller}/trash-page");

		$rows = $this->Model->justTrash()->all(null, null, null, $this->limit, $offset);
		$total = $this->Model->justTrash()->count();

		$pagination = $this->getPaginate($baseUrl, $total, $this->limit, $uri);

		$data['rows'] = $rows;
		$data['offset'] = $offset;
		$view = $this->renderPartial("{$this->controller}/trash/page", $data, TRUE);

		echo json_encode(array(
				'pagination' => $pagination,
				'view' => $view
			)
		);
	}

	public function trashSearch($keyword = null, $offset = 0){
		if(empty($keyword)){
			if(isset($_GET['keyword'])){
				$keyword = $_GET['keyword'];
			}else{
				$keyword = "";
			}
		}

		$uri = 4;
		$baseUrl = site_url("{$this->controller}/trash-search/{$keyword}");

		$where = $this->getWhereCondition($keyword);

		$rows = $this->Model->justTrash()->all($where, null, null, $this->limit, $offset);
		$total = $this->Model->justTrash()->count($where);

		$pagination = $this->getPaginate($baseUrl, $total, $this->limit, $uri);

		$data['rows'] = $rows;
		$data['offset'] = $offset;
		$view = $this->renderPartial("{$this->controller}/trash/page", $data, TRUE);

		echo json_encode(array(
				'pagination' => $pagination,
				'view' => $view
			)
		);
	}

	public function forceDelete(){
		if(isset($_POST["{content:primary}"])){
			${content:primary} = $_POST["{content:primary}"];
			
			if($this->Model->forceDelete(${content:primary})){
				$this->msg['success']['message'] = 'Data has been successfully removed.';
				echo json_encode($this->msg['success']);
				exit();
			}
			echo json_encode($this->msg['failed']);
		}	
	}

}