<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;

class PrevalidationPassedException extends Exception {

    public function report() : bool {
        return true;
    }

    public function render() : RedirectResponse {
        return redirect()->back();
    }
}
