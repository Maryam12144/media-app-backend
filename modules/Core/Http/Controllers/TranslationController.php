<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class TranslationController
 * @package Zix\Core\Http\Controllers
 */
class TranslationController
{

    public function index(Request $request)
    {
        return response()->json(trans($request->get('group')));
    }
}