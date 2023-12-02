<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Employee_List') }}</h1>
    <ul>
        <li><a href="/employees">{{ __('translate.Employees') }}</a></li>
        <li>{{ __('translate.Employee_List') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>
<div class="col-md-12 mb-3">
    <div class="col-lg-4 col-md-4 col-sm-6 col-12">
        <select name="view_style" class="form-control" id="view_style" onchange="changeView(event)">
           <option value="table">{{ __('translate.Table view') }}</option>
           <option value="icons_view">{{ __('translate.Icons view') }}</option>
        </select>
    </div>
    
</div>
<div class="row" id="section_Employee_list">
    
    <div class="col-md-12 mb-3" id="icon_view">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 col-12 ml-auto">
                <input type="text" id="searchInput" placeholder="{{ __('translate.Search...') }}" class="form-control">
            </div>
        </div>
        <div class="row flex-wrap mt-3">
                @foreach($employees as $employee)
                    <div data-employee="{{ json_encode($employee) }}" data-name="{{ $employee->firstname . ' ' . $employee->lastname }}" class="employee-modal-trigger cursor-pointer mx-3 d-flex align-items-center justify-content-center flex-column" style="width: fit-content">
                            <img style="height: 80px; width: 100px;" src="{{ asset('assets/images/avatar/'. $employee->user->avatar) }}" alt="user avatar" onerror="this.src='{{ asset('assets/images/avatar/no_avatar.jpeg') }}'">
                     
                        <p class="mt-1">{{$employee->firstname}} {{$employee->lastname}}</p>
                    </div>
                @endforeach              
        </div>
    </div>
    <div class="col-md-12" id="table_view">
        <div class="card text-left">
            <div class="card-header text-right bg-transparent">
                @can('employee_add')
                <a class="btn btn-{{$setting->theme_color}} btn-md m-1" href="{{route('employees.create')}}"><i
                        class="i-Add-User text-white mr-2"></i> {{ __('translate.Create') }}</a>
                @endcan
                @can('employee_delete')
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="delete_selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
                @endcan
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table id="employee_list_table" class="display table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>#</th>
                                <th>{{ __('translate.Avatar')}}</th>
                                <th>{{ __('translate.Firstname') }}</th>
                                
                                <th>{{ __('translate.Lastname') }}</th>
                                <th>{{ __('translate.Phone') }}</th>
                                <th>{{ __('translate.Company') }}</th>
                                <th>{{ __('translate.Department') }}</th>
                                <th>{{ __('translate.Designation') }}</th>
                                <th>{{ __('translate.Office_Shift') }}</th>
                                @if( auth()->check() && auth()->user()->can('employee_details') || auth()->user()->can('employee_edit') || auth()->user()->can('employee_delete'))
                                <th>{{ __('translate.Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $employee)
                            <tr>
                                <td></td>
                                <td @click="selected_row( {{ $employee->id}})"></td>
                              
                                <td>
                                    <a href="{{ asset('assets/images/avatar/'. $employee->user->avatar) }}" target="_blank" onclick="openImageWindow(event)">
                                        <img style="height: 40px; width: 40px; border-radius: 100%;" src="{{ asset('assets/images/avatar/'. $employee->user->avatar) }}" alt="user avatar" onerror="this.src='{{ asset('assets/images/avatar/no_avatar.jpeg') }}'">
                                    </a>
                                </td>
                               
                                <td>{{$employee->firstname}}</td>
                                <td>{{$employee->lastname}}</td>
                                <td>{{$employee->phone}}</td>
                                <td>{{$employee->company->name}}</td>
                                <td>{{$employee->department->department}}</td>
                                <td>{{$employee->designation->designation}}</td>
                                <td>{{$employee->office_shift->name}}</td>
                                <td>
                                    @can('employee_details')
                                    <a href="/employees/{{$employee->id}}" class="ul-link-action text-info"
                                        data-toggle="tooltip" data-placement="top" title="Show">
                                        <i class="i-Eye"></i>
                                    </a>
                                    @endcan

                                    @can('employee_edit')
                                    <a href="/employees/{{$employee->id}}/edit" class="ul-link-action text-success"
                                        data-toggle="tooltip" data-placement="top" title="{{ __('translate.Edit') }}">
                                        <i class="i-Edit"></i>
                                    </a>
                                    @endcan

                                    @can('employee_delete')
                                    <a @click="Remove_Employee( {{ $employee->id}})"
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
    </div>
</div>
{{-- model --}}
<div class="modal" id="employeeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Employee Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="employeeDetails" class="d-flex flex-column align-items-center">

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
        el: '#section_Employee_list',
        data: {
            SubmitProcessing:false,
            selectedIds:[],
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

            //--------------------------------- Remove Employee ---------------------------\\
            Remove_Employee(id) {
             console.log(id);
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
                            .delete("/employees/" + id)
                            .then(() => {
                                window.location.href = '/employees'; 
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
                        .post("/employees/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                            .then(() => {
                                window.location.href = '/employees'; 
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
    $('#icon_view').hide();
    $(function () {
      "use strict";

        $('#employee_list_table').DataTable( {
            "processing": true, // for show progress bar
            select: {
                style: 'multi',
                selector: '.select-checkbox',
                items: 'row',
            },
            responsive: {
                details: {
                    type: 'column',
                    target: 0
                }
            },
            columnDefs: [{
                targets: 0,
                    className: 'control'
                },
                {
                    targets: 1,
                    className: 'select-checkbox'
                },
                {
                    targets: [0, 1],
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
    var isEmployee = true;
    @if( auth()->check() && auth()->user()->can('employee_details') || auth()->user()->can('employee_edit') || auth()->user()->can('employee_delete'))
       isEmployee = false;
    @endif

    function openImageWindow(event) {
        event.preventDefault();
        window.open(event.currentTarget.href, '_blank', 'width=600,height=500');
    }

    $('.employee-modal-trigger').on('click', function(event) {
        event.preventDefault();

        var employeeData = JSON.parse($(this).attr('data-employee'));
        displayEmployeeDetails(employeeData);
    });

    function displayEmployeeDetails(employee) {
        var modalBody = $('#employeeDetails');
        var modalTitle = $('#employeeModal .modal-title');

        modalTitle.text(employee.firstname + ' ' + employee.lastname);

        // Customize the display of employee details in the modal body
        var baseUrl = "{{ url('/') }}";
        var avatarUrl = employee.user.avatar ? baseUrl + '/assets/images/avatar/' + employee.user.avatar : '#';
        
        var modalContent = `
            <a href="${avatarUrl}" target="_blank" onclick="openImageWindow(event)">
                <img style="height: 200px; width: 200px; border-radius: 100%;" src="${avatarUrl}" alt="user avatar">
            </a>
            <p class="my-1 mt-2">{{ __('translate.Phone') }}: ${employee.phone}</p>
            <p class="my-1">{{ __('translate.Company') }}: ${employee.company.name}</p>
            <p class="my-1">{{ __('translate.Department') }}: ${employee.department.department}</p>
            <p class="my-1">{{ __('translate.Designation') }}: ${employee.designation.designation}</p>
            <p class="my-1">{{ __('translate.Office_Shift') }}: ${employee.office_shift.name}</p>
            <div>
                ${ isEmployee ? '' : `
                    <a href="/employees/${employee.id}" class="ul-link-action text-info"
                       data-toggle="tooltip" data-placement="top" title="Show">
                      <i class="i-Eye"></i>
                    </a>
                    <a href="/employees/${employee.id}/edit" class="ul-link-action text-success"
                        data-toggle="tooltip" data-placement="top" title="{{ __('translate.Edit') }}">
                        <i class="i-Edit"></i>
                    </a>
                    <a onclick="removeEmployee( ${employee.id} )"
                        class="ul-link-action text-danger mr-1 cursor-pointer" data-toggle="tooltip"
                        data-placement="top" title="{{ __('translate.Delete') }}">
                        <i class="i-Close-Window"></i>
                    </a>
                `}
            </div>

        `;



        modalBody.html(modalContent);

        $('#employeeModal').modal('show');
    }

    function removeEmployee(id) {
        console.log(id);
        swal({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#0CC27E',
            cancelButtonColor: '#FF586B',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success mr-5',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false
        }).then(async function (result) {
            try {
                    await axios.delete("/employees/" + id);
                    window.location.href = '/employees';
                    toastr.success('Deleted successfully');
                } catch (error) {
                    toastr.error('There was something wrong.');
                }
        });
    }

    function changeView(e){
       if (e.target.value == 'table') {
         $('#icon_view').hide();
         $('#table_view').show();
       } else {
         $('#icon_view').show();
         $('#table_view').hide();
       }
    }

    function filterEmployees() {
     
        var input, filter, employees, employee, name, i;
        input = document.getElementById('searchInput');
        filter = input.value.toUpperCase();
        employees = document.querySelectorAll('.employee-modal-trigger'); 
    
        employees.forEach(function(employee) {
            var name = employee.getAttribute('data-name').toUpperCase();
            // console.log(name);
            if (name.includes(filter)) {
                console.log('yes');
                employee.classList.add('d-flex');
                employee.classList.remove('d-none');
            } else {
                console.log('no');
                employee.classList.remove('d-flex');
                employee.classList.add('d-none');
            }
        });
    
    }

    document.getElementById('searchInput').addEventListener('input', filterEmployees);

</script>
@endsection
