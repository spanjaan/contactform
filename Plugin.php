<?php

namespace Spanjaan\ContactForm;

use System\Classes\PluginBase;
use Backend;
use Spanjaan\ContactForm\Controllers\ContactForm;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
         'name' => 'spanjaan.contactform::lang.plugin.name',
         'description' => 'spanjaan.contactform::lang.plugin.description',
         'author' => 'S.p. Anjaan',
         'icon' => 'icon-envelope'
        ];
    }

    /**
     * @var array Plugin dependencies
     */

    public function registerComponents()
    {
        return [
         'Spanjaan\ContactForm\Components\ContactForm' => 'ContactForm'
        ];
    }

    public function registerSettings()
    {
        return [
         'config' => [
          'label' => 'ContactForm',
          'icon' => 'icon-envelope',
          'description' => 'Sp Contact Form General Setting',
          'category' => 'ContactForm',
          'class' => 'Spanjaan\ContactForm\Models\Settings',
          'permissions' => ['spanjaan.contactform.manage_settings'],
          'keywords' => 'ContactForm',
          'order' => 500
         ]
        ];
    }

    public function registerNavigation()
    {
        return [
         'main-menu-item' => [
          'label' => 'spanjaan.contactform::lang.contactform.mainmenu',
          'url' => Backend::url('spanjaan/contactform/contactform'),
          'icon' => 'icon-envelope',
          'permissions' => ['spanjaan.contactform.inbox'],


          'sideMenu' => [
           'side-menu-item' => [
            'label' => 'spanjaan.contactform::lang.contactform.submenu',
            'icon' => 'icon-envelope-open',
            'url' => Backend::url('spanjaan/contactform/contactform'),
            'permissions' => ['spanjaan.contactform.inbox'],
            'counter' => ContactForm::countUnreadMessages(),
            'counterLabel' => 'Un-Read Messages'
           ]

          ]

         ]
        ];
    }

    public function registerMailTemplates()
    {
        return [
         'spanjaan.contactform::mail.reply' => 'Reply message to Sender',
         'spanjaan.contactform::mail.auto-response' => 'Auto response message send to Sender',
         'spanjaan.contactform::mail.notification' => 'Notification sent to the administrator upon contact form submission.',
        ];
    }
}
