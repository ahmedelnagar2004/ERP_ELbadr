<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateGeneralSettingsRequest;
use App\Settings\CompanySettings;
use App\Settings\SalesSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GeneralSettingsController extends Controller
{
    public function edit(CompanySettings $settings, SalesSettings $salesSettings): View
    {
        return view('admin.settings.index', [
            'setting' => $settings,
            'salesSettings' => $salesSettings
        ]);
    }

    public function update(UpdateGeneralSettingsRequest $request, CompanySettings $settings, SalesSettings $salesSettings): RedirectResponse
    {
        // Handle logo upload if provided
        $logoPath = $settings->logo;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        $settings->name = $request->string('name');
        $settings->email = $request->string('email');
        $settings->phone = $request->string('phone');
        $settings->address = $request->string('address');
        $settings->logo = $logoPath;
        $settings->save();

        // Save Sales Settings
        $salesSettings->allow_decimal_quantities = $request->has('allow_decimal_quantities');
        $salesSettings->default_discount_type = $request->input('default_discount_type', 'fixed');
        $salesSettings->enabled_payment_methods = $request->input('enabled_payment_methods', []);
        $salesSettings->save();

        return redirect()->route('admin.settings.edit')->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}


