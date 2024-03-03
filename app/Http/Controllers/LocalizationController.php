<?php

namespace App\Http\Controllers;




class LocalizationController extends Controller
{
    //

    public function __invoke($locale){
       
        if(!in_array($locale, ['en', 'it'])){

            abort(400);
        }

        session(['localization' => $locale]);

        return redirect()->back();
    }
}
