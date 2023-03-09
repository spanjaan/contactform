<?php

namespace Spanjaan\ContactForm\Controllers;

use Backend\Classes\Controller;
use Backend\Facades\Backend;
use BackendMenu;
use Spanjaan\ContactForm\Models\ContactForm as ContactFormModel;
use Flash;
use Mail;
use Validator;
use ValidationException;
use Illuminate\Support\Facades\Redirect;
use System\Classes\SettingsManager;

class ContactForm extends Controller
{
    public $requiredPermissions = ['spanjaan.contactform.inbox'];

    public $implement = ['Backend\Behaviors\ListController'];

    public $listConfig = 'config_list.yaml';



    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('spanjaan.contactform', 'main-menu-item');
        SettingsManager::setContext('spanjaan.contactform', 'contactform');

        // Include backend styles
        $this->addCss("/plugins/spanjaan/contactform/assets/css/backend-list.css");
    }


    /**
         * Injecting css class 'new' to list row if its new record
         *
         * @param $record
         * @param null $definition
         * @return string
         */
    public function listInjectRowClass($record, $definition = null)
    {
        if ($record->is_new) {
            return 'new';
        }
    }

    /**
     * count unread messages to show
     * counter on the side menu
     */
    public static function countUnreadMessages()
    {
        $counter = ContactFormModel::where('is_new', true)->count();

        if ($counter > 0) {
            return $counter;
        } else {
            return null;
        }
    }


    /**
     * (AJAX Call)
     * Delete items from the list.
     *
     */
    public function onDelete()
    {
        /** Check if this is even set **/


        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            ContactFormModel::whereIn('id', $checkedIds)->delete();
        }

        $counter = ContactFormModel::where('is_new', true)->count();

        return [
         'counter' => $counter,
         'list' => $this->listRefresh()
        ];
    }


    /**
     * (AJAX Call)
     * Delete single message
     *
     * @return mixed
     */
    public function onDeleteSingle()
    {
        $id = post('id');
        $record = ContactFormModel::find($id);

        if ($record) {
            $record->delete();
            Flash::success(e(trans('spanjaan.contactform::lang.contactform.message_delete_success')));
        } else {
            Flash::error(e(trans('spanjaan.contactform::lang.contactform.message_delete_error')));
        }


        return Redirect::to(Backend::url('spanjaan/contactform/contactform'));
    }

    /**
     * send message reply
     */
    public function onReplyMessage()
    {
        $formValidationRules = [
            'subject' => ['required'],
            'message' => ['required']
        ];

        $validator = Validator::make(post(), $formValidationRules);

        // Validate
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $record = ContactFormModel::find(post('id'));

        if ($record) {
            $vars = [
                'message_body' => nl2br(post('message'))
            ];

            Mail::send('spanjaan.contactform::mail.reply', $vars, function ($message) {
                $message->to(post('email_to'), post('name_to'));
                $message->subject(post('subject'));
            });

            $record->is_replied = true;
            $record->cssClass = 'replied'; // add replied class to cssClass attribute
            $record->save();

            Flash::success(e(trans('spanjaan.contactform::lang.contactform.message_reply_success')));
        } else {
            Flash::error(e(trans('spanjaan.contactform::lang.contactform.message_reply_error')));
        }

        return redirect()->refresh();
    }


    /**
         * view single message details
         *
         * @param $id
         */
    public function view($id)
    {
        $message = ContactFormModel::find($id);
        if ($message) {
            $message->is_new = false;
            $message->save();

            $this->addCss("/plugins/spanjaan/contactform/assets/css/backend-custom.css");
            $this->addJs("/plugins/spanjaan/contactform/assets/js/print.min.js");

            $this->pageTitle = "Message";
            $this->vars['message'] = $message;
        } else {
            Flash::error(e(trans('spanjaan.contactform::lang.contactform.message_not_found_error')));
            return Redirect::to(Backend::url('spanjaan/contactform/contactform'));
        }
    }
}
