<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Policy_List') }}</h1>
    <ul>
        <li><a href="/core/policies">{{ __('translate.Policy') }}</a></li>
        <li>{{ __('translate.Policy_List') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Policy_list">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header text-right bg-transparent">
                @can('policy_add')
                <a class="btn btn-{{$setting->theme_color}} btn-md m-1" @click="New_Policy"><i
                        class="i-Add text-white mr-2"></i>{{ __('translate.Create') }}</a>
                @endcan
                @can('policy_delete')
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="delete_selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="policy_list_table" class="display table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('translate.Policy_Title') }}</th>
                                <th>{{ __('translate.Policy_Company') }}</th>
                                <th>{{ __('translate.Policy_Description') }}</th>
                                <th>{{ __('translate.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($policies as $policy)
                            <tr>
                                <td @click="selected_row( {{ $policy->id}})"></td>
                                <td>{{$policy->title}}</td>
                                <td>{{$policy->company->name}}</td>
                                <td>
                                {{ Illuminate\Support\Str::limit($policy->description, $limit = 35) }}
                                    <a @click="Open_Text( {{ $policy }})" class="ul-link-action text-{{ $setting->theme_color }} cursor-pointer"
                                        data-toggle="tooltip" data-placement="top" title="Read more" style="font-size: 11px;">
                                        Read more...
                                    </a>
                                </td>
                                <td>
                                    @can('policy_edit')
                                    <a @click="Edit_Policy( {{ $policy}})" class="ul-link-action text-success"
                                        data-toggle="tooltip" data-placement="top" title="{{ __('translate.Edit') }}">
                                        <i class="i-Edit"></i>
                                    </a>
                                    @endcan
                                    @can('policy_delete')
                                    <a @click="Remove_Policy( {{ $policy->id}})" class="ul-link-action text-danger mr-1"
                                        data-toggle="tooltip" data-placement="top" title="{{ __('translate.Delete') }}">
                                        <i class="i-Close-Window"></i>
                                    </a>
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Add & Edit Policy -->
        <div class="modal fade" id="Policy_Modal" tabindex="-1" role="dialog" aria-labelledby="Policy_Modal"
            aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="editmode" class="modal-title">{{ __('translate.Edit') }}</h5>
                        <h5 v-else class="modal-title">{{ __('translate.Create') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="editmode?Update_Policy():Create_Policy()">
                            <div class="row">

                                <div class="col-md-12">
                                    <label class="ul-form__label">{{ __('translate.Company') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Company"
                                        placeholder="{{ __('translate.Choose_Company') }}" v-model="policy.company_id"
                                        :reduce="label => label.value"
                                        :options="companies.map(companies => ({label: companies.name, value: companies.id}))">
                                    <template #no-options>
                                    {{ __('translate.Sorry, no matching options') }}
                                </template>
                            </v-select>

                                    <span class="error" v-if="errors && errors.company_id">
                                        @{{ errors.company_id[0] }}
                                    </span>
                                </div>
                                <div class="col-md-12">
                                    <label for="title" class="ul-form__label">{{ __('translate.Policy_Title') }} <span
                                            class="field_required">*</span></label>
                                    <input type="text" v-model="policy.title" class="form-control" name="title"
                                        id="title" placeholder="{{ __('translate.Enter_policy_title') }}">
                                    <span class="error" v-if="errors && errors.title">
                                        @{{ errors.title[0] }}
                                    </span>
                                </div>

                                <div class="col-md-12">
                                    <label for="description"
                                        class="ul-form__label">{{ __('translate.Detailed_Description') }} <span
                                            class="field_required">*</span></label>
                                    <textarea type="text" v-model="policy.description" class="form-control"
                                        name="description" id="description"
                                        placeholder="{{ __('translate.Enter_policy_description') }}"></textarea>
                                    <span class="error" v-if="errors && errors.description">
                                        @{{ errors.description[0] }}
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

<div class="modal fade" id="policyModal" tabindex="-1" role="dialog" aria-labelledby="policyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="fullPolicyText">
        <!-- Full policy text will be displayed here -->
      </div>
    
    </div>
  </div>
</div>
@endsection

@section('page-js')

<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect)

        var app = new Vue({
        el: '#section_Policy_list',
        data: {
            selectedIds:[],
            editmode: false,
            SubmitProcessing:false,
            errors:[],
            policies: {}, 
            companies: [], 
            policy: {
                title: "",
                company_id:"",
                description:"",
            }, 
        },
       
        methods: {
            Open_Text(des){
                console.log(des);
                // Assuming you're using Vue to manage the state
                $('#fullPolicyText').empty();
                $('#fullPolicyText').append(des.description); // Use html() instead of append()
                // // Open the modal
                $('#policyModal').modal('show');
            },
           
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


            //------------------------------ Show Modal (Create Policy) -------------------------------\\
            New_Policy() {
                this.reset_Form();
                this.editmode = false;
                this.Get_Data_Create();
                $('#Policy_Modal').modal('show');
            },

            //------------------------------ Show Modal (Update Policy) -------------------------------\\
            Edit_Policy(policy) {
                this.editmode = true;
                this.reset_Form();
                this.Get_Data_Edit(policy.id);
                this.policy = policy;
                $('#Policy_Modal').modal('show');
            },


             //---------------------- Get_Data_Create ------------------------------\\
             Get_Data_Create() {
                axios
                    .get("/core/policies/create")
                    .then(response => {
                        this.companies   = response.data.companies;
                    })
                    .catch(error => {
                       
                    });
            },

            //---------------------- Get_Data_Edit ------------------------------\\
            Get_Data_Edit(id) {
                axios
                    .get("/core/policies/"+id+"/edit")
                    .then(response => {
                        this.companies   = response.data.companies;
                    })
                    .catch(error => {
                       
                    });
            },

            Selected_Company(value) {
                if (value === null) {
                    this.policy.company_id = "";
                }
            },

            //----------------------------- Reset Form ---------------------------\\
            reset_Form() {
                this.policy = {
                    id: "",
                    title: "",
                    company_id:"",
                    description: "",
                };
                this.errors = {};
            },
            
            //------------------------ Create Policy ---------------------------\\
            Create_Policy() {
                var self = this;
                self.SubmitProcessing = true;
                axios.post("/core/policies", {
                    title: self.policy.title,
                    company_id: self.policy.company_id,
                    description: self.policy.description,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/core/policies'; 
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

           //----------------------- Update Policy ---------------------------\\
            Update_Policy() {
                var self = this;
                self.SubmitProcessing = true;
                axios.put("/core/policies/" + self.policy.id, {
                    title: self.policy.title,
                    company_id: self.policy.company_id,
                    description: self.policy.description,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/core/policies'; 
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

             //--------------------------------- Remove policy ---------------------------\\
            Remove_Policy(id) {

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
                            .delete("/core/policies/" + id)
                            .then(() => {
                                window.location.href = '/core/policies'; 
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
                        .post("/core/policies/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                            .then(() => {
                                window.location.href = '/core/policies'; 
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
        },

    });

</script>

<script type="text/javascript">
    $(function () {
      "use strict";

        $('#policy_list_table').DataTable( {
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
                    targets: [0],
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


</script>
@endsection