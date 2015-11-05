<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    protected function respondWithErrorMessage($errorMessage, $errorCode = 400)
    {
        $error['code'] = $errorCode;
        $error['message'] = $errorMessage;
        $r['errors'] = [$error];
        return response()->json($r);
    }
    protected function badRequest(){
        return $this->respondWithErrorMessage('Bad Request');
    }
}
