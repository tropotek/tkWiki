<?php
namespace App\Controller\Admin\Dev;

use Tk\Request;
use Dom\Template;
use \App\Controller\Iface;

/**
 *
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Events extends Iface
{

    /**
     * @var \Tk\Table
     */
    protected $table = null;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct('Available Events');
    }
    
    /**
     *
     * @param Request $request
     * @return \App\Page\Iface|Template|string
     */
    public function doDefault(Request $request)
    {
        $this->table = \Tk\Table::create('EventList');
        $this->table->setParam('renderer', \Tk\Table\Renderer\Dom\Table::create($this->table));

        $this->table->addCell(new \Tk\Table\Cell\Text('name'));
        $this->table->addCell(new \Tk\Table\Cell\Text('value'));
        $this->table->addCell(new \Tk\Table\Cell\Text('eventClass'));
        $this->table->addCell(new \Tk\Table\Cell\Html('doc'))->addCss('key');

        $this->table->addAction(\Tk\Table\Action\Csv::create());

        $list = $this->convertEventData(\App\Factory::getEventDispatcher()->getAvailableEvents(\App\Factory::getConfig()->getSitePath()));
        $this->table->setList($list);

        return $this->show();
    }

    /**
     * @param $eventData
     * @return array
     */
    protected function convertEventData($eventData) {
        $data = array();
        foreach ($eventData as $className => $eventArray) {

            foreach ($eventArray['const'] as $consName => $constData) {
                $data[] = array(
                    'name' => '\\'.$className . '::' . $consName,
                    'value' => $constData['value'],
                    'eventClass' => '\\'.$constData['event'],
                    'doc' => nl2br($constData['doc'])
                );
            }
        }
        return $data;
    }

    /**
     * @return \App\Page\Iface
     */
    public function show()
    {
        $template = $this->getTemplate();

        $template->replaceTemplate('table', $this->table->getParam('renderer')->show());

        return $this->getPage()->setPageContent($template);
    }

    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<XHTML
<div class="row">
  
  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <i class="fa fa-empire fa-fw"></i>
        Available Events
      </div>
      <div class="panel-body">
        <p>The events are available for use with plugins or when adding to the system codebase.</p>
      
         <div var="table"></div>
      </div>
    </div>
  </div>
</div>
XHTML;

        return \Dom\Loader::load($xhtml);
    }

}