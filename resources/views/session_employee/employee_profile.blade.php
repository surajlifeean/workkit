@extends('layouts.master')
@section('page-css')
@endsection

@section('main-content')
<div class="breadcrumb">
    <h1>{{ __('translate.Employee') }}</h1>
    <ul>
        <li><a href="/employee_profile">{{ __('translate.Profile') }}</a></li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="card user-profile o-hidden mb-4" id="section_employee_Profile_list">
    <div class="header-cover"></div>
    <div class="user-info">
        <img class="profile-picture avatar-lg mb-2" src="{{asset('assets/images/avatar/'.Auth::user()->avatar)}}" alt="">
        <p class="m-0 text-24">@{{ user.username }}</p>
    </div>
    <div class="card-body">
        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <a class="nav-item nav-link active show" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-home" aria-selected="true">{{ __('translate.Profile') }}</a>
              <a class="nav-item nav-link" id="nav-basic-tab" data-toggle="tab" href="#nav-basic" role="tab" aria-controls="nav-basic" aria-selected="false">{{ __('translate.Basic_Information') }}</a>
              <a class="nav-item nav-link" id="nav-document-tab" data-toggle="tab" href="#nav-document" role="tab" aria-controls="nav-document" aria-selected="false">{{ __('translate.Document') }}</a>
              <a class="nav-item nav-link" id="nav-bank-tab" data-toggle="tab" href="#nav-bank" role="tab" aria-controls="nav-bank" aria-selected="false">{{ __('translate.Bank_Account') }}</a>
          </div>
        </nav>
       
        <div class="tab-content ul-tab__content p-3" id="nav-tabContent">
            {{-- Profile --}}
            <div class="tab-pane fade active show" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <form @submit.prevent="Update_Profile()" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="FirstName" class="ul-form__label">{{ __('translate.FirstName') }} <span class="field_required">*</span></label>
                            <input type="text" v-model="user.firstname" class="form-control" name="FirstName" id="FirstName" placeholder="{{ __('translate.Enter_FirstName') }}">
                            <span class="error" v-if="errors && errors.firstname">
                                @{{ errors.firstname[0] }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label for="lastname" class="ul-form__label">{{ __('translate.LastName') }} <span class="field_required">*</span></label>
                            <input type="text" v-model="user.lastname" class="form-control" id="lastname" id="lastname" placeholder="{{ __('translate.Enter_LastName') }}">
                            <span class="error" v-if="errors && errors.lastname">
                                @{{ errors.lastname[0] }}
                            </span>
                        </div>


                        <div class="col-md-6">
                            <label for="Phone" class="ul-form__label">{{ __('translate.Phone') }}</label>
                            <input type="text" v-model="user.phone" class="form-control" id="Phone" placeholder="{{ __('translate.Enter_Phone') }}">

                        </div>

                        <div class="col-md-6">
                            <label for="country" class="ul-form__label">{{ __('translate.Country') }}</label>
                            <input type="text" v-model="user.country" class="form-control" id="country" placeholder="{{ __('translate.Enter_Country') }}">
                            <span class="error" v-if="errors && errors.country">
                                @{{ errors.country[0] }}
                            </span>
                        </div>


                        <div class="col-md-6">
                            <label for="email" class="ul-form__label">{{ __('translate.Email') }} <span class="field_required">*</span></label>
                            <input type="text" v-model="user.email" class="form-control" id="email" placeholder="{{ __('translate.Enter_email_address') }}">
                            <span class="error" v-if="errors && errors.email">
                                @{{ errors.email[0] }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label for="password" class="ul-form__label">{{ __('translate.Password') }} <span class="field_required">*</span></label>
                            <input type="password" v-model="user.password" class="form-control" id="password" placeholder="{{ __('translate.min_6_characters') }}">
                            <span class="error" v-if="errors && errors.password">
                                @{{ errors.password[0] }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <label for="Avatar" class="ul-form__label">{{ __('translate.Avatar') }}</label>
                            <input name="Avatar" @change="changeAvatar" type="file" class="form-control" id="Avatar">
                            <span class="error" v-if="errors && errors.avatar">
                                @{{ errors.avatar[0] }}
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
            {{-- Basic Information --}}
            <div class="tab-pane fade" id="nav-basic" role="tabpanel" aria-labelledby="nav-basic-tab">

                <div class="row">
                    <!--begin::form-->
                    <form @submit.prevent="Update_Employee_Basic_Info()">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="FirstName" class="ul-form__label">{{ __('translate.FirstName') }} <span class="field_required">*</span></label>
                                <input type="text" class="form-control" id="FirstName" placeholder="{{ __('translate.Enter_FirstName') }}" v-model="employee.firstname">
                                <span class="error" v-if="errors && errors.firstname">
                                    @{{ errors.firstname[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="LastName" class="ul-form__label">{{ __('translate.LastName') }}
                                    <span class="field_required">*</span></label>
                                <input type="text" class="form-control" id="LastName" placeholder="{{ __('translate.Enter_LastName') }}" v-model="employee.lastname">
                                <span class="error" v-if="errors && errors.lastname">
                                    @{{ errors.lastname[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="ul-form__label">{{ __('translate.Gender') }} <span class="field_required">*</span></label>
                                <v-select @input="Selected_Gender" placeholder="{{ __('translate.Choose_Gender') }}" v-model="employee.gender" :reduce="(option) => option.value" :options="
                                                                    [
                                                                        {label: 'Male', value: 'male'},
                                                                        {label: 'Female', value: 'female'},
                                                                    ]">
                                </v-select>

                                <span class="error" v-if="errors && errors.gender">
                                    @{{ errors.gender[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="ul-form__label">{{ __('translate.Family_status') }} </label>
                                <v-select @input="Selected_Family_status" placeholder="{{ __('translate.Choose_status') }}" v-model="employee.marital_status" :reduce="(option) => option.value" :options="
                                                    [
                                                        {label: 'Married', value: 'married'},
                                                        {label: 'Single', value: 'single'},
                                                        {label: 'Divorced', value: 'divorced'},
                                                    ]">
                                </v-select>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="ul-form__label" for="picker3">{{ __('translate.Birth_date') }}</label>

                                <vuejs-datepicker id="birth_date" name="birth_date" placeholder="{{ __('translate.Enter_Birth_date') }}" v-model="employee.birth_date" input-class="form-control" format="yyyy-MM-dd" @closed="employee.birth_date=formatDate(employee.birth_date)">
                                </vuejs-datepicker>

                            </div>



                            <div class="form-group col-md-4">
                                <label for="inputEmail4" class="ul-form__label">{{ __('translate.Email_Address') }} <span class="field_required">*</span></label>
                                <input type="email" class="form-control" id="inputtext4" placeholder="{{ __('translate.Enter_email_address') }}" v-model="employee.email">
                                <span class="error" v-if="errors && errors.email">
                                    @{{ errors.email[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="country" class="ul-form__label">{{ __('translate.Country') }}
                                    <span class="field_required">*</span></label>
                                <input type="text" class="form-control" id="country" placeholder="{{ __('translate.Enter_Country') }}" v-model="employee.country">
                                <span class="error" v-if="errors && errors.country">
                                    @{{ errors.country[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="city" class="ul-form__label">{{ __('translate.City') }} </label>
                                <input type="text" class="form-control" id="city" placeholder="{{ __('translate.Enter_City') }}" v-model="employee.city">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="province" class="ul-form__label">{{ __('translate.Province') }}
                                </label>
                                <input type="text" class="form-control" id="province" placeholder="{{ __('translate.Enter_Province') }}" v-model="employee.province">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="address" class="ul-form__label">{{ __('translate.Address') }}</label>
                                <input type="text" class="form-control" id="address" placeholder="{{ __('translate.Enter_Address') }}" v-model="employee.address">
                                <span class="error" v-if="errors && errors.address">
                                    @{{ errors.address[0] }}
                                </span>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="zipcode" class="ul-form__label">{{ __('translate.Zip_code') }}</label>
                                <input type="text" class="form-control" id="zipcode" placeholder="{{ __('translate.Enter_zip_code') }}" v-model="employee.zipcode">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="phone" class="ul-form__label">{{ __('translate.Phone_Number') }}<span class="field_required">*</span></label>
                                <input type="text" class="form-control" id="phone" placeholder="{{ __('translate.Enter_Phone_Number') }}" v-model="employee.phone">
                                <span class="error" v-if="errors && errors.phone">
                                    @{{ errors.phone[0] }}
                                </span>
                            </div>

                        </div>

                        <div class="row mt-3">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary" :disabled="SubmitProcessingBasic">
                                    {{ __('translate.Submit') }}
                                </button>
                                <div v-once class="typo__p" v-if="SubmitProcessingBasic">
                                    <div class="spinner spinner-primary mt-3"></div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- end::form -->
                </div>
            </div>

            {{-- Document --}}
            <div class="tab-pane fade" id="nav-document" role="tabpanel" aria-labelledby="nav-document-tab">

                <div class="row" id="section_document_list">
                    <div class="col-md-12">
                        <div class="text-left">
                            <div class="text-left bg-transparent">
                                <a class="btn btn-primary btn-md m-2" @click="New_Document"><i class="i-Add text-white mr-2"></i>
                                    {{ __('translate.Add_Document') }}</a>
                            </div>
                            <div class="table-responsive">
                                <table id="ul-contact-list" class="display table data_datatable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('translate.Title') }}</th>
                                            <th>{{ __('translate.Attachment') }}</th>
                                            <th>{{ __('translate.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documents as $document)
                                        <tr>
                                            <td>{{$document->title}}</td>
                                            <td>
                                                <a href="{{ asset('assets/employee/documents/'.$document->attachment) }}" target="_blank">
                                                    {{$document->attachment}}
                                                </a>
                                            </td>
                                            <td>
                                                <a @click="Edit_Document( {{ $document}})" class="ul-link-action text-success" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="i-Edit"></i>
                                                </a>
                                                <a @click="Remove_Document( {{ $document->id}})" class="ul-link-action text-danger mr-1" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="i-Close-Window"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <!-- Modal Add & Edit document -->
                        <div class="modal fade" id="document_Modal" tabindex="-1" role="dialog" aria-labelledby="document_Modal" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 v-if="edit_mode_document" class="modal-title">
                                            {{ __('translate.Edit') }}
                                        </h5>
                                        <h5 v-else class="modal-title">{{ __('translate.Create') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <form @submit.prevent="edit_mode_document?Update_document():Create_document()">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <label for="title" class="ul-form__label">{{ __('translate.Title') }}
                                                        <span class="field_required">*</span></label>
                                                    <input type="text" v-model="document.title" class="form-control" name="title" id="title" placeholder="{{ __('translate.Enter_title') }}">
                                                    <span class="error" v-if="errors_document && errors_document.title">
                                                        @{{ errors_document.title[0] }}
                                                    </span>
                                                </div>


                                                <div class="col-md-12">
                                                    <label for="attachment" class="ul-form__label">{{ __('translate.Attachment') }}
                                                        <span class="field_required">*</span></label>
                                                    <input name="attachment" @change="change_Document" type="file" class="form-control" id="attachment">
                                                    <span class="error" v-if="errors && errors.attachment">
                                                        @{{ errors.attachment[0] }}
                                                    </span>
                                                </div>


                                                <div class="col-md-12">
                                                    <label for="Description" class="ul-form__label">{{ __('translate.Description') }}</label>
                                                    <textarea type="text" v-model="document.description" class="form-control" name="Description" id="Description" placeholder="{{ __('translate.Enter_Description') }}"></textarea>
                                                </div>

                                            </div>




                                            <div class="row mt-3">

                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-primary" :disabled="Submit_Processing_document">
                                                        {{ __('translate.Submit') }}
                                                    </button>
                                                    <div v-once class="typo__p" v-if="Submit_Processing_document">
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
            </div>

            {{-- Bank Account --}}
            <div class="tab-pane fade" id="nav-bank" role="tabpanel" aria-labelledby="nav-bank-tab">

                <div class="row">
                    <div class="col-md-12">
                        <div class="text-left">
                            <div class="text-left bg-transparent">
                                <a class="btn btn-primary btn-md m-2" @click="New_Account"><i class="i-Add text-white mr-2"></i>{{ __('translate.Add_Account') }}</a>
                            </div>
                            <div class="table-responsive">
                                <table id="ul-contact-list" class="display table data_datatable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('translate.Bank_Name') }}</th>
                                            <th>{{ __('translate.Bank_Branch') }}</th>
                                            <th>{{ __('translate.Bank_No') }}</th>
                                            <th>{{ __('translate.Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($accounts_bank as $account_bank)
                                        <tr>
                                            <td>{{$account_bank->bank_name}}</td>
                                            <td>{{$account_bank->bank_branch}}</td>
                                            <td>{{$account_bank->account_no}}</td>
                                            <td>
                                                <a @click="Edit_Account( {{ $account_bank}})" class="ul-link-action text-success" data-toggle="tooltip" data-placement="top" title="Edit">
                                                    <i class="i-Edit"></i>
                                                </a>
                                                <a @click="Remove_Account( {{ $account_bank->id}})" class="ul-link-action text-danger mr-1" data-toggle="tooltip" data-placement="top" title="Delete">
                                                    <i class="i-Close-Window"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <!-- Modal Add & Edit Account -->
                        <div class="modal fade" id="Account_Modal" tabindex="-1" role="dialog" aria-labelledby="Account_Modal" aria-hidden="true">
                            <div class="modal-dialog modal-md" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 v-if="edit_mode_account" class="modal-title">
                                            {{ __('translate.Edit') }}
                                        </h5>
                                        <h5 v-else class="modal-title">{{ __('translate.Create') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <form @submit.prevent="edit_mode_account?Update_Account():Create_Account()">
                                            <div class="row">

                                                <div class="col-md-12">
                                                    <label for="bank_name" class="ul-form__label">{{ __('translate.Bank_Name') }}
                                                        <span class="field_required">*</span></label>
                                                    <input type="text" v-model="account_bank.bank_name" class="form-control" name="bank_name" id="bank_name" placeholder="{{ __('translate.Enter_Bank_Name') }}">
                                                    <span class="error" v-if="errors_bank && errors_bank.bank_name">
                                                        @{{ errors_bank.bank_name[0] }}
                                                    </span>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="bank_branch" class="ul-form__label">{{ __('translate.Bank_Branch') }}
                                                        <span class="field_required">*</span></label>
                                                    <input type="text" v-model="account_bank.bank_branch" class="form-control" name="bank_branch" id="bank_branch" placeholder="{{ __('translate.Enter_Bank_Branch') }}">
                                                    <span class="error" v-if="errors_bank && errors_bank.bank_branch">
                                                        @{{ errors_bank.bank_branch[0] }}
                                                    </span>
                                                </div>

                                                <div class="col-md-12">
                                                    <label for="account_no" class="ul-form__label">{{ __('translate.Bank_Number') }}
                                                        <span class="field_required">*</span></label>
                                                    <input type="text" v-model="account_bank.account_no" class="form-control" name="account_no" id="account_no" placeholder="{{ __('translate.Enter_Bank_Number') }}">
                                                    <span class="error" v-if="errors_bank && errors_bank.account_no">
                                                        @{{ errors_bank.account_no[0] }}
                                                    </span>
                                                </div>


                                                <div class="col-md-12">
                                                    <label for="note" class="ul-form__label">{{ __('translate.Please_provide_any_details') }}</label>
                                                    <textarea type="text" v-model="account_bank.note" class="form-control" name="note" id="note" placeholder="{{ __('translate.Please_provide_any_details') }}"></textarea>
                                                </div>

                                            </div>


                                            <div class="row mt-3">

                                                <div class="col-md-6">
                                                    <button type="submit" class="btn btn-primary" :disabled="Submit_Processing_Bank">
                                                        {{ __('translate.Submit') }}
                                                    </button>
                                                    <div v-once class="typo__p" v-if="Submit_Processing_Bank">
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

            </div>
        </div>

    </div>
</div>



@endsection

@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/vendor/vuejs-datepicker/vuejs-datepicker.min.js')}}"></script>
<script src="{{asset('assets/js/vendor/echarts.min.js')}}"></script>
<script src="{{asset('assets/js/echart.options.min.js')}}"></script>
<script>
    Vue.component('v-select', VueSelect.VueSelect)
    var app = new Vue({
        el: '#section_employee_Profile_list',
        components: {
        vuejsDatepicker
        },
        data: {
            data: new FormData(),
            SubmitProcessing: false,
            errors: [],

            SubmitProcessingBasic: false,
            editmode: false,
            errors: [],

            Submit_Processing_Bank: false,
            edit_mode_account: false,
            errors_bank: [],

            Submit_Processing_document: false,
            edit_mode_document: false,
            errors_document: [],


            user: @json($user),
            accounts_bank: @json($accounts_bank),
            documents: @json($documents),
            employee: @json($employee),

            account_bank: {
                bank_name: "",
                bank_branch: "",
                account_no: "",
                note: "",
            },

            document: {
                title: "",
                description: "",
                attachment: "",
            },
        },

        methods: {


            changeAvatar(e) {
                let file = e.target.files[0];
                this.user.avatar = file;
            },


            //----------------------- Update Profile ---------------------------\\
            Update_Profile() {
                var self = this;
                self.SubmitProcessing = true;
                self.data.append("firstname", self.user.firstname);
                self.data.append("lastname", self.user.lastname);
                self.data.append("country", self.user.country);
                self.data.append("email", self.user.email);
                self.data.append("password", self.user.password);
                self.data.append("phone", self.user.phone);
                self.data.append("avatar", self.user.avatar);
                self.data.append("_method", "put");

                axios
                    .post("/employee_profile/" + self.user.id, self.data)
                    .then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/employee_profile';
                        toastr.success('{{ __('translate.Updated_in_successfully ') }}');
                        self.errors = {};
                    })
                    .catch(error => {
                        self.SubmitProcessing = false;
                        if (error.response.status == 422) {
                            self.errors = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge ') }}');
                    });
            },



            //------------------------ basic info ----------------------------------\\
            formatDate(d) {
                var m1 = d.getMonth() + 1;
                var m2 = m1 < 10 ? '0' + m1 : m1;
                var d1 = d.getDate();
                var d2 = d1 < 10 ? '0' + d1 : d1;
                return [d.getFullYear(), m2, d2].join('-');
            },

            Selected_Gender(value) {
                if (value === null) {
                    this.employee.gender = "";
                }
            },

            Selected_Family_status(value) {
                if (value === null) {
                    this.employee.marital_status = "";
                }
            },



            //------------------------ update basic info -------------------------------\\
            Update_Employee_Basic_Info() {
                var self = this;
                self.SubmitProcessing = true;
                axios.put("/session_employee/basic/info/" + self.employee.id, {
                        firstname: self.employee.firstname,
                        lastname: self.employee.lastname,
                        country: self.employee.country,
                        email: self.employee.email,
                        gender: self.employee.gender,
                        phone: self.employee.phone,
                        birth_date: self.employee.birth_date,
                        marital_status: self.employee.marital_status,
                        city: self.employee.city,
                        province: self.employee.province,
                        address: self.employee.address,
                        zipcode: self.employee.zipcode,
                    }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/employee_profile';
                        toastr.success('{{ __('translate.Updated_in_successfully ') }}');
                        self.errors = {};
                    })
                    .catch(error => {
                        self.SubmitProcessing = false;
                        if (error.response.status == 422) {
                            self.errors = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge ') }}');
                    });
            },

            //------------------------ Nav Document ---------------------------------------------------------------------------------------------\\


            New_Document() {
                this.reset_Form_Document();
                this.edit_mode_document = false;
                $('#document_Modal').modal('show');
            },

            //------------------------------ Show Modal (Edit document) -------------------------------\\
            Edit_Document(document) {
                this.edit_mode_document = true;
                this.reset_Form_Document();
                this.document = document;
                $('#document_Modal').modal('show');
            },

            //----------------------------- reset_Form_Document---------------------------\\
            reset_Form_Document() {
                this.document = {
                    id: "",
                    title: "",
                    attachment: "",
                    description: "",
                };
                this.errors_document = {};
            },



            change_Document(e) {
                let file = e.target.files[0];
                this.document.attachment = file;
            },

            //----------------------- Update document---------------------------\\
            Create_document() {
                var self = this;
                self.Submit_Processing_document = true;

                if (self.document.attachment) {
                    self.data.append("attachment", self.document.attachment);
                }
                self.data.append("employee_id", self.employee.id);
                self.data.append("title", self.document.title);
                self.data.append("description", self.document.description);

                axios
                    .post("/employee_document", self.data)
                    .then(response => {
                        self.Submit_Processing_document = false;
                        window.location.href = '/employees/' + self.employee.id;
                        toastr.success('{{ __('translate.Created_in_successfully') }}');
                        self.errors_document = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_document = false;
                        if (error.response.status == 422) {
                            self.errors_document = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },

            //----------------------- Update document---------------------------\\
            Update_document(id) {
                var self = this;
                self.Submit_Processing_document = true;

                if (self.document.attachment) {
                    self.data.append("attachment", self.document.attachment);
                }
                self.data.append("employee_id", self.employee.id);
                self.data.append("title", self.document.title);
                self.data.append("description", self.document.description);
                self.data.append("_method", "put");

                axios
                    .post("/employee_document/" + self.document.id, self.data)
                    .then(response => {
                        self.Submit_Processing_document = false;
                        window.location.href = '/employees/' + self.employee.id;
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors_document = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_document = false;
                        if (error.response.status == 422) {
                            self.errors_document = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },


            //--------------------------------- Remove_Document ---------------------------\\
            Remove_Document(id) {

                swal({
                    title: '{{ __('translate.Are_you_sure ') }}',
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
                }).then(function() {
                    axios
                        .delete("/employee_document/" + id)
                        .then(() => {
                            location.reload();
                            toastr.success('{{ __('translate.Deleted_in_successfully') }}');

                        })
                        .catch(() => {
                            toastr.error('{{ __('translate.There_was_something_wronge') }}');
                        });
                });
            },


            //--------------------------------------------- Bank Account -----------------------------------------------------------\\


            //------------------------------ Show Modal (Create Bank Account) -------------------------------\\
            New_Account() {
                this.reset_Form_bank_account();
                this.edit_mode_account = false;
                $('#Account_Modal').modal('show');
            },

            //------------------------------ Show Modal (Edit Bank Account) -------------------------------\\
            Edit_Account(account_bank) {
                this.edit_mode_account = true;
                this.reset_Form_bank_account();
                this.account_bank = account_bank;
                $('#Account_Modal').modal('show');
            },


            //----------------------------- Reset_Form_Bank Account---------------------------\\
            reset_Form_bank_account() {
                this.account_bank = {
                    id: "",
                    bank_name: "",
                    bank_branch: "",
                    account_no: "",
                    note: "",
                };
                this.errors_bank = {};
            },

            //------------------------ Create Bank Account ---------------------------\\
            Create_Account() {
                var self = this;
                self.Submit_Processing_Bank = true;
                axios.post("/session_employee/storeAccount", {
                        employee_id: self.employee.id,
                        bank_name: self.account_bank.bank_name,
                        bank_branch: self.account_bank.bank_branch,
                        account_no: self.account_bank.account_no,
                        note: self.account_bank.note,

                    }).then(response => {
                        self.Submit_Processing_Bank = false;
                        window.location.href = '/employee_profile';
                        toastr.success('{{ __('translate.Created_in_successfully ') }}');
                        self.errors_bank = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_Bank = false;
                        if (error.response.status == 422) {
                            self.errors_bank = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge ') }}');
                    });
            },

            //----------------------- Update Bank Account ---------------------------\\
            Update_Account() {
                var self = this;
                self.Submit_Processing_Bank = true;
                axios.put("/session_employee/updateAccount/" + self.account_bank.id, {
                        employee_id: self.employee.id,
                        bank_name: self.account_bank.bank_name,
                        bank_branch: self.account_bank.bank_branch,
                        account_no: self.account_bank.account_no,
                        note: self.account_bank.note,

                    }).then(response => {
                        self.Submit_Processing_Bank = false;
                        // window.location.href = '/employee/my_requests/' + self.employee.id;
                        location.reload();
                        toastr.success('{{ __('translate.Updated_in_successfully') }}');
                        self.errors_bank = {};
                    })
                    .catch(error => {
                        self.Submit_Processing_Bank = false;
                        if (error.response.status == 422) {
                            self.errors_bank = error.response.data.errors;
                        }
                        toastr.error('{{ __('translate.There_was_something_wronge') }}');
                    });
            },

            //--------------------------------- Remove Bank Account ---------------------------\\
            Remove_Account(id) {

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
                }).then(function() {
                    axios.delete("/session_employee/destroyAccount/" + id)
                        .then(() => {
                            toastr.success('Deleted in successfully');
                            location.reload();

                        })
                        .catch(() => {
                            toastr.danger('There was something wronge');
                        });
                });
            },


        },
        //-----------------------------Autoload function-------------------
        created() {}

    })
</script>

@endsection