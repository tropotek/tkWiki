<?php
namespace App\Controller\Page;

use Tk\Request;
use Dom\Template;
use Tk\Form\Field;
use App\Controller\Iface;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Orphaned extends Iface
{

    /**
     * @var \Tk\Table
     */
    protected $table = null;


    /**
     * @param Request $request
     * @throws \Exception
     */
    public function doDefault(Request $request)
    {
        $this->setPageTitle('Orphaned Page Manager');

        $this->table = \Tk\Table::create('tableOne');

        $this->table->appendCell(new \Tk\Table\Cell\Checkbox('id'));
        $this->table->appendCell(new \Tk\Table\Cell\Text('id'));
        $this->table->appendCell(new \Tk\Table\Cell\Text('title'))->addCss('key')->setUrl(\Tk\Uri::create('/user/edit.html'));
        $this->table->appendCell(new \Tk\Table\Cell\Text('userId'));
        $this->table->appendCell(new \Tk\Table\Cell\Text('type'));
        $this->table->appendCell(new \Tk\Table\Cell\Text('url'));
        $this->table->appendCell(new \Tk\Table\Cell\Text('permission'));
        $this->table->appendCell(new \Tk\Table\Cell\Text('views'));
        $this->table->appendCell(new \Tk\Table\Cell\Date('modified'));
        $this->table->appendCell(new \Tk\Table\Cell\Date('created'));

        // Filters
        $this->table->appendFilter(new Field\Input('keywords'))->setLabel('')->setAttr('placeholder', 'Keywords');

        // Actions
        //$this->table->appendAction(\Tk\Table\Action\Button::getInstance('New Page', 'fa fa-plus', \Tk\Uri::create('/edit.html')));
        $this->table->appendAction(new \Tk\Table\Action\Delete());
        $this->table->appendAction(new \Tk\Table\Action\Csv($this->getConfig()->getDb()));

        //$filter = $this->table->getFilterValues();
        $list = \App\Db\PageMap::create()->findOrphanedPages($this->table->getTool('title'));
        $this->table->setList($list);

    }

    /**
     * @return Template
     */
    public function show()
    {
        $template = parent::show();

        $ren =  \Tk\Table\Renderer\Dom\Table::create($this->table);
        $template->appendTemplate('table', $ren->show());

        return $template;
    }

    /**
     * DomTemplate magic method
     *
     * @return Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<XHTML
<div class="row">

  <div class="col-lg-12">
    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-th-list"></i> Pages</div>
      <div class="panel-body " var="table">
      </div>
    </div>
  </div>

</div>
XHTML;

        return \Dom\Loader::load($xhtml);
    }

}