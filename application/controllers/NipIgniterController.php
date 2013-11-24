<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NipIgniterController extends Nip_Controller {
	
	public $pageTitle = "Welcome To NipIgniter Generator";
	
	public function __construct(){
		parent::__construct();
		$this->load->library(array("NipIgniter"));
	}

	public function index(){
		$this->render($this->view);
	}

	public function generateModel(){
		$this->nipigniter->generateModel();
	}

	public function generateCrud(){
		$this->nipigniter->generateCrud();
	}

}