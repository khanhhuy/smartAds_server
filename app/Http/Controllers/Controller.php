<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    protected function respondWithErrorMessage($errorMessage){
        $error['message'] = $errorMessage;
        $r['errors'] = [$error];
        return response()->json($r);
    }
    protected function badRequest(){
        return $this->respondWithErrorMessage('Bad Request');
    }
}
