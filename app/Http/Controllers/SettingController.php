<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Currency;
use File;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_auth = auth()->user();
        if ($user_auth->can('settings')) {

            $setting_data = Setting::where('deleted_at', '=', null)->first();

            $email_settings['host'] = env('MAIL_HOST');
            $email_settings['port'] = env('MAIL_PORT');
            $email_settings['username'] = env('MAIL_USERNAME');
            $email_settings['password'] = env('MAIL_PASSWORD');
            $email_settings['encryption'] = env('MAIL_ENCRYPTION');
            $email_settings['from_email'] = env('MAIL_FROM_ADDRESS');
            $email_settings['from_name'] = env('MAIL_FROM_NAME');

            $currencies = Currency::where('deleted_at', '=', null)->orderBy('id', 'desc')->get(['id', 'name']);
            $setting['id'] = $setting_data->id;
            $setting['email'] = $setting_data->email;
            $setting['CompanyName'] = $setting_data->CompanyName;
            $setting['CompanyPhone'] = $setting_data->CompanyPhone;
            $setting['CompanyAdress'] = $setting_data->CompanyAdress;
            $setting['logo'] = "";


            $setting['footer'] = $setting_data->footer;
            $setting['developed_by'] = $setting_data->developed_by;
            $setting['currency_id'] = $setting_data->currency_id;
            $setting['default_language'] = $setting_data->default_language;
            return view('settings.system_settings.system_settings_list', compact('setting', 'email_settings', 'currencies'));
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_auth = auth()->user();
        if ($user_auth->can('settings')) {

            $request->validate([
                'CompanyName'      => 'required|string|max:255',
                'CompanyPhone'     => 'nullable|numeric',
                'email'            => 'required|string|email|max:255',
                'CompanyAdress'    => 'required|string',
                'developed_by'     => 'required|string|max:255',
                'footer'           => 'required|string|max:255',
                'logo'             => 'nullable|image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',
                'currency_id'      => 'required',
            ]);

            $setting = Setting::findOrFail($id);
            $currentAvatar = $setting->logo;

            if ($request->logo != null) {
                if ($request->logo != $currentAvatar) {

                    // dd($request->logo);

                    $image = $request->file('logo');
                    $filename = time() . '.' . $image->extension();
                    $image->move(public_path('/assets/images/'), $filename);
                    $path = public_path() . '/assets/images/';

                    $userPhoto = $path . '/' . $currentAvatar;
                    if (file_exists($userPhoto)) {
                        if ($setting->logo != 'no_avatar.png') {
                            @unlink($userPhoto);
                        }
                    }
                } else {
                    $filename = $currentAvatar;
                }
            } else {
                $filename = $currentAvatar;
            }




            if ($request['currency_id'] != 'null') {
                $currency = $request['currency_id'];
            } else {
                $currency = null;
            }

            if ($request['default_language'] != 'null') {
                $default_language = $request['default_language'];
            } else {
                $default_language = 'en';
            }
            Setting::whereId($id)->update([
                'currency_id' => $currency,
                'email' => $request['email'],
                'CompanyName' => $request['CompanyName'],
                'CompanyPhone' => $request['CompanyPhone'],
                'CompanyAdress' => $request['CompanyAdress'],
                'footer' => $request['footer'],
                'developed_by' => $request['developed_by'],
                'logo' => $filename,
            ]);

            return response()->json(['success' => true]);
        }
        return abort('403', __('You are not authorized'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //-------------- Business setting ------------------\\

    public function business_settings()
    {
        $setting = Setting::where('id', 1)->first();

        return view('settings.business_settings.business_setting', compact('setting'));
    }

    public function update_business_settings(Request $request, $id)
    {
        $setting = Setting::findOrFail($id);
        $currentAvatar = $setting->logo;
       
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            if ($request->file('logo')->getClientOriginalName() != $setting->logo) {
                $image = $request->file('logo');
                $filename = time() . '.' . $image->extension();
                $image->move(public_path('/assets/images/'), $filename);
                $path = public_path() . '/assets/images/';
        
                $userPhoto = $path . '/' . $setting->logo;
                if (file_exists($userPhoto)) {
                    @unlink($userPhoto);
                }
                $setting->logo = $filename;
            }
        }
        

        if ($request->hasFile('dark_logo') && $request->file('dark_logo')->isValid()) {
            if ($request->file('dark_logo')->getClientOriginalName() != $setting->dark_logo) {
                $image = $request->file('dark_logo');
                $filename = time() . '.' . $image->extension();
                $image->move(public_path('/assets/images/'), $filename);
                $path = public_path() . '/assets/images/';
        
                $userPhoto = $path . '/' . $setting->dark_logo;
                if (file_exists($userPhoto)) {
                    @unlink($userPhoto);
                }
                $setting->dark_logo = $filename;
            }
        }
        
        
        // dd($request);
        if ($request->hasFile('favicon') && $request->file('favicon')->isValid()) {
            if ($request->file('favicon')->getClientOriginalName() != $setting->favicon) {
                $image = $request->file('favicon');
                $filename = time() . '.' . $image->extension();
                $image->move(public_path('/assets/images/'), $filename);
                $path = public_path() . '/assets/images/';
        
                $userPhoto = $path . '/' . $setting->favicon;
                if (file_exists($userPhoto)) {
                    @unlink($userPhoto);
                }
                $setting->favicon = $filename;
            }
        }
        
        $setting->theme_color = $request->theme_color;
        $setting->save();

        return response()->json(['status' => 'success', 'code' => 200]);
    }

    //-------------- Clear_Cache ---------------\\

    public function Clear_Cache(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
    }
}
