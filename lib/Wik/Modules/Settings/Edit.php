<?php
/*      -- Generated By Auto Class Builder (c) Tropotek --
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2005 Michael Mifsud
 */

/**
 * Edit:
 * Use the following markup to call this module in the template:
 *   <div var="Ext_Modules_Settings_Edit" com-class="Ext_Modules_Settings_Edit"></div>
 *
 * @package Wik
 */
class Wik_Modules_Settings_Edit extends Com_Web_Component
{

    /**
     * @var Wik_Db_Settings
     */
    private $settings = null;


    /**
     * __construct
     *
     */
    function __construct()
    {
        parent::__construct();
        $this->settings = Wik_Db_Settings::getinstance();

    }

    /**
     * The default init method
     *
     */
    function init()
    {
        $form = Form::create('Settings', $this->settings);
        $form->addDefaultEvents(Tk_Type_Url::create('/index.html'));

        $form->addField(Form_Field_Text::create('title'))->setRequired(true);
        $form->addField(Form_Field_Text::create('siteEmail'))->setNotes('All email from forms will be sent to this address.')->setRequired(true);
        //$form->addField(Form_Field_Text::create('gmapKey'))->setLabel('Google Map Key');
        $form->addField(Form_Field_Textarea::create('contact'))->setNotes('Set the site contact description for the footer')->setHeight(100)->setWidth(400);
        $form->addField(Form_Field_Textarea::create('metaDescription'))->setHeight(100)->setWidth(400)->setNotes('Default page meta tag description');
        $form->addField(Form_Field_Textarea::create('metaKeywords'))->setHeight(100)->setWidth(400)->setNotes('Default Page meta tag keywords');
        $form->addField(Form_Field_Textarea::create('footerScript'))->setHeight(100)->setWidth(400)->setNotes('Useful for adding anylitic scripts');

        $this->setForm($form);

    }

    /**
     * Render the component
     *
     * @param Dom_Template $template
     */
    function show()
    {
        $template = $this->getTemplate();


    }

}


