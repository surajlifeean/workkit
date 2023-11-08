<?php

use App\Models\ActivePlan;

$setting = DB::table('settings')->where('deleted_at', '=', null)->first(); 

$active_plan = ActivePlan::whereIn('status', ['active', 'hold'])
->where('start_date', '<=', now()) 
->where('end_date', '>=', now())  
->select('subs_plan_id', 'end_date', 'status')
->first();

?>
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
@if (session('success'))
    <div class="alert alert-success">
        {{session('success')}}
    </div>
@endif
<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Company_list">
    <div class="col-md-12">
        <div class="row">
            @foreach($data as $dt)
            @if($dt['active'] == 1)
            <div class="col-lg-3 my-4">
                <div class="card position-relative " style="height: 100%">
                    <div class="card-body d-flex  align-items-center justify-content-center flex-column overflow-hidden">
                        <div class="position-absolute bg-{{$setting->theme_color}} plan_cont">{{$dt['plan']}}</div>
                        <div class="text-dark {{ $dt['is_offer_price'] == 1 ? 'mt-3 d-inline' : 'd-none'}}">Special offer</div>
                        <h2 class="{{ $dt['is_offer_price'] == 1 ? '' : 'mt-4'}}" style="font-size: 2rem; margin-bottom: 0;">{{ $dt['currency'] == 'USD' ? '$' : ($dt['currency'] == 'EUR' ? '€' : ($dt['currency'] == 'XOF' || $dt['currency'] == 'XAF' ? 'FCFA' : '')) }}
                        {{ $dt['is_offer_price'] == 1 ? $dt['offered_price'] : $dt['price'] }}
                        <span style="font-size: 13px;font-weight: 600">
                        / {{
                                   
                                   $dt['duration'] > 1 && $dt['duration'] < 12 ?
                                       ($dt['duration'] == 1 ? $dt['duration'] . ' month' :  $dt['duration'] . ' months'):
                                       ($dt['duration'] >= 12 ?
                                           number_format($dt['duration'] / 12, 1) . ' year' :
                                           $dt['duration'] . ' months')
                               }}
                        </span></h2>
                        @if($dt['is_offer_price'] == 1)
                        <p class="position-relative m-0">
                             {{ $dt['price'] }}
                            <span class="position-absolute" style="width: 100%;height: 1px;background: red; top: 9px; right: 0;"></span>
                        </p>
                        @endif
                        <p class="m-0">{{$dt['description']}}</p>
                        <div class="d-flex justify-content-center align-items-center flex-column mt-3">
                          <ul class="list-unstyled mb-4 mt-1">
                            <li class="mb-3">
                                <i class="nav-icon text-primary i-Arrow-RightinCircle font-weight-900 mr-2" style="font-size: 1rem;"></i>
                                <span style="font-size: 1rem; font-weight: 600">{{ $dt['total_users'] == -1 ? 'Unlimited' : $dt['total_users'] }} {{ __('translate.Users') }}</span>
                            </li>
                            <li class="mb-3">
                                <i class="nav-icon text-primary i-Arrow-RightinCircle font-weight-900 mr-2" style="font-size: 1rem;"></i>
                                <span style="font-size: 1rem; font-weight: 600">{{ $dt['total_users'] == -1 ? 'Unlimited' : $dt['total_users'] - 1 }} {{ __('translate.Employees') }}</span>
                            </li>
                           
                          </ul>
                        </div>
                        
                        @if($active_plan && $active_plan->subs_plan_id == $dt['id'])
                        <span>
                             {{ ucwords($active_plan->status) }}
                           </span>
                           <span>
                             Exp Date: {{ $active_plan->end_date }}
                           </span>
                        @else
                        <a href="{{ route('stripe.checkout', [ 'price' => ( $dt['is_offer_price'] == 1 ? $dt['offered_price'] : $dt['price'] ), 'product' => $dt['plan'], 'currency' => $dt['currency'] , 'plan_id' => $dt['id'] , 'is_offer_price' => $dt['is_offer_price'] ] ) }}" class="btn btn-{{$setting->theme_color}}">Buy Now</a>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            @endforeach
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
                    confirmButtonClass: 'btn btn-{{$setting->theme_color}} mr-5',
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
                    confirmButtonClass: 'btn btn-{{$setting->theme_color}} mr-5',
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
                sSearchPlaceholder: "{{ __('translate.Search...') }}",
                "oPaginate": {
                    "sNext": "{{ __('translate.Next') }}",
                    "sPrevious": "{{ __('translate.Previous') }}",
                    "sLast": "{{ __('translate.Last') }}",
                },
                "sZeroRecords": "{{__('translate.No matching records found')}}",
                "sInfo": "{{__('translate.Showing _START_ to _END_ of _TOTAL_ entries')}}",
                "sInfoFiltered": "{{ __('translate.filtered from _MAX_ total entries') }}",
                "sInfoEmpty": "{{ __('translate.Showing 0 to 0 of 0 entries')}}",
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

@if (session('error'))
    <script>
        toastr.error("{{ session('error') }}");
    </script>
@endif
@if (session('success'))
    <script>
        toastr.success("{{ session('success') }}");
    </script>
@endif
@endsection