<?php
namespace App\Controller;

use Tk\Form;
use Tk\Form\Field;
use Tk\Form\Event;
use Tk\Request;


/**
 * Class Index
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Recover extends Iface
{

    /**
     * @var Form
     */
    protected $form = null;

    /**
     * @var \Tk\EventDispatcher\EventDispatcher
     */
    private $dispatcher = null;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct('Recover Password');
        $this->dispatcher = $this->getConfig()->getEventDispatcher();
    }

    /**
     * @param Request $request
     * @return \App\Page\Iface
     */
    public function doDefault(Request $request)
    {
        $this->form = new Form('loginForm', $request);

        $this->form->addField(new Field\Input('account'));
        $this->form->addField(new Event\Button('recover', array($this, 'doRecover')));

        // Find and Fire submit event
        $this->form->execute();

        return $this->show();
    }

    public function doRecover($form)
    {
        
        if (!$form->getFieldValue('account')) {
            $form->addFieldError('account', 'Please enter a valid username or email');
        }
        
        if ($form->hasErrors()) {
            return;
        }
        
        // TODO: This should be made a bit more secure for larger sites.
        
        $account = $form->getFieldValue('account');
        /** @var \App\Db\User $user */
        $user = null;
        if (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $user = \App\Db\UserMap::create()->findByEmail($account);
        } else {
            $user = \App\Db\UserMap::create()->findByUsername($account);
        }
        if (!$user) {
            $form->addFieldError('account', 'Please enter a valid username or email');
            return;
        }

        $newPass = $user->createPassword();
        $user->password = \App\Factory::hashPassword($newPass);
        $user->save();
        
        // Fire the login event to allow developing of misc auth plugins
        $event = new \App\Event\FormEvent($form);
        $event->set('user', $user);
        $event->set('password', $newPass);
        $event->set('templatePath', $this->getTemplatePath());
        
        $this->dispatcher->dispatch('auth.onRecover', $event);
        
        \App\Alert::addSuccess('You new access details have been sent to your email address.');
        \Tk\Uri::create()->redirect();
        
    }


    public function show()
    {
        $template = $this->getTemplate();
        
        if ($this->getConfig()->get('site.user.registration')) {
            $template->setChoice('register');
        }
        
        return $this->getPage()->setPageContent($template);
    }


    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $tplFile = $this->getTemplatePath().'/xtpl/recover.xtpl';
        return \Dom\Loader::loadFile($tplFile);
    }

}