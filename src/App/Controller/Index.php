<?php
namespace App\Controller;

use Tk\Request;
/**
 * Class Index
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Index extends Iface
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct('Home');
    }

    /**
     * @param Request $request
     * @return \App\Page\Iface
     */
    public function doDefault(Request $request)
    {
        // TODO: 
        //throw new \Tk\Exception('This page should neot need a controller...its a wiki DOPE!!!');
        

        return $this->showDefault($request);
    }


    /**
     * Note: no longer a dependacy on show() allows for many show methods for many 
     * controller methods (EG: doAction/showAction, doSubmit/showSubmit) in one Controller object
     * 
     * @param Request $request
     * @return \App\Page\PublicPage
     */
    public function showDefault(Request $request)
    {
        $template = $this->getTemplate();
        
        return $this->getPage()->setPageContent($template);
    }


    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<HTML
<div>
  <p>TODO: REPLACE THIS PAGE WITH A WIKI PAGE...... The /home, /index.html , /index.php, etc page should be assumed to exist</p>
</div>
HTML;

        return \Dom\Loader::load($xhtml);
    }

}