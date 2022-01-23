<?php

namespace Sammyjo20\Wagonwheel\Tests\Mail;

use Illuminate\Mail\Mailable;
use Sammyjo20\Wagonwheel\Concerns\SaveForOnlineViewing;

class ExampleMail extends Mailable
{
    use SaveForOnlineViewing;

    public function build()
    {
        return $this->view('wagonwheel-tests::mails.example-mail');
    }
}
