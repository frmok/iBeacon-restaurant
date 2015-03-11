<?php
namespace App\Services;
use Illuminate\Support\Facades\Facade;
class PushFacade extends Facade {

    protected static function getFacadeAccessor() { return 'push'; }

}

?>