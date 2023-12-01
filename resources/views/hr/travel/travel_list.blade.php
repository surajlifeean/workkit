<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
<?php 
DB::table('notifications')
    ->where('is_seen', 0)
    ->where('is_superadmin', 0)
    ->whereNull('leave_id')
    ->update(['is_seen' => 1]);

?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Employee Expense List') }}</h1>
    <ul>
        <li><a href="/       /travel">{{ __('translate.Employee Expenses') }}</a></li>
        <li>{{ __('translate.Employee Expense List') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Travel_list">
    <div class="col-md-12">
        <div class="card text-left">
            <div class="card-header text-right bg-transparent">
               
                <a class="btn btn-{{$setting->theme_color}} btn-md m-1" @click="New_Travel"><i class="i-Add text-white mr-2"></i>
                    {{ __('translate.Create') }}</a>
          
                @can('travel_delete')
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="delete_selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="travel_list_table" class="display table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('translate.Employee') }}</th>
                                <th>{{ __('translate.Company') }}</th>
                                <th>{{ __('translate.Type') }}</th>
                                <th>{{ __('translate.Start_Date') }}</th>
                                <th>{{ __('translate.Finish_Date') }}</th>
                                <th>{{ __('translate.Purpose_of_visit') }}</th>
                                <th>{{ __('translate.Expected_Budget') }}</th>
                                <th>{{ __('translate.Actual_Budget') }}</th>
                                <th>{{ __('translate.Status') }}</th>
                                <th>{{ __('translate.Attachment') }}</th>
                                <th>{{ __('translate.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($travels as $travel)
                            <tr>
                                <td @click="selected_row( {{ $travel->id}})"></td>
                                <td>{{$travel->employee->username ?? 'N/A'}}</td>
                                <td>{{$travel->company->name}}</td>
                                <td>{{ $travel->expenseCategory->title ?? 'N/A' }}</td>
                                <td>{{ $travel->start_date ? \Carbon\Carbon::parse($travel->start_date)->timezone($setting->timezone)->format('d/m/Y') : '' }}</td>
                                <td>{{ $travel->end_date ? \Carbon\Carbon::parse($travel->end_date)->timezone($setting->timezone)->format('d/m/Y') : '' }}</td>

                                <td>{{$travel->visit_purpose}}</td>
                                <td>{{$travel->expected_budget}}</td>
                                <td>{{$travel->actual_budget}}</td>
                                <td>{{ __('translate.'.$travel->status)}}</td>
                                <td>
                                    <a href="{{ asset('/assets/images/expenses/' . $travel->attachment) }}" target="_blank" onclick="openImageWindow(event)">
                                        <img src="{{ asset('/assets/images/expenses/' . $travel->attachment) }}" style="height: 2rem; width: 2rem;" alt="">
                                    </a>                                                        
                                </td>
                                <td>
                                   
                                    <a @click="Edit_Travel( {{ $travel}})" class="ul-link-action text-success"
                                        data-toggle="tooltip" data-placement="top" title="{{ __('translate.Edit') }}">
                                        <i class="i-Edit"></i>
                                    </a>
                                 
                              
                                    <a @click="Remove_Travel( {{ $travel->id}})" class="ul-link-action text-danger mr-1"
                                        data-toggle="tooltip" data-placement="top" title="{{ __('translate.Delete') }}">
                                        <i class="i-Close-Window"></i>
                                    </a>
                          
                                </td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Add & Edit Travel -->
        <div class="modal fade" id="Travel_Modal" tabindex="-1" role="dialog" aria-labelledby="Travel_Modal"
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

                        <form @submit.prevent="editmode?Update_Travel():Create_Travel()">
                            <div class="row">


                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Company') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Company"
                                        placeholder="{{ __('translate.Choose_Company') }}" v-model="travel.company_id"
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

                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Employee') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Employee"
                                        placeholder="{{ __('translate.Choose_Employee') }}" v-model="travel.employee_id"
                                        :reduce="label => label.value"
                                        :options="employees.map(employees => ({label: employees.username, value: employees.id}))">

                                    <template #no-options>
                                    {{ __('translate.Sorry, no matching options') }}
                                </template>
                            </v-select>

                                    <span class="error" v-if="errors && errors.employee_id">
                                        @{{ errors.employee_id[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Expense_Category') }} <span
                                            class="field_required">*</span></label>
                                    <select name="expense_type" id="expense_category_id" class="form-control">
                                        @foreach($exp_types as $exp)
                                           <option value="{{ $exp->id }}">{{ $exp->title }}</option>
                                        @endforeach
                                    </select>
 
                                </div>
                            
                                <div class="col-md-6">
                                    <label for="start_date" class="ul-form__label">{{ __('translate.Start_Date') }}
                                        <span class="field_required">*</span></label>

                                    <vuejs-datepicker id="start_date" name="start_date"
                                        placeholder="{{ __('translate.Enter_Start_date') }}" v-model="travel.start_date"
                                        input-class="form-control" format="dd-MM-yyyy"
                                        @closed="travel.start_date=formatDate(travel.start_date)">
                                    </vuejs-datepicker>

                                    <span class="error" v-if="errors && errors.start_date">
                                        @{{ errors.start_date[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="end_date" class="ul-form__label">{{ __('translate.Finish_Date') }} <span
                                            class="field_required">*</span></label>

                                    <vuejs-datepicker id="end_date" name="end_date"
                                        placeholder="{{ __('translate.Enter_Finish_date') }}" v-model="travel.end_date"
                                        input-class="form-control" format="dd-MM-yyyy"
                                        @closed="travel.end_date=formatDate(travel.end_date)">
                                    </vuejs-datepicker>

                                    <span class="error" v-if="errors && errors.end_date">
                                        @{{ errors.end_date[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="visit_purpose"
                                        class="ul-form__label">{{ __('translate.Purpose_of_visit') }} <span
                                            class="field_required">*</span></label>
                                    <input type="text" v-model="travel.visit_purpose" class="form-control"
                                        name="visit_purpose" placeholder="{{ __('translate.Enter_Purpose_of_visit') }}"
                                        id="visit_purpose">
                                    <span class="error" v-if="errors && errors.visit_purpose">
                                        @{{ errors.visit_purpose[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="visit_place" class="ul-form__label">{{ __('translate.Place_of_visit') }}
                                        <span class="field_required">*</span></label>
                                    <input type="text" v-model="travel.visit_place" class="form-control"
                                        name="visit_place" placeholder="{{ __('translate.Enter_Place_of_visit') }}"
                                        id="visit_place">
                                    <span class="error" v-if="errors && errors.visit_place">
                                        @{{ errors.visit_place[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Modes_of_Travel') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_travel_mode"
                                        placeholder="{{ __('translate.Choose_Modes_of_Travel') }}"
                                        v-model="travel.travel_mode" :reduce="(option) => option.value" :options="
                                            [
                                                {label: 'Bus', value: 'bus'},
                                                {label: 'Train', value: 'train'},
                                                {label: 'Car', value: 'car'},
                                                {label: 'Taxi', value: 'taxi'},
                                                {label: 'Air', value: 'air'},
                                                {label: 'Other', value: 'other'},
                                            ]">
                                    <template #no-options>
                                    {{ __('translate.Sorry, no matching options') }}
                                </template>
                            </v-select>

                                    <span class="error" v-if="errors && errors.travel_mode">
                                        @{{ errors.travel_mode[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="expected_budget"
                                        class="ul-form__label">{{ __('translate.Expected_Budget') }} <span
                                            class="field_required">*</span></label>
                                    <input type="text" v-model="travel.expected_budget" class="form-control"
                                        name="expected_budget" placeholder="{{ __('translate.Enter_Expected_Budget') }}"
                                        id="expected_budget">
                                    <span class="error" v-if="errors && errors.expected_budget">
                                        @{{ errors.expected_budget[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="actual_budget"
                                        class="ul-form__label">{{ __('translate.Actual_Budget') }} <span
                                            class="field_required">*</span></label>
                                    <input type="text" v-model="travel.actual_budget" class="form-control"
                                        name="actual_budget" placeholder="{{ __('translate.Enter_Actual_Budget') }}"
                                        id="actual_budget">
                                    <span class="error" v-if="errors && errors.actual_budget">
                                        @{{ errors.actual_budget[0] }}
                                    </span>
                                </div>

                                @if(auth()->user()->role_users_id == 4 || auth()->user()->role_users_id == 1)
                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Status') }} <span
                                            class="field_required">*</span></label>
                                    
                                    <select name="travel_status" id="travel_status" class="form-control">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                    <span class="error" v-if="errors && errors.status">
                                        @{{ errors.status[0] }}
                                    </span>
                                </div>
                                @endif

                                <div class="col-md-6">
                                    <label for="attachment" class="ul-form__label">{{ __('translate.Attachment') }}</label>
                                    <input name="attachment" @change="changeAttachement" type="file" class="form-control"
                                        id="attachment">
                                    <span class="error" v-if="errors && errors.attachment">
                                        @{{ errors.attachment[0] }}
                                    </span>
                                </div>

                                <div class="col-md-12">
                                    <label for="description"
                                        class="ul-form__label">{{ __('translate.Please_provide_any_details') }}</label>
                                    <textarea type="text" v-model="travel.description" class="form-control"
                                        name="description" id="description"
                                        placeholder="{{ __('translate.Please_provide_any_details') }}"></textarea>
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
<script src="{{asset('assets/js/vendor/vuejs-datepicker/vuejs-datepicker.min.js')}}"></script>


<script>
    Vue.component('v-select', VueSelect.VueSelect)

        var app = new Vue({
        el: '#section_Travel_list',
        components: {
            vuejsDatepicker
        },
        data: {
            selectedIds:[],
            editmode: false,
            SubmitProcessing:false,
            companies:[],
            employees:[],
            arrangement_types:[],
            accounts: @json($accounts),
            errors:[],
            travels: [], 

            travel: {
                company_id: "",
                employee_id: "",
                arrangement_type_id:"",
                expected_budget:"",
                actual_budget:"",
                start_date:"",
                end_date:"",
                visit_purpose:"",
                visit_place:"",
                travel_mode:"",
                description:"",
                status:"",
                attachment:"",
                expense_category_id:""
            },
         
        },
       
        methods: {

            changeAttachement(e){
                let file = e.target.files[0];
                this.travel.attachment = file;
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

            formatDate(d){
                var m1 = d.getMonth()+1;
                var m2 = m1 < 10 ? '0' + m1 : m1;
                var d1 = d.getDate();
                var d2 = d1 < 10 ? '0' + d1 : d1;
                return [d.getFullYear(), m2, d2].join('-');
            },

            Selected_Status(value) {
                if (value === null) {
                    this.travel.status = "";
                }
                console.log(this.travel.status);
            },


            //------------------------------ Show Modal (Create Travel) -------------------------------\\
            New_Travel() {
                this.reset_Form();
                this.editmode = false;
                this.Get_Data_Create();
                $('#Travel_Modal').modal('show');
            },

            //------------------------------ Show Modal (Update Travel) -------------------------------\\
            Edit_Travel(travel) {
                this.editmode = true;
                this.reset_Form();
                this.Get_Data_Edit(travel.id);
                this.Get_employees_by_company(travel.company_id);
                this.travel = travel;
                this.travel.attachment = '';
                $('#Travel_Modal').modal('show');
            },
            

            Selected_travel_mode(value) {
                if (value === null) {
                    this.travel.employee_id = "";
                }
            }, 

            Selected_Company(value) {
                if (value === null) {
                    this.travel.company_id = "";
                }
                this.employees = [];
                this.travel.employee_id = "";
                this.Get_employees_by_company(value);
            },

            Selected_Employee(value) {
                if (value === null) {
                    this.travel.travel_mode = "";
                }
            },

            Selected_Arrangement_Type(value) {
                if (value === null) {
                    this.travel.arrangement_type_id = "";
                }
            },
            //----------------------------- Reset Form ---------------------------\\
            reset_Form() {
                this.travel = {
                    id: "",
                    employee_id: "",
                    arrangement_type_id:"",
                    expected_budget:"",
                    actual_budget:"",
                    start_date:"",
                    end_date:"",
                    visit_purpose:"",
                    visit_place:"",
                    travel_mode:"",
                    description:"",
                    status:"",
                };
                this.errors = {};
            },

             //---------------------- Get_employees_by_company ------------------------------\\
            
             Get_employees_by_company(value) {
                axios
                .get("/Get_employees_by_company?id=" + value)
                .then(({ data }) => (this.employees = data));
            },


             //---------------------- Get_Data_Create  ------------------------------\\
             Get_Data_Create() {
                axios
                    .get("/hr/travel/create")
                    .then(response => {
                        this.companies   = response.data.companies;
                        this.arrangement_types   = response.data.arrangement_types;
                    })
                    .catch(error => {
                       
                    });
            },

              //---------------------- Get_Data_Edit  ------------------------------\\
              Get_Data_Edit(id) {
                axios
                    .get("/hr/travel/"+id+"/edit")
                    .then(response => {
                        this.companies   = response.data.companies;
                        this.arrangement_types   = response.data.arrangement_types;
                    })
                    .catch(error => {
                       
                    });
            },


              //------------------------ Create Travel ---------------------------\\
              Create_Travel() {
                var self = this;
                self.SubmitProcessing = true;
                let exp_data = new FormData();
                    // console.log($('#travel_status').val())
                    exp_data.append("company_id", self.travel.company_id);
                    exp_data.append("employee_id", self.travel.employee_id);
                    exp_data.append("arrangement_type_id", self.travel.arrangement_type_id);
                    exp_data.append("description", self.travel.description);
                    exp_data.append("expected_budget", self.travel.expected_budget);
                    exp_data.append("actual_budget", self.travel.actual_budget);
                    exp_data.append("start_date", self.travel.start_date);
                    exp_data.append("end_date", self.travel.end_date);
                    exp_data.append("visit_purpose", self.travel.visit_purpose)
                    exp_data.append("visit_place", self.travel.visit_place);
                    exp_data.append("travel_mode", self.travel.travel_mode);
                    exp_data.append("status", $('#travel_status').val());
                    exp_data.append("attachment", self.travel.attachment);
                    exp_data.append("expense_category_id", $('#expense_category_id').val());

                axios.post("/hr/travel", exp_data).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/hr/travel'; 
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

           //----------------------- Update Travel ---------------------------\\
            Update_Travel() {
                var self = this;
                self.SubmitProcessing = true;

                let exp_data_update = new FormData();

                exp_data_update.append("company_id", self.travel.company_id);
                exp_data_update.append("employee_id", self.travel.id);
                exp_data_update.append("arrangement_type_id", self.travel.arrangement_type_id);
                exp_data_update.append("description", self.travel.description);
                exp_data_update.append("expected_budget", self.travel.expected_budget);
                exp_data_update.append("actual_budget", self.travel.actual_budget);
                exp_data_update.append("start_date", self.travel.start_date);
                exp_data_update.append("end_date", self.travel.end_date);
                exp_data_update.append("visit_purpose", self.travel.visit_purpose)
                exp_data_update.append("visit_place", self.travel.visit_place);
                exp_data_update.append("travel_mode", self.travel.travel_mode);
                exp_data_update.append("status",  $('#travel_status').val());
                exp_data_update.append("attachment", self.travel.attachment);
                exp_data_update.append("expense_category_id", $('#expense_category_id').val());
                exp_data_update.append("_method", "put");

                axios.post("/hr/travel/" + self.travel.id, exp_data_update).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/hr/travel'; 
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

          

             //--------------------------------- Remove Travel ---------------------------\\
            Remove_Travel(id) {

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
                            .delete("/hr/travel/" + id)
                            .then(() => {
                                window.location.href = '/hr/travel'; 
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
                        .post("/hr/travel/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                            .then(() => {
                                window.location.href = '/hr/travel'; 
                                toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                            })
                            .catch(() => {
                                toastr.error('{{ __('translate.There_was_something_wronge') }}');
                            });
                    });
            },

            Selected_Account(value) {
            if (value === null) {
                this.expense.account_id = "";
            }
            },
    
            Selected_Category(value) {
                if (value === null) {
                    this.expense.expense_category_id = "";
                }
            },
    
    
            Selected_Payment_Method(value) {
                if (value === null) {
                    this.expense.payment_method_id = "";
                }
            },
    
    
            //------------------------ Create Expense ---------------------------\\
            Create_Expense() {
                var self = this;
                self.SubmitProcessing = true;
    
                self.data.append("account_id", self.expense.account_id);
                self.data.append("expense_category_id", self.expense.expense_category_id);
                self.data.append("amount", self.expense.amount);
                self.data.append("payment_method_id", self.expense.payment_method_id);
                self.data.append("date", self.expense.date);
                self.data.append("expense_ref", self.expense.expense_ref);
                self.data.append("description", self.expense.description);
                self.data.append("attachment", self.expense.attachment);
               
                axios.post("/accounting/expense", self.data)
                    .then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/accounting/expense'; 
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
        },
        //-----------------------------Autoload function-------------------
        created() {
        }

    })

</script>

<script type="text/javascript">
    $(function () {
      "use strict";

        $('#travel_list_table').DataTable( {
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

    function openImageWindow(event) {
        event.preventDefault();

        const imageUrl = event.currentTarget.href;
        
        // You can customize the window features as needed
        window.open(imageUrl, 'Image Window', 'width=800, height=600, resizable=yes');
    }
</script>
@endsection