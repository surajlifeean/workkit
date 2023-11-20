<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')
<meta charset="utf-8">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Designation_List') }}</h1>
    <ul>
        <li><a href="/core/designations">{{ __('translate.Designations') }}</a></li>
        <li>{{ __('translate.Designation_List') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Designation_list">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header text-right bg-transparent">
                @can('designation_add')
                <a class="btn btn-{{$setting->theme_color}} btn-md m-1" @click="New_Designation"><i class="i-Add text-white mr-2"></i>
                    {{ __('translate.Create') }}</a>
                @endcan
                @can('designation_delete')
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="delete_selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="designation_list_table" class="display table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('translate.Designation') }}</th>
                                <th>{{ __('translate.Company') }}</th>
                                <th>{{ __('translate.Department') }}</th>
                                <th>{{ __('translate.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($designations as $designation)
                            <tr>
                                <td @click="selected_row( {{ $designation->id}})"></td>
                                <td>{{$designation->designation}}</td>
                                <td>{{$designation->company->name}}</td>
                                <td>{{$designation->department->department}}</td>

                                <td>
                                    @can('designation_edit')
                                    <a @click="Edit_Designation( {{ $designation}})" class="ul-link-action text-success"
                                        data-toggle="tooltip" data-placement="top" title="{{ __('translate.Edit') }}">
                                        <i class="i-Edit"></i>
                                    </a>
                                    @endcan
                                    @can('designation_delete')
                                    <a @click="Remove_Designation( {{ $designation->id}})"
                                        class="ul-link-action text-danger mr-1" data-toggle="tooltip"
                                        data-placement="top" title="{{ __('translate.Delete') }}">
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

        <!-- Modal Add & Edit designation -->
        <div class="modal fade" id="Designation_Modal" tabindex="-1" role="dialog" aria-labelledby="Designation_Modal"
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

                        <form @submit.prevent="editmode?Update_Designation():Create_Designation()">
                            <div class="row">

                                <div class="col-md-12">
                                    <label class="ul-form__label">{{ __('translate.Company') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Company"
                                        placeholder="{{ __('translate.Choose_Company') }}"
                                        v-model="designation.company_id" :reduce="label => label.value"
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
                                    <label class="ul-form__label">{{ __('translate.Department') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Department"
                                        placeholder="{{ __('translate.Choose_Department') }}"
                                        v-model="designation.department_id" :reduce="label => label.value"
                                        :options="departments.map(departments => ({label: departments.department, value: departments.id}))">

                                    <template #no-options>
                                    {{ __('translate.Sorry, no matching options') }}
                                </template>
                            </v-select>

                                    <span class="error" v-if="errors && errors.department">
                                        @{{ errors.department[0] }}
                                    </span>
                                </div>

                                <div class="col-md-12">
                                    <label for="designation" class="ul-form__label">{{ __('translate.Designation') }}
                                        <span class="field_required">*</span></label>
                                    <input type="text" v-model="designation.designation" class="form-control"
                                        name="designation" id="designation"
                                        placeholder="{{ __('translate.Enter_Designation') }}">
                                    <span class="error" v-if="errors && errors.designation">
                                        @{{ errors.designation[0] }}
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
    Vue.component('v-select', VueSelect.VueSelect)
        var app = new Vue({
        el: '#section_Designation_list',
        data: {
            selectedIds:[],
            editmode: false,
            SubmitProcessing:false,
            departments: [],
            errors:[],
            designations: [], 
            companies :[],
            designation: {
                designation: "",
                company_id: "",
                department_id: "",
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

            Selected_Department(value) {
                if (value === null) {
                    this.designation.department_id = "";
                }
            },


            Selected_Company(value) {
                if (value === null) {
                    this.designation.company_id = "";
                    this.designation.department_id = "";
                }
                this.departments = [];
                this.designation.department_id = "";
                this.Get_departments_by_company(value);
            },


             
             //---------------------- Get_Data_Create ------------------------------\\
             Get_Data_Create() {
                axios
                    .get("/core/designations/create")
                    .then(response => {
                        this.companies   = response.data.companies;
                    })
                    .catch(error => {
                       
                    });
            },

              
             //---------------------- Get_Data_Edit ------------------------------\\
             Get_Data_Edit(id) {
                axios
                    .get("/core/designations/"+id+"/edit")
                    .then(response => {
                        this.companies   = response.data.companies;
                    })
                    .catch(error => {
                       
                    });
            },


            //---------------------- Get_departments_by_company ------------------------------\\
            Get_departments_by_company(value) {
                axios
                    .get("/core/Get_departments_by_company?id=" + value)
                    .then(({ data }) => (this.departments = data));
            },

            //------------------------------ Show Modal (Create designation) -------------------------------\\
            New_Designation() {
                this.reset_Form();
                this.editmode = false;
                this.Get_Data_Create();
                $('#Designation_Modal').modal('show');
            },

            //------------------------------ Show Modal (Update designation) -------------------------------\\
            Edit_Designation(designation) {
                this.editmode = true;
                this.reset_Form();
                this.Get_Data_Edit(designation.id);
                this.Get_departments_by_company(designation.company_id);
                this.designation = designation;
                $('#Designation_Modal').modal('show');
            },

            //----------------------------- Reset Form ---------------------------\\
            reset_Form() {
                this.designation = {
                    id: "",
                    designation: "",
                    company_id:"",
                    department_id: "",
                };
                this.errors = {};
            },

             //---------------------- Get all departments ------------------------------\\
            //  Get_all_departments() {
            //     axios
            //         .get("/core/Get_all_departments")
            //         .then(({ data }) => (this.departments = data));
            // },
            
            //------------------------ Create designation ---------------------------\\
            Create_Designation() {
                var self = this;
                self.SubmitProcessing = true;
                axios.post("/core/designations", {
                    designation: self.designation.designation,
                    company_id: self.designation.company_id,
                    department: self.designation.department_id,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/core/designations'; 
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

           //----------------------- Update designation ---------------------------\\
            Update_Designation() {
                var self = this;
                self.SubmitProcessing = true;
                axios.put("/core/designations/" + self.designation.id, {
                    designation: self.designation.designation,
                    company_id: self.designation.company_id,
                    department: self.designation.department_id,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/core/designations'; 
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

             //--------------------------------- Remove designation ---------------------------\\
            Remove_Designation(id) {

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
                            .delete("/core/designations/" + id)
                            .then(() => {
                                window.location.href = '/core/designations'; 
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
                        .post("/core/designations/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                            .then(() => {
                                window.location.href = '/core/designations'; 
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

        $('#designation_list_table').DataTable( {
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