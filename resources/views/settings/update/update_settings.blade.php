@extends('layouts.master')
@section('main-content')


<div class="breadcrumb">
    <h1>{{ __('translate.Settings') }}</h1>
    <ul>
        <li><a href="/settings/system_settings">{{ __('translate.Update_Log') }}</a></li>
        <li>{{ __('translate.Settings') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<section id="section_update_Settings">
    {{-- Update_settings --}}
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <span>{{ __('translate.Update_Log') }}</span>
                </div>
                <!--begin::form-->
                <div class="card-body">
                    <div class="alert alert-danger">{{ __('translate.Update_note') }}</div>
                    @if($version !='')
                    <div class="alert alert-info">
                        <strong>{{ __('translate.Update_Available') }}
                            <span class="badge badge-pill badge-info">
                                {{$version}}
                            </span>
                        </strong>
                        <a :disabled="SubmitProcessing" @click="Update_system" class="btn btn-sm btn-info float-right">
                            {{ __('translate.Update_Now') }}
                        </a>
                    </div>
                    @else
                    <div class="alert alert-info">
                        <strong>{{ __('translate.Latest_version') }} <span class="badge badge-pill badge-info">
                    </div>
                    @endif

                    <div class="col-md-12 text-center">
                        <div class="alert alert-danger" v-if="SubmitProcessing">{{ __('translate.Update_wait') }}</div>
                        <div v-once class="typo__p" v-if="SubmitProcessing">
                            <div class="spinner spinner-primary mt-3"></div>
                        </div>
                        <a href="https://github.com/uilibrary/WorkTick-Issue-and-Features-Request" target="_blank"
                            class="btn btn-outline-success">{{ __('translate.View_Change_Log') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



</section>

@endsection

@section('page-js')

<script>
    var app = new Vue({
            el: '#section_update_Settings',
            data: {
                SubmitProcessing:false,
                errors:[],
                version: @json($version),
            },
           
            methods: {
    
                 //------------------------ Update ---------------------------\\
                Update_system() {
                    var self = this;
                    self.SubmitProcessing = true;
                    axios.get("/updater.update").then(response => {
                            self.SubmitProcessing = false;
                            window.location.href = '/'; 
                            toastr.success('{{ __('translate.Updated_in_successfully') }}');
                    })
                    .catch(error => {
                        self.SubmitProcessing = false;
                        if(error.response.status == 400){
                            toastr.error(error.response.data.msg);
                        }else{
                            toastr.error('{{ __('translate.There_was_something_wronge') }}');
                        }
                    });
                },

               
            },
            //-----------------------------Autoload function-------------------
            created() {
            }
    
        })
    
</script>

@endsection