<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nip_Controller extends CI_Controller {
	//-------------------------------------------------------------
	/**
	 * Title of page
	 *
	 * @var string
	 * @access public
	 */
	public $pageTitle;
	/**
	 * Partial view
	 *
	 * @var string
	 * @access public
	 */
	public $pageContent;
	/**
	 * Layout view
	 *
	 * @var string
	 * @access public
	 */
	public $pageLayout = "layout/main";
	//-------------------------------------------------------------

	//-------------------------------------------------------------
	/**
	 * Controller folder name
	 *
	 * @var string
	 * @access public
	 */
	public $folder = null;
	/**
	 * Controller name in the current URL
	 *
	 * @var string
	 * @access public
	 */
	public $controller;
	/**
	 * Action name in the current URL
	 *
	 * @var string
	 * @access public
	 */
	public $action;
	/**
	 * Combination of URL to view folder
	 *
	 * @var string
	 * @access public
	 */
	public $view;
	//-------------------------------------------------------------

	//-------------------------------------------------------------
	/**
	 * Controller Segment on URL
	 *
	 * @var integer
	 * @access public
	 */
	public $controllerSegment = 1;
	/**
	 * Action Segment on URL
	 *
	 * @var integer
	 * @access public
	 */
	public $actionSegment = 2;
	//-------------------------------------------------------------
	

	public function __construct(){
		parent::__construct();
		$this->setController();
		$this->setAction();
		$this->setView();
		date_default_timezone_set("Asia/Jakarta");
	}
	
	public function render($view, $data = array(), $bool = FALSE){
		$data['controller'] = $this->controller;
		$data['action'] = $this->action;
		
		$data['pageTitle'] = $this->pageTitle;
		$data['pageContent'] = $this->load->view($view,$data,TRUE);
		
		if($bool){
			return $this->load->view($this->pageLayout, $data, TRUE);
		}else{
			$this->load->view($this->pageLayout, $data);
		}
	}
	
	public function renderPartial($view, $data = array(), $bool = FALSE){
		$data['controller'] = $this->controller;
		$data['action'] = $this->action;
		
		$data['pageTitle'] = $this->pageTitle;
		
		if($bool){
			return $this->load->view($view,$data,TRUE);
		}else{
			$this->load->view($view,$data);
		}
	}
	
	public function setController($controller = null){
		if($controller){
			$this->controller = $controller;
		}else{
			$this->controller = $this->uri->segment($this->controllerSegment)?$this->uri->segment($this->controllerSegment):$this->getDefaultClass();
		}
	}
	
	public function getController(){
		return $this->controller;
	}

	public function setAction($action = null){
		if($action){
			$this->action = $action;
		}else{
			$this->action = 
				$this->uri->segment($this->actionSegment)?
					$this->uri->segment($this->actionSegment):
						"index";
		}

	}
	
	public function getAction(){
		return $this->action;
	}
	
	public function getDefaultClass(){
		preg_match_all('/((?:^|[A-Z])[a-z]+)/',get_class($this),$matches);
		$defaultClass = $this->changeClassName($matches[0]);
		return $defaultClass;
	}

	protected function changeClassName($arrClassName = null){
		if($arrClassName){
			$newClass = "";
			foreach ($arrClassName as $i => $value) {
				if($i==0){
					$newClass .= strtolower($value);
				}else{
					if(strtolower($value) == "controller")
						break;
					$newClass .= "-".strtolower($value);
				}
			}
			return $newClass;
		}
	}

	public function setView(){
		if($this->folder){
			$this->view = "{$this->folder}/{$this->controller}/{$this->action}";
		}else{
			$this->view = "{$this->controller}/{$this->action}";
		}
		
	}

	public function getPaginate($baseUrl, $total, $limit, $uri){
		$this->load->library('pagination');

		$config['base_url'] = $baseUrl;
		$config['total_rows'] = $total;
		$config['per_page'] = $limit;
		$config['uri_segment'] = $uri;

		$config['full_tag_open'] = '<ul class="pagination pull-right" style="margin:0">';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = '&laquo;';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '&raquo;';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = '›';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '‹';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);

		return $this->pagination->create_links();
	}
	
}