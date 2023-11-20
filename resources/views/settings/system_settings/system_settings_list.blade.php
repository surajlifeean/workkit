<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')


<div class="breadcrumb">
    <h1>{{ __('translate.Settings') }}</h1>
    <ul>
        <li><a href="/settings/system_settings">{{ __('translate.System_Settings') }}</a></li>
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
                    <span>{{ __('translate.System_Settings') }}</span>
                </div>
                <!--begin::form-->
                <div class="card-body">
                    <form @submit.prevent="Update_Settings()" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-md-4">
                                <label class="ul-form__label">{{ __('translate.Default_Currency') }} <span
                                        class="field_required">*</span></label>
                                <v-select @input="Selected_Currency"
                                    placeholder="{{ __('translate.Enter_Default_Currency') }}"
                                    v-model="setting.currency_id" :reduce="label => label.value"
                                    :options="currencies.map(currencies => ({label: currencies.name, value: currencies.id}))">
                                <template #no-options>
                                    {{ __('translate.Sorry, no matching options') }}
                                </template>
                            </v-select>
                                <span class="error" v-if="errors_settings && errors_settings.currency_id">
                                    @{{ errors_settings.currency_id[0] }}
                                </span>
                            </div>


                            <div class="col-md-4">
                                <label for="email" class="ul-form__label">{{ __('translate.Default_Email') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="setting.email" class="form-control" id="email" id="email"
                                    placeholder="{{ __('translate.Enter_Default_Email') }}">
                                <span class="error" v-if="errors_settings && errors_settings.email">
                                    @{{ errors_settings.email[0] }}
                                </span>
                            </div>

                            {{-- <div class="col-md-4">
                                <label for="logo" class="ul-form__label">{{ __('translate.Change_Logo') }} </label>
                                <input name="logo" @change="changeLogo" type="file" class="form-control" id="logo">
                                <span class="error" v-if="errors_settings && errors_settings.logo">
                                    @{{ errors_settings.logo[0] }}
                                </span>
                            </div> --}}


                            <div class="col-md-4">
                                <label for="name" class="ul-form__label">{{ __('translate.Company_Name') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="setting.CompanyName" class="form-control" id="name"
                                    placeholder="{{ __('translate.Enter_Company_Name') }}">
                                <span class="error" v-if="errors_settings && errors_settings.name">
                                    @{{ errors_settings.name[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="Phone" class="ul-form__label">{{ __('translate.Company_Phone') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="setting.CompanyPhone" class="form-control" id="Phone"
                                    placeholder="{{ __('translate.Enter_Company_Phone') }}">
                                <span class="error" v-if="errors_settings && errors_settings.phone">
                                    @{{ errors_settings.phone[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="developed_by" class="ul-form__label">{{ __('translate.Developed_by') }}
                                    <span class="field_required">*</span></label>
                                <input type="text" v-model="setting.developed_by" class="form-control" id="developed_by"
                                    placeholder="{{ __('translate.Enter_Developed_by') }}">
                                <span class="error" v-if="errors_settings && errors_settings.developed_by">
                                    @{{ errors_settings.developed_by[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="footer" class="ul-form__label">{{ __('translate.Footer') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="setting.footer" class="form-control" id="footer"
                                    placeholder="{{ __('translate.Enter_Footer') }}">
                                <span class="error" v-if="errors_settings && errors_settings.footer">
                                    @{{ errors_settings.footer[0] }}
                                </span>
                            </div>


                            <div class="col-md-12">
                                <label for="CompanyAdress" class="ul-form__label">{{ __('translate.Company_Adress') }}
                                    <span class="field_required">*</span></label>
                                <textarea type="text" v-model="setting.CompanyAdress" class="form-control"
                                    name="CompanyAdress" id="CompanyAdress"
                                    placeholder="{{ __('translate.Enter_Company_Adress') }}"></textarea>
                                <span class="error" v-if="errors_settings && errors_settings.CompanyAdress">
                                    @{{ errors_settings.CompanyAdress[0] }}
                                </span>
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

    {{-- SMTP Configuration --}}
    <div class="row mt-5">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <span>{{ __('translate.Email_Settings') }}</span>
                </div>
                <!--begin::form-->
                <div class="card-body">
                    <form @submit.prevent="Update_Email_Settings()" enctype="multipart/form-data">
                        <div class="row">


                            <div class="col-md-4">
                                <label for="from_name" class="ul-form__label">{{ __('translate.From_Name') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="email_settings.from_name" class="form-control"
                                    id="from_name" id="from_name" placeholder="{{ __('translate.From_Name') }}">
                                <span class="error" v-if="errors_email_setting && errors_email_setting.from_name">
                                    @{{ errors_email_setting.from_name[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="from_email" class="ul-form__label">{{ __('translate.From_Email') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="email_settings.from_email" class="form-control"
                                    id="from_email" id="from_email" placeholder="{{ __('translate.From_Email') }}">
                                <span class="error" v-if="errors_email_setting && errors_email_setting.from_email">
                                    @{{ errors_email_setting.from_email[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="host" class="ul-form__label">{{ __('translate.Host') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="email_settings.host" class="form-control" id="host"
                                    id="host" placeholder="{{ __('translate.Mail_Host') }}">
                                <span class="error" v-if="errors_email_setting && errors_email_setting.host">
                                    @{{ errors_email_setting.host[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="port" class="ul-form__label">{{ __('translate.Port') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="email_settings.port" class="form-control" id="port"
                                    id="port" placeholder="{{ __('translate.Mail_Port') }}">
                                <span class="error" v-if="errors_email_setting && errors_email_setting.port">
                                    @{{ errors_email_setting.port[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="encryption" class="ul-form__label">{{ __('translate.Encryption') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="email_settings.encryption" class="form-control"
                                    id="encryption" id="encryption" placeholder="{{ __('translate.Mail_Encryption') }}">
                                <span class="error" v-if="errors_email_setting && errors_email_setting.encryption">
                                    @{{ errors_email_setting.encryption[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="username" class="ul-form__label">{{ __('translate.Username') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="email_settings.username" class="form-control" id="username"
                                    id="username" placeholder="{{ __('translate.Mail_Username') }}">
                                <span class="error" v-if="errors_email_setting && errors_email_setting.username">
                                    @{{ errors_email_setting.username[0] }}
                                </span>
                            </div>

                            <div class="col-md-4">
                                <label for="password" class="ul-form__label">{{ __('translate.Password') }} <span
                                        class="field_required">*</span></label>
                                <input type="text" v-model="email_settings.password" class="form-control" id="password"
                                    id="password" placeholder="{{ __('translate.Mail_Password') }}">
                                <span class="error" v-if="errors_email_setting && errors_email_setting.password">
                                    @{{ errors_email_setting.password[0] }}
                                </span>
                            </div>


                        </div>

                        <div class="row mt-3">

                            <div class="col-md-6">
                                <button type="submit" class="btn btn-{{$setting->theme_color}}"
                                    :disabled="Submit_Processing_Email_Setting">
                                    {{ __('translate.Submit') }}
                                </button>
                                <div v-once class="typo__p" v-if="Submit_Processing_Email_Setting">
                                    <div class="spinner spinner-primary mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Clear_Cache--}}
    <div class="row mt-5">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <span>{{ __('translate.Clear_Cache') }}</span>
                </div>
                <!--begin::form-->
                <div class="card-body">
                    <form @submit.prevent="Clear_Cache()">
                        <div class="row">

                            <button type="submit" class="btn btn-{{$setting->theme_color}}" :disabled="Submit_Processing_Clear_Cache">
                                {{ __('translate.Clear_Cache') }}
                            </button>
                            <div v-once class="typo__p" v-if="Submit_Processing_Clear_Cache">
                                <div class="spinner spinner-primary mt-3"></div>
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
            email_settings: @json($email_settings),
            currencies: @json($currencies),
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