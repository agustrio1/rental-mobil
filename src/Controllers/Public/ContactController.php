<?php

namespace App\Controllers\Public;

class ContactController
{
  public function index(): void {
    view('public.contact.index', ['settings' => settings(), 'seo' ]);
  }
}