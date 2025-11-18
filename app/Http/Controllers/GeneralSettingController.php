<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Http\Requests\admin\StoreSettingRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class GeneralSettingController extends Controller
{
   public function edit(){
    $setting = Setting::first();
    return view ('admin.general_settings.edit', compact('setting'));
   }

   public function update(StoreSettingRequest $request){

     $setting->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'logo' => $request->logo->store('settings', 'public'),
        ]);
    return redirect()->route('admin.general_settings.edit')->with('success', 'تم تحديث الإعدادات بنجاح');
    
    
   }

}
