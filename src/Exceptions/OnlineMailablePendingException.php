<?php

namespace Sammyjo20\Jockey\Exceptions;

use Exception;

class OnlineMailablePendingException extends Exception
{
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function render()
    {
        return view('jockey::online-mailable-pending');
    }
}
