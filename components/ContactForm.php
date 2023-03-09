<?php

namespace Spanjaan\ContactForm\Components;

use Cms\Classes\ComponentBase;
use Spanjaan\ContactForm\Models\Settings;
use Spanjaan\ContactForm\Models\ContactForm as ContactFormModel;
use Flash;
use Validator;
use AjaxException;
use Mail;

class ContactForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
         'name' => 'spanjaan.contactform::lang.plugin.name',
         'description' => 'spanjaan.contactform::lang.component.description',

        ];
    }

    public function defineProperties()
    {
        return [
         'nameTitle' => [
          'title' => 'spanjaan.contactform::lang.component.name_title',
          'description' => 'spanjaan.contactform::lang.component.name_description',
          'default' => 'Full Name',
          'type' => 'string',
          'required' => true,
          'validationMessage' => 'Title label required'
         ],
         'emailTitle' => [
          'title' => 'spanjaan.contactform::lang.component.email_title',
          'description' => 'spanjaan.contactform::lang.component.email_description',
          'default' => 'Email',
          'type' => 'string',
          'required' => true,
          'validationMessage' => 'Email label required'
         ],
         'phoneTitle' => [
          'title' => 'spanjaan.contactform::lang.component.phone_title',
          'description' => 'spanjaan.contactform::lang.component.phone_description',
          'default' => 'Phone',
          'type' => 'string'

         ],
         'subjectTitle' => [
          'title' => 'spanjaan.contactform::lang.component.subject_title',
          'description' => 'spanjaan.contactform::lang.component.subject_description',
          'default' => 'Subject',
          'type' => 'string',
          'required' => true,
          'validationMessage' => 'Subject label required'

         ],
         'messageTitle' => [
          'title' => 'spanjaan.contactform::lang.component.message_title',
          'description' => 'spanjaan.contactform::lang.component.message_description',
          'default' => 'Message',
          'type' => 'string',
          'required' => true,
          'validationMessage' => 'Message label required'

         ],
         'buttonText' => [
          'title' => 'spanjaan.contactform::lang.component.button_text',
          'description' => 'spanjaan.contactform::lang.component.button_description',
          'default' => 'Submit',
          'type' => 'string',
          'required' => true,
          'validationMessage' => 'Button text required'

         ],
         'displayPhone' => [
          'title' => 'spanjaan.contactform::lang.component.display_phone_field',
          'description' => 'spanjaan.contactform::lang.component.display_phone_field_description',
          'default' => true,
          'type' => 'checkbox',
         ],
         'detailedErrors' => [
          'title' => 'spanjaan.contactform::lang.component.detailed_errors_field',
          'description' => 'spanjaan.contactform::lang.component.detailed_errors_field_description',
          'default' => false,
          'type' => 'checkbox',
         ],
        ];
    }

    public function properties()
    {
        return [
         'nameLabel' => $this->property('nameTitle', 'Full Name'),
         'emailLabel' => $this->property('emailTitle', 'Email'),
         'phoneLabel' => $this->property('phoneTitle', 'Phone No.'),
         'subjectLabel' => $this->property('subjectTitle', 'Subject'),
         'messageLabel' => $this->property('messageTitle', 'Message'),
         'phoneEnabled' => $this->property('displayPhone', false),
         'buttonText' => $this->property('buttonText', 'Submit'),
         'detailedErrors' => $this->property('detailedErrors', false),
        ];
    }

    public function settings()
    {
        return [
         'recaptcha_enabled' => Settings::get('recaptcha_enabled', false),
         'recaptcha_site_key' => Settings::get('site_key', ''),
        ];
    }
/**
        * Injecting Assets
        */
       public function onRun()
       {
           $this->addJs('/plugins/spanjaan/contactform/assets/js/frontend.js');
           if (Settings::get('recaptcha_enabled', false)) {
           }
       }

    /**
  * AJAX form fubmit handler
  */
  public function onFormSubmit()
  {
      /**
       * Form validation
       */
      $customValidationMessages = [
          'name.required' => trans('spanjaan.contactform::validation.custom.name.required'),
          'email.required' => trans('spanjaan.contactform::validation.custom.email.required'),
          'email.email' => trans('spanjaan.contactform::validation.custom.email.email'),
          'subject.required' => trans('spanjaan.contactform::validation.custom.subject.required'),
          'message.required' => trans('spanjaan.contactform::validation.custom.message.required'),
      ];
      $formValidationRules = [
       'name' => 'required',
       'email' => 'required|email',
       'subject' => 'required',
       'message' => 'required',
   ];

      if (Settings::get('recaptcha_enabled', false)) {
          $formValidationRules['g-recaptcha-response'] = 'required';
      }


      $validator = Validator::make(post(), $formValidationRules, $customValidationMessages);

      if ($validator->fails()) {
          $messages = $validator->messages();
          Flash::error($messages->first());

          throw new AjaxException([
              '#simple_contact_flash_message' => $this->renderPartial('@flashMessage.htm', [
                  'errors' => $messages->all()
              ])
          ]);
      }

      /**
       * Validating reCaptcha
       */
      if (Settings::get('recaptcha_enabled', false)) {
          $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".Settings::get('secret_key')."&response=".post('g-recaptcha-response')."&remoteip=".$_SERVER['REMOTE_ADDR']), true);
          if ($response['success'] == false || $response['score'] < 0.5) {
              Flash::error(e(trans('zainab.simplecontact::validation.custom.reCAPTCHA.required')));
              throw new AjaxException(['#simple_contact_flash_message' => $this->renderPartial('@flashMessage.htm')]);
          }
      }


      /**
       * At this point all validations succeded
       * further processing form
       */

      $this->submitForm();

      $successMessage = "Thank you for contacting us";
      Flash::success($successMessage);

      return ['#simple_contact_flash_message' => $this->renderPartial('@flashMessage.htm')];
  }


    protected function submitForm()
    {
        $model = new ContactFormModel();

        $model->name = post('name');
        $model->email = post('email');
        $model->phone = post('phone');
        $model->subject = post('subject');
        $model->message = post('message');

        $model->save();

        if (Settings::get('recieve_notification', false) && !empty(Settings::get('notification_email_address', ''))) {
            $this->sendNotificationMail($model->id);
        }

        if (Settings::get('auto_reply', false)) {
            $this->sendAutoReply();
        }
    }

    /**
     * Send notification email
     */
    protected function sendNotificationMail($message_id)
    {
        $url_message = Backend::url('spanjaan/contactform/contactform/view/' . $message_id);
        $vars = [
         'url_message' => $url_message,
         'name' => post('name'),
         'email' => post('email'),
         'phone' => post('phone'),
         'subject' => post('subject'),
         'message_body' => post('message')
        ];

        Mail::send('spanjaan.contactform::mail.notification', $vars, function ($message) use ($vars) {
            $message->to(Settings::get('notification_email_address'));
            $message->replyTo($vars['email'], $vars['name']);
        });
    }

    /**
     * send auto reply
     */
    protected function sendAutoReply()
    {
        $vars = [
         'name' => post('name'),
         'email' => post('email'),
         'phone' => post('phone'),
         'subject' => post('subject'),
         'message_body' => post('message')
        ];

        Mail::send('spanjaan.contactform::mail.auto-response', $vars, function ($message) {
            $message->to(post('email'), post('name'));
        });
    }
}
