<?php
//=-----------------------------------------------------------=
// htmlgen_site.php
//=-----------------------------------------------------------=
// Author: Richard Mogendorff: 19-Oct-2013
//
// Version 1.0
//
// This is the class that generates the common html elements
// for the application.
//
// The basic sequence of functions called for a page is:
//
// $hg = HtmlGenerator::getInstance();
// $hg->startPage();
// $hg->closePage();
//=-----------------------------------------------------------=
class HtmlGenerator
{
	//Only one instance of this is created, and this contains that instance.
	private static $s_HtmlGenerator;

	//These are used to help manage state in the generator and ensure correct usage.
	private $m_pageOpened;

    // path to templates init in construct

    private $_head;
    private $_header;
    private $_footer;
	
	//=---------------------------------------------------------=
	// getInstance
	//=---------------------------------------------------------=
	// A static method that returns an instance of the HtmlGenerator object. Only one of these is created, so
	// the same instance is returned on repeated calls to this method.
	//
	// Returns:
	//    HtmlGenerator
	public static function getInstance($_head = NULL, $_header = NULL, $_footer = NULL)
	{
		if (HtmlGenerator::$s_HtmlGenerator === NULL)
		{
			HtmlGenerator::$s_HtmlGenerator = new HtmlGenerator($_head = NULL, $_header = NULL, $_footer = NULL);
		}

		return HtmlGenerator::$s_HtmlGenerator;
	}

	//=---------------------------------------------------------=
	// __construct
	//=---------------------------------------------------------=
	// Don't want anybody to call this except for getInstance above so make this private.
	private function __construct($_head = NULL, $_header = NULL, $_footer = NULL)
	{
		$this->m_pageOpened = FALSE;
        $this->_head = $this->setTemplate(_HEAD);
        $this->_header = $this->setTemplate(_HEADER);
        $this->_footer = $this->setTemplate(_FOOTER);
	}

	//=---------------------------------------------------------=
	// __destruct
	//=---------------------------------------------------------=
	// cleans up this instance.
	function __destruct()
	{
	}

    //=---------------------------------------------------------=
    // templateExist
    //=---------------------------------------------------------=
    // check if template on given path exists.
    // @return: bool
    // @templatePath: path to given template

    private function templateExist($templatePath)
    {
        if(file_exists($templatePath)) return TRUE;
        else return FALSE;
    }

    private function setTemplate($templatePath){
        if ($this->templateExist($templatePath)) return $templatePath;
        else{
            throw new NoTemplateException($templatePath);
        }
    }



    /*
    private function setDefaultTemplate($template){
        if (file_exists(strtoupper($template)))
        {
            $this->{strtolower($template)};
            return TRUE;
        }
        else{
            throw new NoTemplateException($template);
        }

    }
    */


	//=---------------------------------------------------------=
	//=---------------------------------------------------------=
	//                      Public Methods
	//=---------------------------------------------------------=
	//=---------------------------------------------------------=


	//=---------------------------------------------------------=
	// startPage
	//=---------------------------------------------------------=
	// This routine starts a page by emitting the XHTML headers and the <head> portion of the HTML up to the start of the content section
	//
	// Parameters:
	//		$in_pageTitle - the title for this page.

	public function startPage($pageTitle = SEO_TITLE)
	{
		if ($this->m_pageOpened)
		{
			throw new InternalErrorException('The startPage() method has already been called!');
		}
		$this->m_pageOpened = TRUE;		
		
		//=---------------------------------=		
		//Output HTML									
		//=---------------------------------=

        /*
		 $pageName = basename($_SERVER['PHP_SELF']);
		 
		 $b1 = '';
		 $b2 = '';
		 $b3 = '';
		 
		 if ($pageName == 'index.php'){
			 $b1 = 'active';
		 }
		 else if ($pageName == 'my_wishlists.php'){
			 $b2 = 'active';
		 }
		 else if ($pageName == 'friends_list.php'){
			 $b3 = 'active';
		 }
		 else if ($pageName == 'friend_wishlists.php'){
			 $b3 = 'active';
		 }
        */

        include_once $this->_head;
        include_once $this->_header;
	}

    public function includeTemplate($templatePath){
        if ($this->templateExist($templatePath)){
            include $templatePath;
        }
        else{
            throw new NoTemplateException($templatePath);
        }
    }

    public function navigationMenu(){
        echo '<h2>Nav Menu</h2>';
    }

    public function renderSelectOptions($options, $selected = NULL){
        $initVal = ($options === NULL)? 'No options are available': 'Select value';
        $selectOptions = '<option value="0">'.$initVal.'</option>';
        foreach($options as $key => $option){
            $selectOptions .= (!empty($selected) && $selected == $key)? '<option value="'.$key.'" selected="selected">'.$option.'</option>': '<option value="'.$key.'">'.$option.'</option>';
        }
        return $selectOptions;
    }
	

	//=---------------------------------------------------------=
	// closePage
	//=---------------------------------------------------------=
	// Generate the footer and close the page.
	 
	public function closePage()
	{
		if (!$this->m_pageOpened)
		{
			throw new InternalErrorException('The startPage() method was never called to open the page body');
		}

		$this->m_pageOpened = FALSE;
	
		include_once $this->_footer;
	
	}
	
	
	
	//=---------------------------------------------------------=
	// closePage
	//=---------------------------------------------------------=
	// Generate the footer and close the page.

	
	
	
}

?>