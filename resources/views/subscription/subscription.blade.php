<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
{{-- <link rel="stylesheet" href="{{asset('assets/fonts/iconsmind/tabler-icons.css')}}"> --}}

@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Manage_Plan') }}</h1>
    <ul>
        <li><a href="/subscription">/ {{ __('translate.Subscription_List') }}</a></li>
        
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Company_list">
    <div class="col-md-12">
        <div class="row">
            <div class="col-lg-3">
                <div class="card position-relative">
                    <div class="card-body d-flex  align-items-center justify-content-center flex-column">
                        <div class="position-absolute bg-primary plan_cont">Free Plan</div>
                        <h2 class="mt-3" style="font-size: 3rem;">$0<span style="font-size: 13px;font-weight: 600">/ Lifetime</span></h2>
                        <p>Free plan offers</p>
                        <div class="d-flex justify-content-center align-items-center flex-column">
                          <ul class="list-unstyled mb-4 mt-1">
                            <li class="mb-3">
                                <i class="nav-icon text-primary i-Arrow-RightinCircle font-weight-900 mr-2" style="font-size: 1rem;"></i>
                                <span style="font-size: 1rem; font-weight: 600">1 User</span>
                            </li>
                            <li class="mb-3">
                                <i class="nav-icon text-primary i-Arrow-RightinCircle font-weight-900 mr-2" style="font-size: 1rem;"></i>
                                <span style="font-size: 1rem; font-weight: 600">1 Users and</span>
                            </li>
                            <li class="mb-3">
                                <i class="nav-icon text-primary i-Arrow-RightinCircle font-weight-900 mr-2" style="font-size: 1rem;"></i>
                                <span style="font-size: 1rem; font-weight: 600">1 Users and</span>
                            </li>
                            <li class="mb-3">
                                <i class="nav-icon text-primary i-Arrow-RightinCircle font-weight-900 mr-2" style="font-size: 1rem;"></i>
                                <span style="font-size: 1rem; font-weight: 600">1 Users and</span>
                            </li>
                          </ul>
                        </div>
                           
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>


<script>
    var app = new Vue({
        el: '#section_Company_list',
        data: {
            selectedIds:[],
            editmode: false,
            SubmitProcessing:false,
            errors:[],
            companies:[],
            company: {
                name: "",
                email:"",
                country:"",
                phone:"",
            }, 
        },
       
        methods: {

            //---- Event selected_row
            selected_row(id) {
                //in here you can check what ever condition  before append to array.
                if(this.selectedIds.includes(id)){
                    const index = this.selectedIds.indexOf(id);
                    this.selectedIds.splice(index, 1);
                }else{
                    this.selectedIds.push(id)
                }
            },

            //------------------------------ Show Modal (Create Company) -------------------------------\\
            New_Company() {
                this.reset_Form();
                this.editmode = false;
                $('#Company_Modal').modal('show');
            },

            //------------------------------ Show Modal (Update Company) -------------------------------\\
            Edit_Company(company) {
                this.editmode = true;
                this.reset_Form();
                this.company = company;
                $('#Company_Modal').modal('show');
            },


            //----------------------------- Reset Form ---------------------------\\
            reset_Form() {
                this.company = {
                    id: "",
                    name: "",
                    email:"",
                    country:"",
                    phone:"",
                };
                this.errors = {};
            },
            
            //------------------------ Create company ---------------------------\\
            Create_Company() {
                var self = this;
                self.SubmitProcessing = true;
                axios.post("/core/company", {
                    name: self.company.name,
                    email: self.company.email,
                    country: self.company.country,
                    phone: self.company.phone,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/core/company'; 
                        toastr.success('{{ __('translate.Created_in_successfully') }}');
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

           //----------------------- Update Company ---------------------------\\
            Update_Company() {
                var self = this;
                self.SubmitProcessing = true;
                axios.put("/core/company/" + self.company.id, {
                    name: self.company.name,
                    email: self.company.email,
                    country: self.company.country,
                    phone: self.company.phone,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/core/company'; 
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
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

             //--------------------------------- Remove Company ---------------------------\\
            Remove_Company(id) {

                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-primary mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                        axios
                            .delete("/core/company/" + id)
                            .then(() => {
                                window.location.href = '/core/company'; 
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
            },

            //--------------------------------- delete_selected ---------------------------\\
            delete_selected() {
                var self = this;
                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes_delete_it') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-primary mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                        axios
                        .post("/core/company/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                        .then(() => {
                            window.location.href = '/core/company'; 
                            toastr.success('{{ __('translate.Deleted_in_successfully') }}');
                        })
                        .catch(() => {
                            toastr.error('{{ __('translate.There_was_something_wronge') }}');
                        });
                });
            },           
        },
        //-----------------------------Autoload function-------------------
        created() {
        }

    })

</script>

<script type="text/javascript">
    $(function () {
      "use strict";

        $('#company_list_table').DataTable( {
            "processing": true, // for show progress bar
            select: {
                style: 'multi',
                selector: '.select-checkbox',
                items: 'row',
            },
           
            columnDefs: [
                
                {
                    targets: 0,
                    className: 'select-checkbox'
                },
                {
                    targets: 0,
                    orderable: false
                }
            ],
        
            dom: "<'row'<'col-sm-12 col-md-7'lB><'col-sm-12 col-md-5 p-0'f>>rtip",
            oLanguage:
                { 
                sLengthMenu: "_MENU_", 
                sSearch: '',
                sSearchPlaceholder: "Search..."
            },
            buttons: [
                {
                    extend: 'collection',
                    text: 'EXPORT',
                    buttons: [
                        'csv','excel', 'pdf', 'print'
                    ]
                }]
        });

    });
</script>
@endsection