<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine Page History Plugin
 *
 * @package		Page History
 * @subpackage		Plugins
 * @category		Plugins
 * @author		David Hyland
 * @link			http://www.dhyland.com
 */

$plugin_info = array(
				'pi_name'			=> 'Page History',
				'pi_version'		=> '1.0.5',
				'pi_author'		=> 'David Hyland',
				'pi_author_url'	=> 'http://www.dhyland.com',
				'pi_description'	=> 'Returns last pages visited',
				'pi_usage'		=> Page_history::usage()
			);


class Page_history {  
	
  /**
   * Constructor
   */  
	function __construct()
	{
		// make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
		$this->prefix = $this->EE->TMPL->fetch_param('variable_prefix', 'page_history:');
	}

	// --------------------------------------------------------------------
	
  /**
   * Get page from session tracker
   */  
 function get()
	{
	  $page = $this->EE->TMPL->fetch_param('page', 1);
		$site_url = $this->EE->TMPL->fetch_param('site_url');
	  $tagdata = $this->EE->TMPL->tagdata;
		$path = isset($this->EE->session->tracker[$page]) ? $this->EE->session->tracker[$page] : false;
		$path = $path && $site_url ? $this->EE->functions->create_url($path) : $path;
		if ($tagdata)
		{
		  $variables = array(
        array(
          $this->prefix.'path' => $path,
        ),
		  );
		  $tagdata = $path ? $this->EE->TMPL->parse_variables($tagdata, $variables) : '';
  		return $tagdata;
		}
		else {
  		return $path;
		}
	}
	/* END */
	
		
// ----------------------------------------
//  Plugin Usage
// ----------------------------------------

// This function describes how the plugin is used.
//  Make sure and use output buffering

function usage()
{
ob_start(); 
?>

To get the current URL:
{exp:page_history:get page='0'}

To get the previous page:
{exp:page_history:get page='1'} 
OR simply:
{exp:page_history:get}

To get "two pages ago":
{exp:page_history:get page='2'}

To output content conditionally, use a tag pair:
{exp:page_history:get page='1'}
<a href="{page_history:path}">Back</a>
{/exp:page_history:get}

To output potential collisions with other variable parsing, you may specify a prefix.
The default is "page_history:"
{exp:page_history:get page='1' prefix="foo:"}
  {foo:path}
{/exp:page_history:get}

And so on, up to a limit of 5 "pages ago"

The default returned URL structure is page relative, ie "folder/template/".

To return the full site URL (ie: http://domain.com/page) add the parameter site_url='yes', eg:
{exp:page_history:get page='1' site_url='yes'} 

Marvellous :)

<?php
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}
/* END */


}
// END CLASS

/* End of file pi.page_history.php */
/* Location: ./system/expressionengine/third_party/page_history/pi.page_history.php */