<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @return array
     */
    protected function userInfo()
    {
        $data = ['auth' => ['status' => false]];

        if (\Auth::check()) {
            $data['auth']['status']     = true;
            $data['auth']['userInfo']   = \Auth::user()->toArray();
        }

        return $data;
    }
}
