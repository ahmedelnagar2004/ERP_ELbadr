<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    /**
     * Change the application language.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function switchLang($locale, Request $request)
    {
        // Validate the locale
        $validator = Validator::make(['locale' => $locale], [
            'locale' => 'required|in:en,ar',
        ]);

        if ($validator->fails()) {
            $locale = 'ar';
        }

        Session::put('locale', $locale);

        $isRTL = $locale === 'ar';
        Session::put('rtl', $isRTL);

        App::setLocale($locale);

        $previousUrl = url()->previous();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => __('Language changed successfully'),
                'rtl' => $isRTL,
                'redirect' => $previousUrl
            ]);
        }

        // For regular requests, redirect back with a success message
        return redirect()->back()->with('success', __('Language changed successfully'));
    }
}
