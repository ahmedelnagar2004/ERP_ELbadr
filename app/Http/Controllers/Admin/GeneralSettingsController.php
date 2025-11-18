<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateGeneralSettingsRequest;
use App\Settings\CompanySettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GeneralSettingsController extends Controller
{
    public function edit(CompanySettings $settings): View
    {
        return view('admin.general_settings.edit', ['setting' => $settings]);
    }

    public function update(UpdateGeneralSettingsRequest $request, CompanySettings $settings): RedirectResponse
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

        return redirect()->route('admin.general_settings.edit')->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}


