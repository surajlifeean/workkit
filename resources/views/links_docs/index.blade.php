<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Docs_&_Links_Lists') }}</h1>
    <ul>
        <li><a href="/core/comp_docs_links">{{ __('translate.Docs_&_Links') }}</a></li>
        <li>{{ __('translate.Docs_&_Links_Lists') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Announcement_list">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header text-right bg-transparent">
                @can('comp_docs_link_add')
                <a class="btn btn-{{$setting->theme_color}} btn-md m-1" @click="New_Announcement"><i class="i-Add text-white mr-2"></i>
                    {{ __('translate.Create') }}</a>
                @endcan
                @can('comp_docs_link_delete')
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="Delete_Selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
                @endcan
            </div>
            <div class="card-body">
              
                <div class="table-responsive">
                    <table id="announcement_list_table" class="display table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('translate.Employee') }}</th>
                                <th>{{ __('translate.Phone') }}</th>
                                <th>{{ __('translate.Name') }}</th>
                                <th>{{ __('translate.Description') }}</th>
                                <th>{{ __('translate.Type') }}</th>
                                <th>{{ __('translate.Upload') }}</th>
                                <th>{{ __('translate.Date') }}</th>
                                <th>{{ __('translate.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($linksDocs as $ld)
                            <tr>
                                <td @click="selected_row( {{ $ld->id}})"></td>
                                <td>{{ $ld->username }}</td>
                                <td>{{ $ld->phone }}</td>
                                <td>{{ $ld->name }}</td>
                                <td>{{ $ld->description }}</td>
                                <td>
                                    @if ($ld->type == 'doc')
                                      Document
                                    @else
                                      Link    
                                    @endif
                                </td>
                                <td>
                                    {{ $ld->upload }}</td>
                                <td>{{ $ld->created_at }}</td>
                                <td>
                                    
                                   
                                    @if($ld->type == 'doc')
                                     <a  href="{{ asset('comp_docs/'. $ld->upload) }}" download="{{ $ld->upload }}"
                                         class="ul-link-action text-success" data-toggle="tooltip" data-placement="top"
                                         title="Download">
                                         <i class="i-Data-Download"></i>
                                     </a>
                                     @else
                                      <a  href="{{ $ld->upload }}"
                                        class="ul-link-action text-success" data-toggle="tooltip" data-placement="top"
                                        title="Open link"
                                        target="_blank">
                                        <i class="i-File-Link"></i>
                                      </a>
                                     @endif
                                     
                                    @can('comp_docs_link_delete')
                                    <a @click="Remove_Doc_Link( {{ $ld->id}} )"
                                        class="ul-link-action text-danger mr-1" data-toggle="tooltip"
                                        data-placement="top" title="Delete">
                                        <i class="i-Close-Window"></i>
                                    </a>
                                    @endcan
                                    @can('comp_docs_link_delete')
                                    <a @click="Update_Docs_Link( {{ $ld->id}} )"
                                        class="ul-link-action text-success mr-1" data-toggle="tooltip"
                                        data-placement="top" title="Update">
                                        <i class="i-Edit"></i>
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

        <!-- Modal Add & Edit Announcement -->
        <div class="modal fade" id="Announcement_Modal" tabindex="-1" role="dialog" aria-labelledby="Announcement_Modal"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="editmode" class="modal-title">{{ __('translate.Edit') }}</h5>
                        <h5 v-else class="modal-title">{{ __('translate.Create') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="Create_Links_Docs()" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name" class="ul-form__label">{{ __('translate.Name') }} <span
                                            class="field_required">*</span></label>
                                    <input type="text" v-model="links_docs.name" class="form-control" name="name"
                                        id="name" placeholder="{{ __('translate.Enter_title') }}">
                                    <span class="error" v-if="errors && errors.name">
                                        @{{ errors.name[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Choose_type') }} <span
                                            class="field_required">*</span></label>
                                   
                                            <v-select 
                                               placeholder="{{ __('translate.Choose_type') }}"
                                               v-model="links_docs.type"
                                               :reduce="label => label.value"
                                               :options="types.map(type => ({ label: type.up, value: type.name }))">
                                            </v-select>


                                    <span class="error" v-if="errors && errors.type">
                                        @{{ errors.type[0] }}
                                    </span>
                                </div>                                   

                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Company') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Company"
                                        placeholder="{{ __('translate.Choose_Company') }}"
                                        v-model="announcements.company_id" :reduce="label => label.value"
                                        :options="companies.map(companies => ({label: companies.name, value: companies.id}))">
                                    </v-select>

                                    <span class="error" v-if="errors && errors.company_id">
                                        @{{ errors.company_id[0] }}
                                    </span>
                                </div>


                                <div class="col-md-6">
                                    <label for="all_em" class="ul-form__label">{{ __('translate.All_Employees') }}
                                    </label>
                                    <div class="d-flex align-items-center justify-content-start">
                                     <input type="checkbox" v-model="links_docs.all_em" class="form-check mr-2"
                                        name="links_docs.all_em" id="all_em" placeholder="{{ __('translate.All_Employees') }}">
                                        <h6 class=" m-0">{{ __('translate.All_Employees') }}</h6>
                                    </div>
                                    
                                    <span class="error" v-if="errors && errors.all_em">
                                        @{{ errors.all_em[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6" v-if="links_docs.all_em !== true">
                                    <label class="ul-form__label">{{ __('translate.Choose_Employees') }}</label>
                                    <v-select multiple
                                        placeholder="{{ __('translate.Choose_Employees') }}" v-model="links_docs.employees_id"
                                        :reduce="label => label.value"
                                        :options="employees.map(employees => ({label: employees.username, value: employees.id}))">
                                    </v-select>
                                    <span class="error" v-if="errors && errors.employees_id">
                                        @{{ errors.employees_id[0] }}
                                    </span>
                                </div>
                                
                                <div class="col-md-6" v-if="links_docs.type === 'doc' || links_docs.type === '' ">
                                    <label class="ul-form__label">{{ __('translate.Upload_Document') }} <span class="field_required">*</span></label>
                                    <input type="file" ref="fileInput" name="upload" id="upload" @change="change_Upload" accept=".pdf,.doc,.docx,.png,.jpg,image/*" />
                                    <span class="error" v-if="errors && errors.upload">
                                        @{{ errors.upload[0] }}
                                    </span>
                                    <span class="error" v-if="errors && errors.upload_or_link">
                                        @{{ errors.upload_or_link[0] }}
                                    </span>
                                </div>
                                
                                
                                <div class="col-md-6" v-else-if="links_docs.type === 'link'">
                                    <label class="ul-form__label">{{ __('translate.Link') }} <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" name="link" id="name" v-model="links_docs.link" placeholder="{{ __('translate.Link') }}" />
                                    <span class="error" v-if="errors && errors.link">
                                        @{{ errors.link[0] }}
                                    </span>
                                    <span class="error" v-if="errors && errors.upload_or_link">
                                        @{{ errors.upload_or_link[0] }}
                                    </span>
                                </div>




                                <div class="col-md-12">
                                    <label for="description"
                                        class="ul-form__label">{{ __('translate.Detailed_Description') }}</label>
                                    <textarea type="text" v-model="links_docs.description" class="form-control"
                                        name="description" id="description"
                                        placeholder="{{ __('translate.Enter_description') }}"></textarea>
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

        <div class="modal fade" id="update_docs_links_Modal" tabindex="-1" role="dialog" aria-labelledby="update_docs_links_Modal"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5  class="modal-title">{{ __('translate.Edit') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="Update_Doc_Link()" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name" class="ul-form__label">{{ __('translate.Name') }} <span
                                            class="field_required">*</span></label>
                                    <input type="text" v-model="em_links_docs.name" class="form-control" name="name"
                                        id="name" placeholder="{{ __('translate.Enter_title') }}">
                                    <span class="error" v-if="errors && errors.name">
                                        @{{ errors.name[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Choose_type') }} <span
                                            class="field_required">*</span></label>
                                   
                                            <v-select 
                                               placeholder="{{ __('translate.Choose_type') }}"
                                               v-model="em_links_docs.type"
                                               :reduce="label => label.value"
                                               :options="types.map(type => ({ label: type.up, value: type.name }))">
                                            </v-select>


                                    <span class="error" v-if="errors && errors.type">
                                        @{{ errors.type[0] }}
                                    </span>
                                </div>                                   

                               
                                
                                <div class="col-md-6" v-if="em_links_docs.type === 'doc' || em_links_docs.type === '' ">
                                    <label class="ul-form__label">{{ __('translate.Upload_Document') }} <span class="field_required">*</span></label>
                                    <input type="file" ref="fileInput" name="upload" id="upload" @change="change_Upload" accept=".pdf,.doc,.docx,.png,.jpg,image/*" />
                                    <span class="error" v-if="errors && errors.upload">
                                        @{{ errors.upload[0] }}
                                    </span>
                                    
                                </div>
                                
                                
                                <div class="col-md-6" v-else-if="em_links_docs.type === 'link'">
                                    <label class="ul-form__label">{{ __('translate.Link') }} <span class="field_required">*</span></label>
                                    <input type="text" class="form-control" name="link" id="link" v-model="em_links_docs.link" placeholder="{{ __('translate.Link') }}" />
                                    <span class="error" v-if="errors && errors.link">
                                        @{{ errors.link[0] }}
                                    </span>
                                
                                </div>




                                <div class="col-md-12">
                                    <label for="description"
                                        class="ul-form__label">{{ __('translate.Detailed_Description') }}</label>
                                    <textarea type="text" v-model="em_links_docs.description" class="form-control"
                                        name="description" id="description"
                                        placeholder="{{ __('translate.Enter_description') }}"></textarea>
                                    <span class="error" v-if="errors && errors.description">
                                        @{{ errors.description[0] }}
                                    </span>
                                </div>

                            </div>


                            <div class="row mt-3">

                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-{{$setting->theme_color}}" :disabled="SubmitProcessingEdit">
                                        {{ __('translate.Submit') }}
                                    </button>
                                    <div v-once class="typo__p" v-if="SubmitProcessingEdit">
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
<script src="{{asset('assets/js/vendor/vuejs-datepicker/vuejs-datepicker.min.js')}}"></script>


<script>
    Vue.component('v-select', VueSelect.VueSelect)
        var app = new Vue({
        el: '#section_Announcement_list',
        components: {
            vuejsDatepicker
        },
        data: {
            data: new FormData(),
      
            selectedIds:[],
            editmode: false,
            SubmitProcessing:false,
            SubmitProcessingEdit:false,
            errors:[],
            em_links_docs: [],
            types: [
                {'name': 'doc' , 'up': 'Document'},
                {'name': 'link', 'up': 'Link'}
            ],
            employees: [], 
            companies: [],
            departments: [],
            announcements: {}, 
            all_department : {
                id :"null",
                department :'all departments',
            },
       
            links_docs: {
                name: "",
                all_em: "",
                upload: "",
                employee_id: "",
                type: "",
                description: "",
                link: "",
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

            formatDate(d){
                var m1 = d.getMonth()+1;
                var m2 = m1 < 10 ? '0' + m1 : m1;
                var d1 = d.getDate();
                var d2 = d1 < 10 ? '0' + d1 : d1;
                return [d.getFullYear(), m2, d2].join('-');
            },

            Selected_Department(value) {
                if (value === null) {
                    this.announcement.department_id = "";
                }
            },

            //------------------------------ Show Modal (Create announcement) -------------------------------\\
            New_Announcement() {
                this.reset_Form();
                this.departments = [];
                this.editmode = false;
                this.Get_Data_Create();
                $('#Announcement_Modal').modal('show');
            },

            //------------------------------ Show Modal (Update Announcement) -------------------------------\\
            Edit_Announcement(announcement) {
                this.editmode = true;
                this.reset_Form();
                this.Get_Data_Edit(announcement.id);
                this.Get_departments_by_company(announcement.company_id);
                this.announcement = announcement;
                if(announcement.department_id === null){
                    this.announcement.department_id = "null";
                }
                $('#Announcement_Modal').modal('show');
            },

            Selected_Company(value) {
                if (value === null) {
                    this.announcement.company_id = "";
                    this.announcement.department_id = "";
                    this.departments = [];
                }else{
                    this.departments = [];
                    this.announcement.department_id = "";
                
                    this.Get_employees_by_company(value);
                }
            },

            //---------------------- Get_employees_by_company ------------------------------\\
            
            Get_employees_by_company(value) {
                axios
                .get("/Get_employees_by_company?id=" + value)
                .then(({ data }) => (this.employees = data));
            },
            
             //---------------------- Get Data Create  ------------------------------\\
             Get_Data_Create() {
                axios
                    .get("/core/announcements/create")
                    .then(response => {
                        this.companies   = response.data.companies;
                    })
                    .catch(error => {
                       
                    });
            },

             
             //---------------------- Get Data Edit  ------------------------------\\
             Get_Data_Edit(id) {
                axios
                    .get("/core/announcements/"+id+"/edit")
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
                    .then(response => {
                        this.departments = response.data;
                        
                    })
                    .catch(error => {
                       
                    });
            },

            //----------------------------- Reset Form ---------------------------\\
            reset_Form() {
                this.announcement = {
                    id: "",
                    title: "",
                    description:"",
                    summary:"",
                    company_id:"",
                    department_id:"",
                    start_date:"",
                    end_date:"",
                };
                this.errors = {};
            },
            //------------------------------ upload ------------------------------------\\
            
            change_Upload(e) {
                let file = e.target.files[0];
                console.log(file);
                this.links_docs.upload = file;
            },
            // change_Upload_Edit(e) {
            //     let file = e.target.files[0];
            //     console.log(file);
            //     this.em_links_docs.upload = file;
            // },


            //------------------------ Create Links and Docs ---------------------------\\
            Create_Links_Docs() {
                var self = this;
                self.SubmitProcessing = true;
                self.data.append('name', this.links_docs.name);
                self.data.append('type', this.links_docs.type);
                self.data.append('company_id', this.announcements.company_id);
                self.data.append('all_em', this.links_docs.all_em);
                self.data.append('employees_id', this.links_docs.employees_id);
                console.log('File data:', this.links_docs.upload);
                self.data.append('upload', this.links_docs.upload);
                self.data.append('link', this.links_docs.link);
                self.data.append('description', this.links_docs.description);

                axios.post("/core/comp_docs_links", self.data, {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            },
                        }).then(response => {
                        self.SubmitProcessing = false;
                        console.log(response.data)
                        window.location.href = '/core/comp_docs_links'; 
                        toastr.success('{{ __('translate.Created_in_successfully') }}');
                        self.errors = {};
                })
                .catch(error => {
                    self.SubmitProcessing = false;
                    if (error.response.status == 422) {
                        // console.log(error.response.data.errors);
                        self.errors = error.response.data.errors;
                        console.log(this.errors);
                    }
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },

           //----------------------- Update Docs & Llinks ---------------------------\\
           Update_Docs_Link(id){
                this.Get_Em_Data(id);
                $('#update_docs_links_Modal').modal('show');
           },

           Get_Em_Data(id){
              axios
                .get("/core/get_Em_Data/" + id)
                    .then(response => {
                        console.log(response.data)
                        this.em_links_docs = response.data;
                    })
                    .catch(error => {
                       
                    });
           },

            Update_Doc_Link() {
                var self = this;
                self.SubmitProcessingEdit = true;
                console.log(this.em_links_docs)
                self.data.append('name', this.em_links_docs.name);
                self.data.append('type', this.em_links_docs.type);
                self.data.append('description', this.em_links_docs.description);
            
                if (this.em_links_docs.type === 'doc') {
                    self.data.append('upload', this.links_docs.upload);
                } else {
                    self.data.append('link', this.em_links_docs.link);
                }
                self.data.append("_method", "put");

                console.log(self.data)
                axios.post("/core/comp_docs_links/" + self.em_links_docs.id, self.data)
                .then(response => {
                    self.SubmitProcessingEdit = false;
                    console.log(response.data);
                    window.location.href = '/core/comp_docs_links'; 
                    toastr.success('{{ __('translate.Updated_in_successfully') }}');
                    self.errors = {};
                }).catch(error => {
                    self.SubmitProcessingEdit = false;
                    console.log(error.response.data.errors);
                    if (error.response.status == 422) {
                        self.errors = error.response.data.errors;
                    }
                    toastr.error('{{ __('translate.There_was_something_wronge') }}');
                });
            },


             //--------------------------------- Remove Announcement ---------------------------\\
            Remove_Doc_Link(id) {

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
                            .delete("/core/comp_docs_links/" + id)
                            .then((response) => {
                                console.log(response.data);
                                window.location.href = '/core/comp_docs_links'; 
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
            },

                
            //--------------------------------- delete_selected ---------------------------\\
            // Remove_Docs_Links(id){
             
            // },
            
            Delete_Selected() {
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
                        .post("/core/comp_docs_links/multiple_ids", {
                            selectedIds: self.selectedIds,
                        })
                            .then((res) => {
                                console.log(res);
                                // window.location.href = '/core/comp_docs_links'; 
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

        $('#announcement_list_table').DataTable( {
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