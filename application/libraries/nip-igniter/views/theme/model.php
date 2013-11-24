<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class {content:class} extends Nip_Model {
	protected $tableName = "{content:tableName}";
	protected $primary = "{content:primary}";

	protected $softDeletes = TRUE;

	{content:variable}

	protected $validator = array(
			{content:validator}
		);
	
	protected $label = array(
			{content:label}
		);

	public function __construct($options = array()){
		parent::__construct($options);
	}
}