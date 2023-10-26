<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')


<div class="breadcrumb">
    <h1>{{ __('translate.Settings') }}</h1>
    <ul>
        <li><a href="/settings/system_settings">{{ __('translate.Module_Installation') }}</a></li>
        <li>{{ __('translate.Settings') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<section id="section_module_Settings">
    {{-- Module_settings --}}
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <span>{{ __('translate.Module_Installation') }}</span>
                </div>
                <!--begin::form-->
                <div class="card-body">
                    <form @submit.prevent="Upload_Module()" enctype="multipart/form-data">
                        <div class="row">

                            <div class="col-md-4">
                                <label for="module" class="ul-form__label">{{ __('translate.Upload_Module') }} <span
                                        class="field_required">*</span></label>
                                <input name="module" @change="Change_Module" type="file" class="form-control"
                                    id="module">
                                <span class="error" v-if="errors && errors.module">
                                    @{{ errors.module[0] }}
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



</section>

@endsection

@section('page-js')

<script>
    var app = new Vue({
        el: '#section_module_Settings',
        data: {
            data: new FormData(),
            SubmitProcessing:false,
            errors:[],
            module: '',
        },
       
        methods: {


            Change_Module(e){
                let file = e.target.files[0];
                this.module = file;
            },


           //----------------------- Upload_Module ---------------------------\\
           Upload_Module() {
                var self = this;
                self.SubmitProcessing = true;
                self.data.append("module", self.module);

                axios
                    .post("/settings/module_settings" , self.data)
                    .then(response => {
                        self.SubmitProcessing = false;
                        // window.location.href = '/settings/module_settings'; 
                        toastr.success('{{ __('translate.Uploaded_in_successfully') }}');
                        self.errors = {};
                    })
                    .catch(error => {
                        self.SubmitProcessing = false;
                        if (error.response.status == 422) {
                            self.errors = error.response.data.errors;
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