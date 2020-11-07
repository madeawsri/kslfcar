<?php

/**
* Template engine
* @author  Ruslan Ismagilov <ruslan.ismagilov.ufa@gmail.com>
*/
class Template //extends Base
{
	/**
	* Content variables
	* @access private
	* @var array
	*/
	private $vars = array();

	/**
	* Content delimiters
	* @access private
	* @var string
	*/
	private $l_delim = '{',
	$r_delim = '}';

  public function __construct(){
	 global $GET_SERVER_PATH,$GET_SITE_NAME;
	  $folder_project = Config::URL_SERVER_NAME.'/'; //'';
		// debug
    $SERVER_URL = "http://".$_SERVER['SERVER_NAME']."/".$folder_project;
	  $GET_SERVER_PATH = rtrim($SERVER_URL,'/')."/".$GET_SITE_NAME."/..";



    $this->vars['server_name'] = $SERVER_URL;
    $this->vars['server_path'] = $GET_SERVER_PATH;
    $this->vars['module_name'] = FnBase::GETMODULENAME();//@$_GET['option'];
    $this->vars['site_name'] = Config::_get_global_var('GET_SITE_NAME');
    $this->vars['project_name'] = Config::_get_global_var('project_name');
    $this->vars['include_path'] = '';
    $this->vars['ksl_name'] = Config::KSL_NAME;
    $this->vars['timex'] = time();
    $this->vars['css_links'] = array();
    $this->vars['js_links'] = array();
    //$this->vars['site_desc'] = $GET_SITE_DATA['site_desc'];
  }


  public function include_plugin($type,$paths=array()){
    $new_paths = array();
    if(strtolower($type)==="css"){
      if($paths)
        foreach($paths as $k=>$v){
           $x = array();
           $x['css_link'] = $v;
           $new_paths[]=$x;
        }
      $this->vars['css_links']=$new_paths;

    }else{

      if($paths)
        foreach($paths as $k=>$v){
           $x = array();
           $x['js_link'] = $v;
           $new_paths[]=$x;
        }
      $this->vars['js_links']=$new_paths;

    }
    return $this;
  }

  public function include_menu($type,$paths=array()){
    $new_paths = array();
    if($paths)
      foreach($paths as $k=>$v){
        $x = array();
        $x['css_link'] = $v;
        $new_paths[]=$x;
    }
    $this->vars['css_links']=$new_paths;

    return $this;
  }

	/**
	* Set template property in template file
	* @access public
	* @param string $key property name
	* @param string $value property value
	*/
	public function assign( $key, $value )
	{
		$this->vars[$key] = $value;
    return $this;
	}

  public function assign_array($vals=array()){
    foreach($vals as $k=>$v){
      $this->vars[$k] = $v;
    }
  }

	/**
	* Parce template file
	* @access public
	* @param string $template_file
	*/
	public function parse( $template_file )
	{
		if( file_exists( $template_file ) )
		{
			$content = file_get_contents($template_file);

			foreach( $this->vars as $key => $value )
			{
				if( is_array( $value ) )
				{
					$content = $this->parsePair($key, $value, $content);
				}
				else
				{
					$content = $this->parseSingle($key, (string) $value, $content);
				}
			}

			eval( '?> ' . $content . '<?php ' );
		}
		else
		{
			exit( '<h1>Template error</h1>' );
		}
	}

	/**
	* /
	* @param undefined $template_file
	* //eval( '?> ' . $content . '<?php ' );
	* @return
	*/
	public function render( $template_file )
	{
		if( file_exists( $template_file ) )
		{
			$content = file_get_contents($template_file);

			foreach( $this->vars as $key => $value )
			{
				if( is_array( $value ) )
				{
					$content = $this->parsePair($key, $value, $content);
				}
				else
				{
					$content = $this->parseSingle($key, (string) $value, $content);
				}
			}
			return $content;
		}
		else
		{
			return '<h1>Template error</h1>';
		}
	}

	/**
	* Parsing content for single varliable
	* @access private
	* @param string $key property name
	* @param string $value property value
	* @param string $string content to replace
	* @param integer $index index of loop item
	* @return string replaced content
	*/
	private function parseSingle( $key, $value, $string, $index = null )
	{
		if( isset( $index ) )
		{
			$string = str_replace( $this->l_delim . '%index%' . $this->r_delim, $index, $string );
		}
		return str_replace( $this->l_delim . $key . $this->r_delim, $value, $string );
	}

	/**
	* Parsing content for loop varliable
	* @access private
	* @param string $variable loop name
	* @param string $value loop data
	* @param string $string content to replace
	* @return string replaced content
	*/
	private function parsePair( $variable, $data, $string )
	{
		$match = $this->matchPair($string, $variable);
		if( $match == false ) return $string;

		$str = '';
		foreach( $data as $k_row => $row )
		{
			$temp = $match['1'];
			foreach( $row as $key => $val )
			{
				if( !is_array( $val ) )
				{
					$index = array_search( $k_row, array_keys( $data ) );
					$temp  = $this->parseSingle( $key, $val, $temp, $index );
				}
				else
				{
					$temp = $this->parsePair( $key, $val, $temp );
				}
			}
			$str .= $temp;
		}

		return str_replace( $match['0'], $str, $string );
	}

	/**
	* Match loop pair
	* @access private
	* @param string $string content with loop
	* @param string $variable loop name
	* @return string matched content
	*/
	private function matchPair( $string, $variable )
	{
		if( !preg_match("|" . preg_quote($this->l_delim) . 'loop ' . $variable . preg_quote($this->r_delim) . "(.+?)". preg_quote($this->l_delim) . 'end loop' . preg_quote($this->r_delim) . "|s", $string, $match ) )
		{
			return false;
		}

		return $match;
	}

  function test(){
    return "Class Template";
  }
}
