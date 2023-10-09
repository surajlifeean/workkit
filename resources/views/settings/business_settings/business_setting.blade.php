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


                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header ">
                                        <h5 class="">{{ __('Logo dark') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4 setting-logo">
                                                <a href=""
                                                    target="_blank">
                                                    <img id="image" alt="your image"
                                                        src=""
                                                        width="150px" class="big-logo">
                                                </a>
                                            </div>
                                            <div class="choose-files mt-3">

                                                <label for="logo" class="ul-form__label">{{ __('translate.Change_Logo') }} </label>
                                                <input name="logo" @change="changeLogo" type="file" class="form-control" id="logo">
                                                <span class="error" v-if="errors_settings && errors_settings.logo">
                                                    @{{ errors_settings.logo[0] }}
                                                </span>

                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                               
                            </div>


                            <div class="col-md-4">

                                <div class="card">
                                    <div class="card-header ">
                                        <h5 class="">{{ __('Logo Light') }}</h5>
                                    </div>

                                    <div class="card-body pt-0">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4  setting-logo">
                                                {{--  <img id="image1" src="{{ $logo . '/' . (isset($company_logo_light) && !empty($company_logo_light) ? $company_logo_light : 'logo-light.png') }}"
                                                class="logo logo-sm img_setting"
                                                style="filter: drop-shadow(2px 3px 7px #011c4b);">  --}}
                                                <a href=""
                                                    target="_blank">
                                                    <img id="image1" alt="your image"
                                                        src=""
                                                        width="150px"
                                                        class="big-logo"style="">
                                                </a>

                                            </div>
                                            <div class="choose-files mt-3">
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

                            <div class="col-md-4">

                                <div class="card">
                                    <div class="card-header ">
                                        <h5 class="">{{ __('Favicon') }}</h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class=" setting-card">
                                            <div class="logo-content mt-4 setting-logo ">
                                                {{-- <img src="{{ $logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') }}"
                                                width="50px" class="logo logo-sm img_setting"> --}}
                                                <a href=""
                                                    target="_blank">
                                                    <img id="image2" alt="your image"
                                                        src=""
                                                        width="50px"
                                                        class="big-logo">
                                                </a>
                                            </div>
                                            <div class="choose-files mt-3">

                                                <label for="company_favicon">
                                                    <label for="logo" class="ul-form__label">{{ __('translate.Change_Logo') }} </label>
                                                    <input name="logo" @change="changeLogo" type="file" class="form-control" id="logo">
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
                             @php
                                 $color = 'errr'
                             @endphp
                            <h5 class="mt-3 mb-3">{{ __('Theme Customizer') }}</h5>
                            <div class="col-12">
                                <div class="pct-body">
                                    <div class="row">
                                        <div class="col-4">
                                            <h6 class="">
                                                <i data-feather="credit-card"
                                                    class="me-2"></i>{{ __('Primary color Settings') }}
                                            </h6>
                                            <hr class="my-2" />
                                            <div class="theme-color themes-color">
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-1' ? 'active_color' : '' }}"
                                                    data-value="theme-1"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-1"
                                                    {{ $color == 'theme-1' ? 'checked' : '' }}>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-2' ? 'active_color' : '' }}"
                                                    data-value="theme-2"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-2"
                                                    {{ $color == 'theme-2' ? 'checked' : '' }}>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-3' ? 'active_color' : '' }}"
                                                    data-value="theme-3"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-3"
                                                    {{ $color == 'theme-3' ? 'checked' : '' }}>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-4' ? 'active_color' : '' }}"
                                                    data-value="theme-4"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-4"
                                                    {{ $color == 'theme-4' ? 'checked' : '' }}>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-5' ? 'active_color' : '' }}"
                                                    data-value="theme-5"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-5"
                                                    {{ $color == 'theme-5' ? 'checked' : '' }}>
                                                <br>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-6' ? 'active_color' : '' }}"
                                                    data-value="theme-6"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-6"
                                                    {{ $color == 'theme-6' ? 'checked' : '' }}>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-7' ? 'active_color' : '' }}"
                                                    data-value="theme-7"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-7"
                                                    {{ $color == 'theme-7' ? 'checked' : '' }}>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-8' ? 'active_color' : '' }}"
                                                    data-value="theme-8"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-8"
                                                    {{ $color == 'theme-8' ? 'checked' : '' }}>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-9' ? 'active_color' : '' }}"
                                                    data-value="theme-9"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-9"
                                                    {{ $color == 'theme-9' ? 'checked' : '' }}>
                                                <a href="#!"
                                                    class="themes-color-change {{ $color == 'theme-10' ? 'active_color' : '' }}"
                                                    data-value="theme-10"></a>
                                                <input type="radio" class="theme_color d-none"
                                                    name="theme_color" value="theme-10"
                                                    {{ $color == 'theme-10' ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <h6 class=" ">
                                                <i data-feather="layout"
                                                    class="me-2"></i>{{ __('Sidebar Settings') }}
                                            </h6>
                                            <hr class="my-2 " />
                                            <div class="form-check form-switch ">
                                                <input type="checkbox" class="form-check-input"
                                                    id="cust_theme_bg" name="cust_theme_bg"
                                                    {{-- {{ $settings['cust_theme_bg'] == 'on' ? 'checked' : '' }} --}}
                                                <label class="form-check-label f-w-600 pl-1"
                                                    for="cust_theme_bg">{{ __('Transparent layout') }}</label>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            < class=" ">
                                                <i data-feather="sun"
                                                    class=""></i>{{ __('Layout Settings') }}
                                            </
                                            <hr class=" my-2  " />
                                            <div class="form-check form-switch mt-2 ">
                                                <input type="hidden" name="cust_darklayout"
                                                    value="off">
                                                <input type="checkbox" class="form-check-input"
                                                    id="cust_darklayout" name="cust_darklayout"
                                                    {{-- {{ $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} --}}
                                                <label class="form-check-label f-w-600 pl-1"
                                                    for="cust_darklayout">{{ __('Dark Layout') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary" :disabled="SubmitProcessing">
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
            setting: {

            },
        },
       
        methods: {


            changeLogo(e){
                let file = e.target.files[0];
                this.setting.logo = file;
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
                self.data.append("currency_id", self.setting.currency_id);
                self.data.append("email", self.setting.email);
                self.data.append("logo", self.setting.logo);
                self.data.append("CompanyName", self.setting.CompanyName);
                self.data.append("CompanyPhone", self.setting.CompanyPhone);
                self.data.append("CompanyAdress", self.setting.CompanyAdress);
                self.data.append("footer", self.setting.footer);
                self.data.append("developed_by", self.setting.developed_by);
                self.data.append("_method", "put");

                axios
                    .post("/settings/system_settings/" + self.setting.id, self.data)
                    .then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/settings/system_settings'; 
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

    })

</script>
@endsection