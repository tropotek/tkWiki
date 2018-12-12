<?php
namespace App\Controller;

use Tk\Request;
use Tk\Form;
use Tk\Form\Event;
use Tk\Form\Field;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Contact extends Iface
{

    /**
     * @var Form
     */
    protected $form = null;


    public function __construct()
    {
        $this->setPageTitle('Contact Us');
    }

    /**
     * @param Request $request
     * @throws \Exception
     */
    public function doDefault(Request $request)
    {
        $this->form = new Form('contactForm');

        $this->form->appendField(new Field\Input('name'));
        $this->form->appendField(new Field\Input('email'));

        $opts = new Field\Option\ArrayIterator(array('General', 'Services', 'Orders'));
        $this->form->appendField(new Field\Select('type[]', $opts));

        $this->form->appendField(new Field\File('attach', '/contact/' . date('d-m-Y') . '-___'));
        $this->form->appendField(new Field\Textarea('message'));

        if ($this->getConfig()->get('google.recaptcha.publicKey'))
            $this->form->appendField(new Field\ReCapture('capture', $this->getConfig()->get('google.recaptcha.publicKey'),
                $this->getConfig()->get('google.recaptcha.privateKey')));

        $this->form->appendField(new Event\Submit('send', array($this, 'doSubmit')));

        $this->form->execute();
    }

    /**
     * @return \Dom\Template
     * @throws \Exception
     */
    public function show()
    {
        $template = parent::show();

        // Render the form
        $ren = new \Tk\Form\Renderer\DomStatic($this->form, $template);
        $ren->show();

        return $template;
    }

    /**
     * @param Form $form
     * @throws \Exception
     */
    public function doSubmit($form)
    {
        $values = $form->getValues();
        /** @var Field\File $attach */
        $attach = $form->getField('attach');

        if (empty($values['name'])) {
            $form->addFieldError('name', 'Please enter your name');
        }
        if (empty($values['email']) || !filter_var($values['email'], \FILTER_VALIDATE_EMAIL)) {
            $form->addFieldError('email', 'Please enter a valid email address');
        }
        if (empty($values['message'])) {
            $form->addFieldError('message', 'Please enter some message text');
        }

        // validate any files
        $attach->isValid();

        if ($this->form->hasErrors()) {
            return;
        }
//        if ($attach->hasFile()) {
//            $attach->moveFile($this->getConfig()->getDataPath() . '/contact/' . date('d-m-Y') . '-' . str_replace('@', '_', $values['email']));
//        }

        if ($this->sendEmail($form)) {
            \Tk\Alert::addSuccess('<strong>Success!</strong> Your form has been sent.');
        } else {
            \Tk\Alert::addError('<strong>Error!</strong> Something went wrong and your message has not been sent.');
        }

        \Tk\Uri::create()->redirect();
    }


    /**
     * @param Form $form
     * @return bool
     * @throws \Tk\Exception
     * @throws \Exception
     */
    private function sendEmail($form)
    {
        $name = $form->getFieldValue('name');
        $email = $form->getFieldValue('email');
        $type = '';
        if (is_array($form->getFieldValue('type')))
            $type = implode(', ', $form->getFieldValue('type'));
        $messageStr = $form->getFieldValue('message');

        $attachCount = '';
        /** @var Field\File $field */
        $field = $form->getField('attach');
        if ($field->hasFile()) {
            $attachCount = '<br/><b>Attachments:</b> ';
            foreach ($field->getUploadedFiles() as $file) {
                $attachCount = $file->getFilename() . ', ';
            }
            $attachCount = rtrim($attachCount, ', ');
        }

        $content = <<<MSG
<p>
Dear $name,
</p>
<p>
Email: $email<br/>
Type: $type
</p>
<p>Message:<br/>
  $messageStr
</p>
<p>
$attachCount
</p>
MSG;

        $message = $this->getConfig()->createMessage();
        $message->addTo($email);
        $message->setSubject($this->getConfig()->get('site.title') . ':  Contact Form Submission - ' . $name);
        $message->set('content', $content);
        if ($field->hasFile()) {
            $message->addAttachment($field->getUploadedFile()->getFile(), $field->getUploadedFile()->getFilename());
        }
        return $this->getConfig()->getEmailGateway()->send($message);
    }

}