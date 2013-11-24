<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CrudGenerator extends CI_Model {
	public $pathFolderTheme;
	public $pathControllerTheme;
	public $pathControllerTarget;

	public $pathViewFolderTheme;

	
	public function __construct($options = array()){
		parent::__construct($options);
		$this->pathFolderTheme = APPPATH."libraries/nip-igniter/views";
		$this->pathControllerTheme = "/theme/controller.php";
		$this->pathControllerTarget = APPPATH."controllers/";

		$this->pathViewFolderTheme = "/theme/crud/";
		$this->pathViewFolderTarget = APPPATH."views/";

	}

	public function createCrud($primary = null, $className = null, $fields = null){
		if($primary && $className && $fields){
			$template = $this->createController($primary, $className, $fields);
			$template = $this->createView($primary, $className, $fields);
			return TRUE;
		}
		return FALSE;
	}

	public function createController($primary = null, $className = null, $fields = null){
		if($primary && $className && $fields){
			$controllerName = $className."Controller";
			$template = file_get_contents($this->pathFolderTheme.$this->pathControllerTheme);
			
			$foreignkey = "";
			$loadModel = array();
			foreach($fields as $name => $array){
				if($array['type'] == "fk"){
					$loadModel[] = $array['fk_name'];
					$foreignkey .= "\$data['{$name}'] = \$this->{$array['fk_name']}->all();\n\t\t\t";
				}
			}

			$strLoadModel = "";
			if(!empty($loadModel)){
				foreach($loadModel as $value){
					$strLoadModel .= ",'{$value}'";
				}
			}

			$template = str_replace("{content:loadmodel}", $strLoadModel, $template);
			$template = str_replace("{content:foreignkey}", $foreignkey, $template);
			$template = str_replace("{content:classname}", $className, $template);
			$template = str_replace("{content:primary}", $primary, $template);
			
			$fileName = $this->pathControllerTarget.$controllerName.".php";
			if($this->generate($fileName, $template)){
				return $template;
			}
		}
		return FALSE;
	}

	public function createView($primary = null, $className = null, $fields = null){
		if($primary && $className && $fields){
			
			$folder = $this->pathViewFolderTarget.getStrippedClass($className);
			$this->createFolder($folder);

			/*Create page.php*/
			/* --------------------------------------- */
			$template = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."part/part.table-body.php");
			
			$thead = "";
			$tbody = "";
			foreach($fields as $name => $array){
				if(isset($array['show'])){
					$thead .= "<th>".getLabel($name)."</th>\n\t\t\t\t\t\t";
					if($array['type']=='fk'){
						$tbody .= "<td><?php echo \$this->{$array['fk_name']}->first(\$row->{$name})->{$array['fk_label']};?></td>\n\t\t\t\t\t\t\t";
					}else{
						$tbody .= "<td><?php echo \$row->{$name};?></td>\n\t\t\t\t\t\t\t";
					}
				}
			}

			$template = str_replace("{content:tbody}", $tbody, $template);
			$template = str_replace("{content:primary}", $primary, $template);
			
			$fileName = $folder."/page.php";
			$this->generate($fileName, $template);

			$temporaryTbody = $template;
			/* --------------------------------------- */

			/*Create index.php*/
			$template = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."part/part.table.php");
			$template = str_replace("{content:thead}", $thead, $template);
			$template = str_replace("{content:body}", $temporaryTbody, $template);

			$temporaryTable = $template;

			$template = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."main/index.php");
			$template = str_replace("{content:table}", $temporaryTable, $template);
			$template = str_replace("{content:classname}", $className, $template);
			$template = str_replace("{content:primary}", $primary, $template);
			
			$fileName = $folder."/index.php";
			$this->generate($fileName, $template);
			/* --------------------------------------- */

			/*Create view.php*/
			/* --------------------------------------- */
			$template = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."main/view.php");
			
			$tr = "";
			foreach($fields as $name => $array){
				if($array['type']=='fk'){
					$tr .= "<tr><td>".getLabel($name)."</td><td>:</td><td><?php echo \$this->{$array['fk_name']}->first(\$model->{$name})->{$array['fk_label']};?></td></tr>\n\t";
				}else{
					$tr .= "<tr><td>".getLabel($name)."</td><td>:</td><td><?php echo \$model->{$name};?></td></tr>\n\t";
				}
			}
			
			$template = str_replace("{content:tr}", $tr, $template);
			
			$fileName = $folder."/view.php";
			$this->generate($fileName, $template);
			/* --------------------------------------- */

			/*Create edit.php*/
			/* --------------------------------------- */
			$template = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."main/edit.php");
			
			$fieldsTemplate = "";
			foreach($fields as $name => $array){
				if($name!=$primary){
					$partTemplate = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."part/part.{$array['type']}.php");
					
					if($array['type']=="fk"){
						$partTemplate = str_replace("{content:fk_primary}", $array['fk_id'], $partTemplate);
						$partTemplate = str_replace("{content:fk_label}", $array['fk_label'], $partTemplate);
					}	
					
					$partTemplate = str_replace("{content:field}", $name, $partTemplate);
					$partTemplate = str_replace("{content:classname}", $className, $partTemplate);
					$partTemplate = str_replace("{content:label}", getLabel($name), $partTemplate);

					$fieldsTemplate .= $partTemplate."\n\t";
				}
			}
			
			$template = str_replace("{content:fields}", $fieldsTemplate, $template);
			$template = str_replace("{content:primary}", $primary, $template);
			$template = str_replace("{content:classname}", $className, $template);
			
			$fileName = $folder."/edit.php";
			$this->generate($fileName, $template);
			/* --------------------------------------- */

			$this->createFolder($folder."/trash");

			/*Create trash/page.php*/
			/* --------------------------------------- */
			$template = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."part/part.trash.table-body.php");
			
			$template = str_replace("{content:tbody}", $tbody, $template);
			$template = str_replace("{content:primary}", $primary, $template);
			
			$fileName = $folder."/trash/page.php";
			$this->generate($fileName, $template);

			$temporaryTbody = $template;
			/* --------------------------------------- */

			/*Create trash/index.php*/
			$template = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."part/part.table.php");
			$template = str_replace("{content:thead}", $thead, $template);
			$template = str_replace("{content:body}", $temporaryTbody, $template);

			$temporaryTable = $template;

			$template = file_get_contents($this->pathFolderTheme.$this->pathViewFolderTheme."main/trash/index.php");
			$template = str_replace("{content:table}", $temporaryTable, $template);
			$template = str_replace("{content:classname}", $className, $template);
			$template = str_replace("{content:primary}", $primary, $template);
			
			$fileName = $folder."/trash/index.php";
			$this->generate($fileName, $template);
			/* --------------------------------------- */

		}
		return FALSE;
	}

	public function generate($fileName, $content){
		if(is_writable($this->pathControllerTarget)){
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

	public function createFolder($name){
		if(!is_dir($name)){
			mkdir($name, 0777);
		}
	}
}