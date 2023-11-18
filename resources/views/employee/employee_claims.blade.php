<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Employee') }}</h1> 
    <ul>
        <li><a href="/employee_claims">| {{ __('translate.claims') }}</a></li>
       
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Company_list">
    <div class="col-md-12">
        <div class="card text-left">
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="ul-contact-list" class="display table data_datatable"
                        >
                        <thead>
                            <tr>
                                <th>{{ __('translate.Employee') }}</th>
                                <th>{{ __('translate.Avatar') }}</th>
                                <th>{{ __('translate.Title') }}</th>
                                <th>{{ __('translate.Description') }}</th>
                                <th>{{ __('translate.Date') }}</th>
                                <th>{{ __('translate.Status') }}</th>
                                <th>{{ __('translate.Attachment')}}</th>
                                <th>{{ __('translate.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($claims as $claim)
                                <tr>
                                   <td>{{ $claim->username }}</td>
                                   <td>
                                    <a href="{{ asset('assets/images/avatar/'. $claim->avatar) }}" target="_blank" onclick="openImageWindow(event)">
                                        <img style="height: 40px; width: 40px; border-radius: 100%;" src="{{ asset('assets/images/avatar/'. $claim->avatar) }}" alt="user avatar">
                                    </a>
                                   </td>
                                   <td>{{ $claim->title }}</td>
                                   <td>{{ $claim->description }}</td>
                                   <td>{{ $claim->created_at ? \Carbon\Carbon::parse($claim->created_at)->format('d/m/Y H:i') : '' }}</td>
                                   <td>{{ __('translate.' . $claim->status) }}</td>
                                   <td>
                                    <a href="{{ asset('assets/images/claims/'. $claim->attachment) }}" target="_blank" onclick="openImageWindow(event)">
                                        <img style="height: 40px; width: 40px; border-radius: 100%;" src="{{ asset('assets/images/claims/'. $claim->attachment) }}" alt="user avatar">
                                    </a>
                                   </td>
                                   <td>
                                    <a @click="Cancel_claim( {{$claim->id}} )"
                                        class="ul-link-action text-danger mr-1" data-toggle="tooltip"
                                        data-placement="top" title="Cancel Leave Request">
                                        <i class="i-Close-Window"></i>
                                    </a>
                                    <a @click="Edit_claim( {{ $claim->id}})" class="ul-link-action text-success"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="i-Edit"></i>
                                    </a>
                                   </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Add & Edit company -->
        <div class="modal fade" id="Claim_Modal" tabindex="-1" role="dialog" aria-labelledby="Claim_Modal"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="editmode" class="modal-title">{{ __('translate.Edit') }}</h5>
                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="Update_Claims()">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Status') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Status" placeholder="{{ __('translate.Choose_status') }}"
                                        v-model="claim.status" :reduce="(option) => option.value" :options="
                                                            [
                                                                {label: '@lang('translate.approved')', value: 'approved'},
                                                                {label: '@lang('translate.pending')', value: 'pending'},
                                                                {label: '@lang('translate.rejected')', value: 'rejected'},
                                                            ]">
                                    </v-select>

                                    <span class="error" v-if="errors && errors.status">
                                        @{{ errors.status[0] }}
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
            claim: {
                id: "",
                status: "",
            },
        },
       
        methods: {

             

            //----------------------------------------- Claims ----------------------------------\\
            Edit_claim(claim){
                this.claim.id = claim;
                $('#Claim_Modal').show();
            },

            Update_Claims() {
                var self = this;
                self.SubmitProcessing = true;
                axios.post("/employees_claims/" + self.claim.id, {

                    status: self.claim.status,
                    
                }).then(response => {
                    self.SubmitProcessing = false;
                         
                    toastr.success('{{ __('translate.Updated_in_successfully') }}');
                    self.errors = {};
                }).catch(error => {
                    self.SubmitProcessing = false;
                    if (error.response.status == 422) {
                        self.errors = error.response.data.errors;
                    }
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },

            Selected_Status(value) {
                if (value === null) {
                    this.claim.status = value;
                }
            },
            Cancel_claim(id) {

                swal({
                    title: '{{ __('translate.Are_you_sure') }}',
                    text: '{{ __('translate.You_wont_be_able_to_revert_this') }}',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0CC27E',
                    cancelButtonColor: '#FF586B',
                    confirmButtonText: '{{ __('translate.Yes') }}',
                    cancelButtonText: '{{ __('translate.No_cancel') }}',
                    confirmButtonClass: 'btn btn-{{$setting->theme_color}} mr-5',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false
                }).then(function () {
                        
                        axios
                            .delete("/claims/" + id)
                            .then((res) => {
                                console.log(res.data);
                                location.reload();
                                toastr.success(res.data.success);
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

        $('#ul-contact-list').DataTable( {
            "processing": true, // for show progress bar
            // select: {
            //     // style: 'multi',
            //     // selector: '.select-checkbox',
            //     items: 'row',
            // },
           
            // columnDefs: [
                
            //     {
            //         targets: 0,
            //         className: 'select-checkbox'
            //     },
            //     {
            //         targets: 0,
            //         orderable: false
            //     }
            // ],
        
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
                        'csv',
                        'excel', 
                        'pdf', 
                        {
                            extend: 'print',
                            text: "{{ __('translate.print') }}",
                        },  
                    ]
                }]
        });

    });
    function openImageWindow(event) {
        event.preventDefault();
        window.open(event.currentTarget.href, '_blank', 'width=600,height=500');
    }
</script>
@endsection