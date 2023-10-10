<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Expense_Category') }}</h1>
    <ul>
        <li><a href="/accounting/expense">{{ __('translate.Expense') }}</a></li>
        <li>{{ __('translate.Expense_Category') }}</li>
    </ul>
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row" id="section_Expense_Category">
    <div class="col-md-12">
        <div class="card text-left">
            @can('expense_category')
            <div class="card-header text-right bg-transparent">
                <a class="btn btn-primary btn-md m-1" @click="New_Category"><i class="i-Add text-white mr-2"></i>
                    {{ __('translate.Create') }}</a>
                <a v-if="selectedIds.length > 0" class="btn btn-danger btn-md m-1" @click="delete_selected()"><i
                        class="i-Close-Window text-white mr-2"></i> {{ __('translate.Delete') }}</a>
            </div>
            @endcan
            <div class="card-body">
                <div class="table-responsive">
                    <table id="expense_category_table" class="display table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('translate.Category') }}</th>
                                <th>{{ __('translate.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td @click="selected_row( {{ $category->id}})"></td>
                                <td>{{$category->title}}</td>
                                <td>
                                    @can('expense_category')
                                    <a @click="Edit_Category( {{ $category}})" class="ul-link-action text-success"
                                        data-toggle="tooltip" data-placement="top" title="Edit">
                                        <i class="i-Edit"></i>
                                    </a>
                                    @endcan
                                    @can('expense_category')
                                    <a @click="Remove_Category( {{ $category->id}})"
                                        class="ul-link-action text-danger mr-1" data-toggle="tooltip"
                                        data-placement="top" title="Delete">
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

        <!-- Modal Add & Edit Category -->
        <div class="modal fade" id="Expense_Category_Modal" tabindex="-1" role="dialog"
            aria-labelledby="Expense_Category_Modal" aria-hidden="true">
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

                        <form @submit.prevent="editmode?Update_Category():Create_Category()">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="title" class="ul-form__label">{{ __('translate.Title') }} <span
                                            class="field_required">*</span></label>
                                    <input type="text" v-model="category.title" class="form-control" name="title"
                                        id="title" placeholder="{{ __('translate.Enter_title') }}">
                                    <span class="error" v-if="errors && errors.title">
                                        @{{ errors.title[0] }}
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


<script>
    var app = new Vue({
        el: '#section_Expense_Category',
        data: {
            editmode: false,
            SubmitProcessing:false,
            errors:[],
            selectedIds:[],
            categories: [], 
            category: {
                title: "",
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

            //------------------------------ Show Modal (Create Category) -------------------------------\\
            New_Category() {
                this.reset_Form();
                this.editmode = false;
                $('#Expense_Category_Modal').modal('show');
            },

            //------------------------------ Show Modal (Update category) -------------------------------\\
            Edit_Category(category) {
                this.editmode = true;
                this.reset_Form();
                this.category = category;
                $('#Expense_Category_Modal').modal('show');
            },

            //----------------------------- Reset Form ---------------------------\\
            reset_Form() {
                this.category = {
                    id: "",
                    title: "",
                };
                this.errors = {};
            },

            //------------------------ Create Category ---------------------------\\
            Create_Category() {
                var self = this;
                self.SubmitProcessing = true;
                axios.post("/accounting/expense_category", {
                    title: self.category.title,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/accounting/expense_category'; 
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

           //----------------------- Update Category ---------------------------\\
            Update_Category() {
                var self = this;
                self.SubmitProcessing = true;
                axios.put("/accounting/expense_category/" + self.category.id, {
                    title: self.category.title,
                }).then(response => {
                        self.SubmitProcessing = false;
                        window.location.href = '/accounting/expense_category'; 
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

             //--------------------------------- Remove Category ---------------------------\\
            Remove_Category(id) {

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
                            .delete("/accounting/expense_category/" + id)
                            .then(() => {
                                window.location.href = '/accounting/expense_category'; 
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
                        .post("/accounting/expense_category/delete/by_selection", {
                            selectedIds: self.selectedIds
                        })
                            .then(() => {
                                window.location.href = '/accounting/expense_category'; 
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
    
        $('#expense_category_table').DataTable( {
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
        
    });
</script>
@endsection