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
            $locale = 'ar'; // Default to Arabic if validation fails
        }

        // Store the locale in the session for future requests
        Session::put('locale', $locale);
        
        // Set RTL for Arabic
        $isRTL = $locale === 'ar';
        Session::put('rtl', $isRTL);
        
        // Set the application locale for the current request
        App::setLocale($locale);
        
        // Get the previous URL
        $previousUrl = url()->previous();
        
        // If this is an AJAX request, return a JSON response
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
