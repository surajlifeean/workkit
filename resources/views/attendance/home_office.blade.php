<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/vue2-clock-picker/vue2-clock-picker.min.css')}}">
<style>
    .i-Add{
        font-weight: bold;
        font-size: 1rem;
    }
</style>

@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Home_Office_List') }}</h1>
    <ul>
        <li><a href="/attendances">{{ __('translate.Home_Office') }}</a></li>
        <li>{{ __('translate.Home_Office_List') }}</li>
    </ul>
    {{-- @dump($otherEmployees) --}}
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_attendance_list">
    <div class="col-md-12">
        <div class="card text-left">
            {{-- <div class="card-header text-right bg-transparent">
                @can('attendance_add')
                <a class="btn btn-primary btn-md m-1" @click="New_Attendance"><i
                        class="i-Add text-white mr-2"></i>{{ __('translate.Create') }}</a>
                @endcan
                @can('attendance_delete')
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="delete_selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
                @endcan
            </div> --}}
            <div class="card-body">
                <div class="table-responsive">
                    <div class="row mb-3">
                        <div class="col-md-3 mb-2">
                            <select id="customFilterDep" class="form-select form-control">
                                 <option value="">Chose department</option>
                                 @foreach($otherEmployees as $item)
                                  <option value="{{$item->department}}">{{$item->department}}</option>
                                 @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 ">
                            <select id="customFilterDesignation" class="form-select form-control">
                                <option value="">Chose designation</option>
                                 @foreach($otherEmployees as $item)
                                  <option value="{{$item->designation}}">{{$item->designation}}</option>
                                 @endforeach                            
                            </select>
                        </div>
                    </div>
                    <table id="attendance_list_table" class="display table">
                        <thead>
                            <tr>
                                <th class="align-middle">#</th>
                                <th class="align-middle">{{ __('translate.Employee') }}</th>
                                <th>{{ __('translate.Mon') }}</th>
                                <th>{{ __('translate.Tue') }}</th>
                                <th>{{ __('translate.Wed') }}</th>
                                <th>{{ __('translate.Thu') }}</th>
                                <th>{{ __('translate.Fri') }}</th>
                                <th>{{ __('translate.Sat') }}</th>
                                <th>{{ __('translate.Sun') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                             <tr id="self_row">
                                <td class="align-middle"></td>
                                <td class="align-middle">{{ $employee->username }}</td>
                             </tr>
                             @foreach ($otherEmployees as $item)
                                <tr id="em{{$item->employee_id}}">
                                    <td class="align-middle"></td>
                                    <td class="align-middle">{{ $item->username }} 
                                        <span hidden>{{ $item->designation }} {{ $item->department }}</span></td>
                                    <td class="monday-cell"></td>
                                    <td class="tuesday-cell"></td>
                                    <td class="wednesday-cell"></td>
                                    <td class="thursday-cell"></td>
                                    <td class="friday-cell"></td>
                                    <td class="saturday-cell"></td>
                                    <td class="sunday-cell"></td>
                                </tr>
                             @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Add & Edit Attendance -->
        <div class="modal fade" id="Attendance_Modal" tabindex="-1" role="dialog" aria-labelledby="Attendance_Modal"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="editmode" class="modal-title">
                            <th>{{ __('translate.Edit') }}</th>
                        </h5>
                        <h5 v-else class="modal-title">
                            <th>{{ __('translate.Create') }}</th>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form @submit.prevent="editmode?Update_Attendance():Create_Attendance()">
                            <div class="row">

                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Company') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Company"
                                        placeholder="{{ __('translate.Choose_Company') }}"
                                        v-model="attendance.company_id" :reduce="label => label.value"
                                        :options="companies.map(companies => ({label: companies.name, value: companies.id}))">
                                    </v-select>

                                    <span class="error" v-if="errors && errors.company_id">
                                        @{{ errors.company_id[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label class="ul-form__label">{{ __('translate.Employee') }} <span
                                            class="field_required">*</span></label>
                                    <v-select @input="Selected_Employee"
                                        placeholder="{{ __('translate.Choose_Employee') }}"
                                        v-model="attendance.employee_id" :reduce="label => label.value"
                                        :options="employees.map(employees => ({label: employees.username, value: employees.id}))">

                                    </v-select>

                                    <span class="error" v-if="errors && errors.employee_id">
                                        @{{ errors.employee_id[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="date" class="ul-form__label">{{ __('translate.Date') }} <span
                                            class="field_required">*</span></label>

                                    <vuejs-datepicker id="date" name="date"
                                        placeholder="{{ __('translate.Enter_Attendance_date') }}"
                                        v-model="attendance.date" input-class="form-control" format="yyyy-MM-dd"
                                        @closed="attendance.date=formatDate(attendance.date)">
                                    </vuejs-datepicker>

                                    <span class="error" v-if="errors && errors.date">
                                        @{{ errors.date[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="clock_in" class="ul-form__label">{{ __('translate.Time_In') }} <span
                                            class="field_required">*</span></label>

                                    <vue-clock-picker v-model="attendance.clock_in"
                                        placeholder="{{ __('translate.Time_In') }}" name="clock_in" id="clock_in">
                                    </vue-clock-picker>
                                    <span class="error" v-if="errors && errors.clock_in">
                                        @{{ errors.clock_in[0] }}
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <label for="clock_out" class="ul-form__label">{{ __('translate.Time_Out') }} <span
                                            class="field_required">*</span></label>

                                    <vue-clock-picker v-model="attendance.clock_out"
                                        placeholder="{{ __('translate.Time_Out') }}" name="clock_out" id="clock_out">
                                    </vue-clock-picker>
                                    <span class="error" v-if="errors && errors.clock_out">
                                        @{{ errors.clock_out[0] }}
                                    </span>
                                </div>

                            </div>


                            <div class="row mt-3">

                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary" :disabled="SubmitProcessing">
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
<script src="{{asset('assets/js/vendor/vue2-clock-picker/vue2-clock-picker.plugin.js')}}"></script>
<script src="{{asset('assets/js/vendor/vuejs-datepicker/vuejs-datepicker.min.js')}}"></script>

<script>
    var wfhDates = @json($work_from_home);
    var shifts = @json($officeShift);
    var otherEmployees = @json($otherEmployees);
    
    function getCurrentWeekDates() {
        var currentDate = new Date();
        var dayOfWeek = currentDate.getDay(); 
        var startDate = new Date(currentDate);
        startDate.setDate(currentDate.getDate() - dayOfWeek); 
     
        var dateCells = [];

        for (var i = 0; i < 7; i++) {
            var cellDate = new Date(startDate);
            cellDate.setDate(startDate.getDate() + (i + 1));
            dateCells.push(cellDate);
        }

        return dateCells;
    }

    // Get the current week's dates
    var weekDates = getCurrentWeekDates();

    // Select the table header row and its cells
    var headerRow = $('table thead tr');
    var headerCells = headerRow.find('th');

    for (var i = 2; i < headerCells.length; i++) {
        var formattedDate = formatDate(weekDates[i - 2]);
        $(headerCells[i]).append('<br>' + formattedDate);
        
        checkWorkFromHome(formattedDate);
    }
 
    function formatDate(date) {
        var day = date.getDate().toString().padStart(2, '0');
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var year = date.getFullYear();
        return  year + '-' + month + '-' + day ;
    }

    function getWorkScheduleForDate(date, data) {
        const dateTime = new Date(date);
        const dayOfWeek = dateTime.getDay();
        const dayNames = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
    
        const dayName = dayNames[dayOfWeek];
        if (data[dayName + "_in"] && data[dayName + "_out"]) {
            const inTime = data[dayName + "_in"];
            const outTime = data[dayName + "_out"];
            
            return inTime + " - " + outTime;
        } else {
            return "No schedule available for " + dayName;
        }
    }

    function getDayName(dateString) {
         var date = new Date(dateString);       
         var daysOfWeek = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];       
         var dayIndex = date.getDay();       
         var dayName = daysOfWeek[dayIndex];       
         return dayName.toLowerCase();
    }

    function checkWorkFromHome(date){
        let shiftTime = getWorkScheduleForDate(date, shifts);
        if (wfhDates.includes(date)) {
            $('#self_row').append(`
               <td class="align-middle ${formattedDate}">
                   <a onclick="Wfh('${formattedDate}', 'From Home')" class="from-home d-flex align-items-center flex-column justify-content-center d-none  w-100px py-1 px-1" style="border: 2px solid #ff000040;border-radius: 0.5rem;box-shadow: 3px 4px 6px rgba(0, 0, 0, 0.1);">
                    <p class="text-dark m-0 mb-1 " style="font-size: 10px;">${shiftTime}</p>
                    <p class="text-dark font-weight-bold m-0">From Home</p>
                    <input class="border-0 d-none" value="${formattedDate}">
                  </a>
              </td>
            `);
        } else {
         $('#self_row').append(`
           <td class="align-middle ${formattedDate}">
             <a onclick="Wfh('${formattedDate}', 'From Office')" class="from-office text-success p-1 " style="border-radius: 100%;"><i class="i-Add"></i>
             <input class="border-0 d-none" value="${formattedDate}"></a>
           </td>
         `);

        }
       
       
    }

    function Wfh(date, type){
                axios.post('/work_from_home', {
                    work_from_home_date: date,
                    type: type,
                })
                     .then(response => {
                       console.log(response.data);
                       toastr.success('{{ __('translate.Created_in_successfully') }}');
                       let shiftTime = getWorkScheduleForDate(date, shifts);

                       if(type === 'From Office'){
                        $(`.${date}`).empty().append(`
                        <a onclick="Wfh('${date}', 'From Home')" class="from-home d-flex align-items-center flex-column justify-content-center d-none  w-100px py-1 px-1" style="border: 2px solid #ff000040;border-radius: 0.5rem;box-shadow: 3px 4px 6px rgba(0, 0, 0, 0.1);">
                          <p class="text-dark m-0 mb-1 " style="font-size: 10px;">${shiftTime}</p>
                          <p class="text-dark font-weight-bold m-0">From Home</p>
                          <input class="border-0 d-none" value="${date}">
                        </a>
                        `);
                       }else{
                        $(`.${date}`).empty().append(`
                          <a onclick="Wfh('${date}', 'From Office')" class="from-office text-success p-1 " style="border-radius: 100%;"><i class="i-Add"></i>
                          <input class="border-0 d-none" value="${date}"></a>
                       `);
                       }
                     
                     })
                     .catch(error => {
                       console.error(error);
                       toastr.error('{{ __('translate.There_was_something_wronge') }}');
                     });
    }
     
    
    otherEmployees?.forEach(element => {
        console.log(element)
        element.from_home.forEach(e => {
           let shiftsTime = getWorkScheduleForDate(e, element.shifts);
           let day = getDayName(e);
           $(`#em${element.employee_id} .${day}-cell`).append(`
                <a  class="from-home d-flex align-items-center flex-column justify-content-center d-none  w-100px py-1 px-1" style="border: 2px solid #ff000040;border-radius: 0.5rem;box-shadow: 3px 4px 6px rgba(0, 0, 0, 0.1);">
                  <p class="text-dark m-0 mb-1 " style="font-size: 10px;">${shiftsTime}</p>
                  <p class="text-dark font-weight-bold m-0">From Home</p>
                </a>
           `);
        })
        // let dataNdate = getWorkScheduleForDate(element.);
        
    });
    
    
</script>

<script>
    Vue.use(VueClockPickerPlugin)
        Vue.component('v-select', VueSelect.VueSelect)

        var app = new Vue({
        el: '#section_attendance_list',
        components: {
            vuejsDatepicker
        },
        data: {
            SubmitProcessing:false,
            editmode: false,
            selectedIds:[],
            companies: [],
            employees:[],
            errors:[],
            attendances: [], 
            attendance: {
                company_id: "",
                employee_id: "",
                date:"",
                clock_in:"",
                clock_out:"",
            }, 

        },
       
        methods: {
            //--------------------------------     Adding the Wfh ------------------------\\
             
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

             //------------------------------ Show Modal (Create attendance) -------------------------------\\
             New_Attendance() {
                this.reset_Form();
                this.editmode = false;
                this.Get_all_companies();
                $('#Attendance_Modal').modal('show');
            },

              //------------------------------ Show Modal (Update attendance) -------------------------------\\
              Edit_Attendance(attendance) {
                this.editmode = true;
                this.reset_Form();
                this.Get_all_companies();
                this.Get_employees_by_company(attendance.company_id);
                this.attendance = attendance;
                $('#Attendance_Modal').modal('show');
            },

            Selected_Company(value) {
                if (value === null) {
                    this.attendance.company_id = "";
                }
                this.employees = [];
                this.attendance.employee_id = "";
                this.Get_employees_by_company(value);
            },

            Selected_Employee(value) {
                if (value === null) {
                    this.attendance.employee_id = "";
                }
            },

            //---------------------- Get_employees_by_company ------------------------------\\
            
            Get_employees_by_company(value) {
                axios
                .get("/Get_employees_by_company?id=" + value)
                .then(({ data }) => (this.employees = data));
            },

            //----------------------------- Reset Form ---------------------------\\
            reset_Form() {
                this.attendance = {
                    company_id: "",
                    employee_id: "",
                    date:"",
                    clock_in:"",
                    clock_out:"",
                };
                this.errors = {};
            },

             //---------------------- Get all companies  ------------------------------\\
             Get_all_companies() {
                axios
                    .get("/attendances/create")
                    .then(response => {
                        this.companies   = response.data.companies;
                    })
                    .catch(error => {
                       
                    });
            },

              //------------------------ Create Attendance ---------------------------\\
              Create_Attendance() {
                var self = this;
                self.SubmitProcessing = true;
                axios.post("/attendances", {
                    company_id: self.attendance.company_id,
                    employee_id: self.attendance.employee_id,
                    date: self.attendance.date,
                    clock_in: self.attendance.clock_in,
                    clock_out: self.attendance.clock_out,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/attendances'; 
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

           //----------------------- Update Attendance ---------------------------\\
            Update_Attendance() {
                var self = this;
                self.SubmitProcessing = true;
                axios.put("/attendances/" + self.attendance.id, {
                    company_id: self.attendance.company_id,
                    employee_id: self.attendance.employee_id,
                    date: self.attendance.date,
                    clock_in: self.attendance.clock_in,
                    clock_out: self.attendance.clock_out,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/attendances'; 
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


            //--------------------------------- Remove Attendance ---------------------------\\
            Remove_Attendance(id) {

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
                            .delete("/attendances/" + id)
                            .then(() => {
                                window.location.href = '/attendances'; 
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
                        .post("/attendances/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                            .then(() => {
                                window.location.href = '/attendances'; 
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
    //   $('#attendance_list_table').DataTable().destroy();
        var dataTable = $('#attendance_list_table').DataTable({
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
        $('#customFilterDep').on('change', function () {
            let value = $(this).val().toLowerCase();
            dataTable.search(value, true, false).draw();
         });
         $('#customFilterDesignation').on('change', function () {
            let value = $(this).val().toLowerCase();
            dataTable.search(value, true, false).draw();
         });
    });
</script>
@endsection