<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')


@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Create_Permissions') }}</h1>
    <ul>
        <li><a href="/settings/permissions">{{ __('translate.Permissions') }}</a></li>
        <li>{{ __('translate.Create_Permissions') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<!-- begin::main-row -->
<div class="row" id="section_Permission_Create">
    <div class="col-lg-12 mb-3">
        <div class="card">

            <!--begin::form-->
            <form @submit.prevent="Create_Permission()">
                <div class="card-body">

                    <div class="row">

                        <div class="col-md-6">
                            <label for="name" class="ul-form__label">{{ __('translate.Role_Name') }} <span
                                    class="field_required">*</span></label>
                            <input type="text" v-model="role.name" class="form-control" name="name" id="name"
                                placeholder="{{ __('translate.Enter_Role_Name') }}">
                            <span class="error" v-if="errors && errors.name">
                                @{{ errors.name[0] }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label for="description" class="ul-form__label">{{ __('translate.Description') }}</label>
                            <input type="text" v-model="role.description" class="form-control" name="description"
                                id="description" placeholder="{{ __('translate.Enter_description') }}">
                        </div>
                    </div>



                    <div class="row">

                          <!--Dashboard -->
                          <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Dashboard">
                                    <div class="card-header">{{ __('translate.Dashboard') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            
                                            {{-- employee_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="Employee_dashboard_view" @change="handleCheckboxChange('Employee_dashboard_view')">
                                                    <span>{{ __('translate.Employee') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- employee_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="Admin_dashboard_view" @change="handleCheckboxChange('Admin_dashboard_view')">
                                                    <span>{{ __('translate.Admin') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            

                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        
                        <!--Employee -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Employee">
                                    <div class="card-header">{{ __('translate.Employee') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- employee_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="employee_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- employee_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="employee_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- employee_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="employee_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- employee_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="employee_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- employee_details --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="employee_details">
                                                    <span>{{ __('translate.Details') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--User Managment -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_User">
                                    <div class="card-header">{{ __('translate.User_Managment') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- user_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="user_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- user_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="user_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- user_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="user_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- user_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="user_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Company -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Company">
                                    <div class="card-header">{{ __('translate.Company') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- company_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="company_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- company_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="company_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- company_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="company_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- company_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="company_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- actions --}}
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Actions">
                                    <div class="card-header">{{ __('translate.Actions') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- employee_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="Actions_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                         
                        
                                          
                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Employee claims --}}
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Employee">
                                    <div class="card-header">{{ __('translate.Employee') }} {{ __('translate.claims') }}</div>
                                    <div class="card-body">
                                        <div class="row">

                                           
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="Employee_claims_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                           

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- home office --}}
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Home_office">
                                    <div class="card-header">{{ __('translate.Home_Office') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- employee_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="Home_Office_View">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!--Department -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Department">
                                    <div class="card-header">{{ __('translate.Department') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- department_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="department_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- department_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="department_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- department_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="department_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- department_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="department_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Designation -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Designation">
                                    <div class="card-header">{{ __('translate.Designation') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- designation_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="designation_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- designation_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="designation_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- designation_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="designation_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- designation_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="designation_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Policy -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Policy">
                                    <div class="card-header">{{ __('translate.Policy') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- policy_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="policy_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- policy_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="policy_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- policy_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="policy_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- policy_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="policy_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Announcement -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Announcement">
                                    <div class="card-header">{{ __('translate.Announcement') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- announcement_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="announcement_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- announcement_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="announcement_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- announcement_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="announcement_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- announcement_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="announcement_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Office Shift -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Office_Shift">
                                    <div class="card-header">{{ __('translate.Office_Shift') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- office_shift_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="office_shift_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- office_shift_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="office_shift_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- office_shift_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="office_shift_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- office_shift_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="office_shift_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Event -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Events">
                                    <div class="card-header">{{ __('translate.Event') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- event_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="event_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- event_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="event_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- event_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="event_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- event_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="event_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Holiday -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Holiday">
                                    <div class="card-header">{{ __('translate.Holiday') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- holiday_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="holiday_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- holiday_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="holiday_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- holiday_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="holiday_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- holiday_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="holiday_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Award -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Award">
                                    <div class="card-header">{{ __('translate.Award') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- award_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="award_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- award_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="award_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- award_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="award_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- award_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="award_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- Award Type --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="award_type">
                                                    <span>{{ __('translate.Award_Type') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Complaint -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Complaint">
                                    <div class="card-header">{{ __('translate.Complaint') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- complaint_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="complaint_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- complaint_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="complaint_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- complaint_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="complaint_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- complaint_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="complaint_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Travel -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Travel">
                                    <div class="card-header">{{ __('translate.Travel') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- travel_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="travel_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- travel_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="travel_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- travel_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="travel_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- travel_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="travel_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- arrangement_type --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="arrangement_type">
                                                    <span>{{ __('translate.Arrangement_Type') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Attendance -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Attendance">
                                    <div class="card-header">{{ __('translate.Attendance') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- attendance_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="attendance_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- attendance_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="attendance_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- attendance_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="attendance_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- attendance_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="attendance_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Accounting -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Accounting">
                                    <div class="card-header">{{ __('translate.Accounting') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- account_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="account_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- account_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="account_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- account_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="account_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- account_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="account_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- payment_method --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="payment_method">
                                                    <span>{{ __('translate.Payment_Method') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Deposit -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Deposit">
                                    <div class="card-header">{{ __('translate.Deposit') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- deposit_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="deposit_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- deposit_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="deposit_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- deposit_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="deposit_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- deposit_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="deposit_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- deposit_category--}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="deposit_category">
                                                    <span>{{ __('translate.Deposit_Category') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--Expense -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Expense">
                                    <div class="card-header">{{ __('translate.Expense') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- expense_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="expense_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- expense_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="expense_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- expense_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="expense_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- expense_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="expense_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- expense_category--}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="expense_category">
                                                    <span>{{ __('translate.Expense_Category') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Client -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Client">
                                    <div class="card-header">{{ __('translate.Client') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- client_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="client_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- client_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="client_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- client_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="client_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- client_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="client_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Project -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Project">
                                    <div class="card-header">{{ __('translate.Project') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- project_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="project_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- project_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="project_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- project_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="project_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- project_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="project_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- project_details --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="project_details">
                                                    <span>{{ __('translate.Project_details') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Task -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Task">
                                    <div class="card-header">{{ __('translate.Task') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- task_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="task_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- task_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="task_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- task_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="task_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- task_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="task_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- task_details --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="task_details">
                                                    <span>{{ __('translate.Task_details') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- kanban_task --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="kanban_task">
                                                    <span>{{ __('translate.Kanban_View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leave -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Leave">
                                    <div class="card-header">{{ __('translate.Leave') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- leave_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="leave_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- leave_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="leave_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- leave_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="leave_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- leave_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="leave_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- leave_type --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="leave_type">
                                                    <span>{{ __('translate.leave_type') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Training -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Training">
                                    <div class="card-header">{{ __('translate.Training') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- training_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="training_view">
                                                    <span>{{ __('translate.View') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- training_add --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="training_add">
                                                    <span>{{ __('translate.Create') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- training_edit --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="training_edit">
                                                    <span>{{ __('translate.Edit') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- training_delete --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="training_delete">
                                                    <span>{{ __('translate.Delete') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- Trainer --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="trainer">
                                                    <span>{{ __('translate.Trainer') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- Training Skills --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="training_skills">
                                                    <span>{{ __('translate.Training_Skills') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Settings">
                                    <div class="card-header">{{ __('translate.Settings') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- settings_view --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="settings">
                                                    <span>{{ __('translate.System_Settings') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- module_settings --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="module_settings">
                                                    <span>{{ __('translate.Module_settings') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- Currency --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="currency">
                                                    <span>{{ __('translate.Currency') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- Backup --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions" value="backup">
                                                    <span>{{ __('translate.Backup') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- group_permission --}}
                                            <div class="col-md-6">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="group_permission">
                                                    <span>{{ __('translate.Group_Permissions') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reports -->
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="accordion" id="accordion_Reports">
                                    <div class="card-header">{{ __('translate.Reports') }}</div>
                                    <div class="card-body">
                                        <div class="row">
                                            {{-- Attendance_Report --}}
                                            <div class="col-md-12">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="attendance_report">
                                                    <span>{{ __('translate.Attendance_Report') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- Employee_Report --}}
                                            <div class="col-md-12">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="employee_report">
                                                    <span>{{ __('translate.Employee_Report') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            {{-- Project_Report --}}
                                            <div class="col-md-12">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="project_report">
                                                    <span>{{ __('translate.Project_Report') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- Task_Report --}}
                                            <div class="col-md-12">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="task_report">
                                                    <span>{{ __('translate.Task_Report') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- Expense_Report --}}
                                            <div class="col-md-12">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="expense_report">
                                                    <span>{{ __('translate.Expense_Report') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                            {{-- deposit_report --}}
                                            <div class="col-md-12">
                                                <label class="checkbox checkbox-outline-primary">
                                                    <input type="checkbox" checked v-model="permissions"
                                                        value="deposit_report">
                                                    <span>{{ __('translate.Deposit_Report') }}</span>
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>




                    <div class="row mt-3">
                        <div class="col-lg-6">
                            <button type="submit" class="btn btn-{{$setting->theme_color}}" :disabled="SubmitProcessing">
                                {{ __('translate.Submit') }}
                            </button>
                            <div v-once class="typo__p" v-if="SubmitProcessing">
                                <div class="spinner spinner-primary mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- end::form -->
        </div>
    </div>

</div>
@endsection


@section('page-js')


<script>
    var app = new Vue({
    el: '#section_Permission_Create',
    data: {
        SubmitProcessing:false,
        errors:[],
        permissions: [],
      
        role: {
            name: "",
            description: ""
        }
        
    },
   
   
    methods: {
        handleCheckboxChange(per) {
            // console.log(per);
            // console.log(this.permissions);
    
            if (per === 'Admin_dashboard_view') {
                this.permissions = this.permissions.filter(item => item !== 'Employee_dashboard_view');
            } else if (per === 'Employee_dashboard_view') {
                this.permissions = this.permissions.filter(item => item !== 'Admin_dashboard_view');
            }

            console.log(this.permissions);
        },
        //------------------------ Create Permissions ---------------------------\\
        Create_Permission() {
            var self = this;
            self.SubmitProcessing = true;
            axios.post("/settings/permissions", {
                name: self.role.name,
                description: self.role.description,
                permissions: self.permissions,
               
            }).then(response => {
                    self.SubmitProcessing = false;
                    window.location.href = '/settings/permissions'; 
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
    created () {
      
    },

})

</script>

@endsection