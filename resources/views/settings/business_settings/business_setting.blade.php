<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>

@extends('layouts.master')
@section('main-content')


<div class="breadcrumb">
    <h1>{{ __('translate.Settings') }}</h1>
    <ul>
        <li><a href="/settings/Business_settings">{{ __('translate.Business_settings') }}</a></li>
        <li>{{ __('translate.Settings') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<section id="section_System_Settings_list">
    {{-- System_Settings --}}
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <span>{{ __('translate.Business_settings') }}</span>
                </div>
                <!--begin::form-->
                <div class="card-body">
                    <form @submit.prevent="Update_Settings()" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-md-4 my-2">
                                <div class="card" style="box-shadow: 0 4px 20px 1px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.06); height: 18rem;">
                                    <div class="card-header bg-{{$setting->theme_color}}">
                                        <h5 class="text-white">{{ __('translate.Logo_Dark') }}</h5>
                                    </div>
                                    <div class="card-body pt-0 d-flex flex-column position-relative">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4 setting-logo">
                                                <a href="{{ asset('/assets/images/' . $setting->dark_logo ) }}"
                                                    target="_blank">
                                                    <img id="image" alt="your image"
                                                        src="{{ asset('/assets/images/' . $setting->dark_logo ) }}"
                                                        width="100px" height="100px" class="big-logo">
                                                </a>
                                            </div>
                                            <div class="choose-files input_cont_images">

                                                <label for="logo" class="ul-form__label">{{ __('translate.Change_Logo') }} </label>
                                                <input name="dark_logo" @change="changeDarkLogo" type="file" class="form-control" id="dark_logo">
                                                <span class="error" v-if="errors_settings && errors_settings.logo">
                                                    @{{ errors_settings.logo[0] }}
                                                </span>

                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 my-2">
                                <div class="card" style="box-shadow: 0 4px 20px 1px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.06); height: 18rem;">
                                    <div class="card-header bg-{{$setting->theme_color}}">
                                        <h5 class="text-white">{{ __('translate.Logo_Light') }}</h5>
                                    </div>

                                    <div class="card-body pt-0 d-flex flex-column position-relative">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4  setting-logo">
                                              
                                                <a href="{{ asset('/assets/images/' . $setting->logo ) }}"
                                                    target="_blank">
                                                    <img id="image1" alt="your image"
                                                        src="{{ asset('/assets/images/' . $setting->logo ) }}"
                                                        width="100px" height="100px"
                                                        class="big-logo"style="">
                                                </a>

                                            </div>
                                            <div class="choose-files input_cont_images">
                                                <label for="logo" class="ul-form__label">{{ __('translate.Change_Logo') }} </label>
                                                <input name="logo" @change="changeLogo" type="file" class="form-control" id="logo">
                                                <span class="error" v-if="errors_settings && errors_settings.logo">
                                                    @{{ errors_settings.logo[0] }}
                                                </span>
                                            </div>
                                            @error('company_logo_light')
                                                <div class="row">
                                                    <span class="invalid-company_logo_light" role="alert">
                                                        <strong
                                                            class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 my-2">
                                <div class="card" style="box-shadow: 0 4px 20px 1px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.06); height: 18rem;">
                                    <div class="card-header bg-{{$setting->theme_color}}">
                                        <h5 class="text-white">{{ __('Favicon') }}</h5>
                                    </div>
                                    <div class="card-body pt-0 d-flex flex-column position-relative">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4 setting-logo ">
                                             
                                                <a href="{{ asset('/assets/images/' . $setting->favicon ) }}"
                                                    target="_blank">
                                                    <img id="image2" alt="your image"
                                                        src="{{ asset('/assets/images/' . $setting->favicon ) }}"
                                                        width="100px"
                                                        height="100px"
                                                        class="big-logo">
                                                </a>
                                            </div>
                                            <div class="choose-files input_cont_images">

                                                <label for="company_favicon">
                                                    <label for="logo" class="ul-form__label">{{ __('translate.Change_Logo') }} </label>
                                                    <input name="favicon" @change="changeFavicon" type="file" class="form-control" id="favicon">
                                                    <span class="error" v-if="errors_settings && errors_settings.logo">
                                                        @{{ errors_settings.logo[0] }}
                                                    </span>
                                                </label>
                                            </div>
                                            @error('company_favicon')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong
                                                            class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 my-2">
                                <div class="card" style="box-shadow: 0 4px 20px 1px rgba(0, 0, 0, 0.06), 0 1px 4px rgba(0, 0, 0, 0.06); height: 18rem;">
                                    <div class="card-header bg-{{$setting->theme_color}}">
                                        <h5 class="text-white">{{ __('translate.Background_Image') }}</h5>
                                    </div>
                                    <div class="card-body pt-0 d-flex flex-column position-relative">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4 setting-logo ">
                                             
                                                <a href="{{ asset('/assets/images/' . $setting->background_image ) }}"
                                                    target="_blank">
                                                    <img id="image2" alt="your image"
                                                        src="{{ asset('/assets/images/' . $setting->background_image ) }}"
                                                        width="100px"
                                                        height="100px"
                                                        class="big-logo">
                                                </a>
                                            </div>
                                            <div class="choose-files input_cont_images">

                                                <label for="company_favicon">
                                                    <label for="background_image" class="ul-form__label">{{ __('translate.Change_Logo') }} </label>
                                                    <input name="background_image" @change="Change_background_image" type="file" class="form-control" id="background_image">
                                                    <span class="error" v-if="errors_settings && errors_settings.background_image">
                                                        @{{ errors_settings.logo[0] }}
                                                    </span>
                                                </label>
                                            </div>
                                            @error('company_favicon')
                                                <div class="row">
                                                    <span class="invalid-logo" role="alert">
                                                        <strong
                                                            class="text-danger">{{ $message }}</strong>
                                                    </span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <div class="pct-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <h5 class="mt-3 mb-3">{{ __('translate.Theme Customizer') }}</h5>
                                            <h6 class="">
                                                <i data-feather="credit-card"
                                                    class="me-2"></i>{{ __('translate.Primary color Settings') }}
                                            </h6>
                                            <hr class="my-2" />
                                            <div class="theme-color themes-color d-flex flex-wrap" style="width: 100%;">
                                                @foreach(['primary', 'indigo', 'pink', 'danger', 'orange', 'warning', 'success', 'teal', 'cyan', 'gray', 'dark', 'info', 'light'] as $color)
                                                    <a class="bg-{{ $color }} cursor-pointer color_themes m-1 {{ $setting->theme_color ==  $color ? 'checked_theme_color' : '' }}" onclick="changeTheme(this)" style="height: 2rem; width: 2rem; border-radius: 0.5rem; display: inline-block;">
                                                     <input type="radio" value="{{ $color }}" name="theme_color" v-model="setting.theme_color" {{ $setting->theme_color == 'bg-' . $color ? 'checked' : '' }} hidden>
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-12">
                                            <h5 class="mt-3 mb-3">{{ __('translate.Theme Mode') }}</h5>
                                            <h6 class="">
                                                <i data-feather="credit-card"
                                                    class="me-2"></i>
                                            </h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_dark_mode" id="flexSwitchCheckDefault" :checked="setting.is_dark_mode == 1">
                                                <label class="form-check-label" for="flexSwitchCheckDefault">{{ __('translate.Enable Dark Mode') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-{{$setting->theme_color}}" :disabled="SubmitProcessing">
                                    {{ __('translate.Submit') }}
                                </button>
                                <div v-once class="typo__p" v-if="SubmitProcessing">
                                    <div class="spinner spinner-primary mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</section>

@endsection

@section('page-js')

<script>
    Vue.component('v-select', VueSelect.VueSelect)

        var app = new Vue({
        el: '#section_System_Settings_list',
        data: {
            data: new FormData(),
            SubmitProcessing:false,
            Submit_Processing_Clear_Cache:false,
            Submit_Processing_Email_Setting:false,
            errors_settings:[],
            errors_email_setting:[],
            setting: @json($setting),
        },
       
        methods: {

            changeLogo(e){
                let file = e.target.files[0];
                this.setting.logo = file;
                console.log(this.setting)
            },

            changeDarkLogo(e){
                let file = e.target.files[0];
                this.setting.dark_logo = file;
            },

            changeFavicon(e){
                let file = e.target.files[0];
                this.setting.favicon = file;
            },

            Change_background_image(e){
                let file = e.target.files[0];
                this.setting.background_image = file;
            },
            Selected_Currency(value) {
                if (value === null) {
                    this.setting.currency_id = "";
                }
            },

            Selected_Language(value) {
                if (value === null) {
                    this.setting.default_language = "";
                }
            },

                //---------------------------------- Clear_Cache ----------------\\
            Clear_Cache() {
                var self = this;
                self.Submit_Processing_Clear_Cache = true;
                axios
                    .get("/clear_cache")
                    .then(response => {
                        self.Submit_Processing_Clear_Cache = false;
                        toastr.success('{{ __('translate.Cache_cleared_successfully') }}');
                    })
                    .catch(error => {
                        self.Submit_Processing_Clear_Cache = false;
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },   


           //----------------------- Update setting ---------------------------\\
           Update_Settings() {
                var self = this;
                self.SubmitProcessing = true;
                var selectedThemeColor = $('input[name="theme_color"]:checked').val();
                let darkMode = $('input[name="is_dark_mode"]').is(':checked') ? 1 : 0;
                
                this.setting.theme_color = selectedThemeColor;
                self.data.append("logo", self.setting.logo);
                self.data.append("favicon", self.setting.favicon);
                self.data.append("dark_logo", self.setting.dark_logo);
                self.data.append("theme_color", self.setting.theme_color);
                self.data.append("background_image", self.setting.background_image);
                self.data.append("is_dark_mode", darkMode);
                self.data.append("_method", "put");
                console.log(this.setting)
                axios
                    .post("/settings/update_business_settings/" + self.setting.id, self.data)
                    .then(response => {
                        self.SubmitProcessing = false;
                        console.log(response.data)
                        window.location.href = '/settings/business_settings'; 
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors_settings = {};
                    })
                    .catch(error => {
                        self.SubmitProcessing = false;
                        if (error.response.status == 422) {
                            self.errors_settings = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },


            //---------------------------------- Update_Email_Settings----------------\\
            Update_Email_Settings() {
                var self = this;
                self.Submit_Processing_Email_Setting = true;
                axios
                    .post("/settings/email_settings", {
                        from_name: self.email_settings.from_name,
                        from_email: self.email_settings.from_email,
                        host: self.email_settings.host,
                        port: self.email_settings.port,
                        username: self.email_settings.username,
                        password: self.email_settings.password,
                        encryption: self.email_settings.encryption
                    })
                    .then(response => {
                        self.Submit_Processing_Email_Setting = false;
                        window.location.href = '/settings/system_settings'; 
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors_email_setting = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_Email_Setting = false;
                        if (error.response.status == 422) {
                            self.errors_email_setting = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },




           
        },
        //-----------------------------Autoload function-------------------
        created() {
        }

    });

</script>

<script>
    function changeTheme(element) {
    const radioButtons = document.querySelectorAll('input[name="theme_color"]');
    radioButtons.forEach(button => button.checked = false);

    const radioButton = element.querySelector('input[name="theme_color"]');
    radioButton.checked = true;
    console.log(radioButton)
    const colorThemes = document.querySelectorAll('.color_themes');
    colorThemes.forEach(theme => theme.style.border = '2px solid transparent');

    element.style.border = '2px solid black';
  }
</script>
@endsection