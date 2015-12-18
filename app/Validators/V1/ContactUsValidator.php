<?php

namespace Tajrish\Validators\V1;

class ContactUsValidator extends AbstractValidator
{
    protected $customAttributes = [
        'name' => 'نام و نام خانوادگی',
        'email' => 'ایمیل',
        'phone_number'=> 'شماره تماس',
        'subject' => 'موضوع',
        'description' => 'متن'
    ];

    protected $rules = array (
        'name' => 'required',
        'email' => 'required|email',
        'phone_number' => 'integer',
        'subject' => 'required',
        'description' => 'required',
    );


}