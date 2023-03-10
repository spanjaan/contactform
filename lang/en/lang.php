<?php

return [
 'plugin' => [
  'name' => 'Sp Contact',
  'description' => 'A simple contact us Form By S.p. Anjaan',
 ],
 'contactform' => [
  'from' => 'From',
  'subject' => 'Subject',
  'name' => 'Name',
  'email' => 'Email',
  'status' => 'Status',
  'date' => 'Date',
  'mainmenu' => 'My Message',
  'submenu' => 'Inbox',
  'permission' => 'Sp ContactForm',
  'permission_messages' => 'Messages',
  'permission_settings' => 'Settings',
  'phone' => 'Phone',
  'message' => 'Message',
  'reply' => 'Reply',
  'print' => 'Print',
  'print_message' => 'Print Message',
  'back' => 'Back',
  'message_delete_success' => 'Successfully deleted the message',
  'message_delete_error' => 'Something went wrong, unable to delete.',
  'message_not_found_error' => 'Could not find what you are looking for!',
  'message_reply_error' => 'Something went wrong, unable to send reply.',
  'message_reply_success' => 'Message send successfully.',
  'message_reply' => 'Reply Message',
  'button_submit_reply' => 'Send',
  'button_close_reply' => 'Close',
 ],
 'settings' => [
  'section_mail_label' => 'Mail Settings',
  'notification_label' => 'Notification',
  'notification_comment' => 'Send mail notification to you when contact form submitted',
  'notification_email_address' => 'Notification Email Address',
  'notification_email_address_comment' => 'Email address where notification mail will be sent',
  'auto_reply_label' => 'Auto Reply',
  'auto_reply_comment' => 'Send auto reply mail to user who submitted contact us form.',
  'section_recaptcha_label' => 'reCAPTCHA Settings',
  'section_recaptcha_comments' => 'Show or Hide reCAPTCHA on contact us form',
  'recaptcha_label' => 'reCAPTCHA',
  'recaptcha_comment' => 'Display reCAPTCHA widget on the form',
  'site_key_label' => 'Site Key',
  'site_key_comment' => 'Your site key provided by google ',
  'secret_key_label' => 'Secret Key',
  'secret_key_comment' => 'Your secret key provided by google ',
 ],
 'component' => [
  'name' => 'Sp Contact Form',
  'description' => 'Add contact us form to page',
  'name_title' => 'Name field label',
  'name_description' => 'Name label will appear above the field(required)',
  'name_validation_message' => '',
  'email_title' => 'Email field label',
  'email_description' => 'Email label will appear above the field(required)',
  'email_validation_message' => '',
  'phone_title' => 'Phone field label',
  'phone_description' => 'Phone label will appear above the field',
  'subject_title' => 'Subject field label',
  'subject_description' => 'Subject label will appear above the field(required)',
  'subject_validation_message' => '',
  'message_title' => 'Message field label',
  'message_description' => 'Message label will appear above the field(required)',
  'message_validation_message' => '',
  'display_phone_field' => 'Show Phone Field',
  'display_phone_field_description' => 'Show phone field in the form',
  'detailed_errors_field' => 'Detailed Errors',
  'detailed_errors_field_description' => 'Show all validation errors using a list',
  'button_text' => 'Form submit button text',
  'button_description' => 'Form submit button text(required)',
  'button_validation_message' => '',
 ]
];
