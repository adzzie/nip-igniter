<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Nip_Router extends CI_Router {
	
	/**
	 * Validates the supplied segments.  Attempts to determine the path to
	 * the controller.
	 *
	 * @access	private
	 * @param	array
	 * @return	array
	 */
	/*function _validate_request($segments)
	{
		if (count($segments) == 0)
		{
			return $segments;
		}

		$temp = $segments[0];
		$segments[0] = $this->change_class_name($segments[0]);
		
		// Does the requested controller exist in the root folder?
		if (file_exists(APPPATH.'controllers/'.$segments[0].'.php'))
		{
			return $segments;
		}
		
		$segments[0] = $temp;

		// Is the controller in a sub-folder?
		if (is_dir(APPPATH.'controllers/'.$segments[0]))
		{
			// Set the directory and remove it from the segment array
			$this->set_directory($segments[0]);
			$segments = array_slice($segments, 1);

			if (count($segments) > 0)
			{
				$segments[0] = $this->change_class_name($segments[0]);
				// Does the requested controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].'.php'))
				{
					if ( ! empty($this->routes['404_override']))
					{
						$x = explode('/', $this->routes['404_override']);

						$this->set_directory('');
						$this->set_class($x[0]);
						$this->set_method(isset($x[1]) ? $x[1] : 'index');

						return $x;
					}
					else
					{
						show_404($this->fetch_directory().$segments[0]);
					}
				}
			}
			else
			{
				// Is the method being specified in the route?
				if (strpos($this->default_controller, '/') !== FALSE)
				{
					$x = explode('/', $this->default_controller);

					$this->set_class($x[0]);
					$this->set_method($x[1]);
				}
				else
				{
					$this->set_class($this->default_controller);
					$this->set_method('index');
				}

				// Does the default controller exist in the sub-folder?
				if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.'.php'))
				{
					$this->directory = '';
					return array();
				}

			}

			return $segments;
		}


		// If we've gotten this far it means that the URI does not correlate to a valid
		// controller class.  We will now see if there is an override
		if ( ! empty($this->routes['404_override']))
		{
			$x = explode('/', $this->routes['404_override']);

			$this->set_class($x[0]);
			$this->set_method(isset($x[1]) ? $x[1] : 'index');

			return $x;
		}


		// Nothing else to do at this point but show a 404
		show_404($segments[0]);
	}*/

	function _validate_request($segments)
    {
    	$temp = $segments[0];
		$segments[0] = $this->change_class_name($segments[0]);

        if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
        {
            return $segments;
        }

        $segments[0] = $temp;

        if (is_dir(APPPATH.'controllers/'.$segments[0]))
        {
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);

            /* ----------- ADDED CODE ------------ */


            while(count($segments) > 0 && is_dir(APPPATH.'controllers/'.$this->directory.$segments[0]))
            {
            	// Set the directory and remove it from the segment array
            //$this->set_directory($this->directory . $segments[0]);
            if (substr($this->directory, -1, 1) == '/')
                $this->directory = $this->directory . $segments[0];
            else
                $this->directory = $this->directory . '/' . $segments[0];

            $segments = array_slice($segments, 1);
            }

            if (substr($this->directory, -1, 1) != '/')
                $this->directory = $this->directory . '/';

            /* ----------- END ------------ */

            if (count($segments) > 0)
            {

            	$segments[0] = $this->change_class_name($segments[0]);

                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().'/'.$segments[0].EXT))
                {
                    show_404($this->fetch_directory().$segments[0]);
                }
            }
            else
            {
                $this->set_class($this->default_controller);
                $this->set_method('index');

                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().'/' .$this->default_controller.EXT))
                {
                    $this->directory = '';
                    return array();
                }

            }

            return $segments;
        }

        show_404($segments[0]);
    }

	/**
	 * Fetch the current class
	 *
	 * @access	public
	 * @return	string
	 */
	function fetch_class()
	{
		//$this->class = $this->change_class_name($this->class);
		return $this->class;
	}

	function change_class_name($name){
		$array = explode("-", $name);
		$array_upper_case = array_map("ucwords", $array);
		$string = implode("", $array_upper_case);
		$string .= "Controller";
		return $string;
	}

	/**
	 *  Fetch the current method
	 *
	 * @access	public
	 * @return	string
	 */
	function fetch_method()
	{
		if ($this->method == $this->fetch_class())
		{
			return 'index';
		}

		$this->method = $this->change_method_name($this->method);
		return $this->method;
	}

	function change_method_name($name){
		$array = explode("-", $name);
		$array_upper_case = array_map("ucwords", $array);
		$string = implode("", $array_upper_case);
		$string = $this->lowerfirst($string);
		return $string;
	}

	function lowerfirst($str){
		$str[0] = strtolower($str[0]);
        return (string)$str;
	}
}