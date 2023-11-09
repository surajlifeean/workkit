<?php $setting = DB::table('settings')->where('deleted_at', '=', null)->first(); ?>
@extends('layouts.master')
@section('main-content')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<style>
    .app-footer{
        display:none;
    }
</style>
@endsection

<div class="breadcrumb">
    <h1>{{ __('translate.Notifications') }}</h1>
    <!-- <ul>
        <li><a href="/employees">{{ __('translate.Employees') }}</a></li>
        <li>{{ __('translate.Employee_List') }}</li>
    </ul> -->
</div>

<div class="separator-breadcrumb border-top"></div>

<div class="row overflow-auto mb-5" id="section_Employee_list">
    <div class="col-12" id="chat-container" style="height: 60vh; overflow-y: scroll;">
       @foreach($notifications as $notification)
        @if($notification->user_id == null)
            <div class="my-2">
              <div class="card p-2" style="max-width: 80%;width: fit-content;min-width: 300px;">
               <h3>{{ $notification->title }}</h3>
               <p style="max-width: 100%; overflow-wrap: break-word" class="m-0">{{ $notification->message }}</p>
               <p style="font-size: 10px;" class="ml-auto mt-0 mb-0">{{ $notification->created_at->format('d/m/Y H:i:s') }}</p>
              </div>
            </div>
        @else
            <div class="my-2 ">
              <div class="card p-2 ml-auto" style="max-width: 90%;width: fit-content;min-width: 300px; background: #D9FDD3;">
               <h3>{{ $notification->title }}</h3>
               <p style="max-width: 100%; overflow-wrap: break-word" class="m-0">{{ $notification->message }}</p>
               <p style="font-size: 10px;" class="ml-auto mt-0 mb-0">{{  $notification->created_at->format('d/m/Y H:i:s') }}</p>
              </div>
            </div>
        @endif
       @endforeach
       <div style="margin-bottom: 9rem;"></div>
    </div>
    <div class=" position-fixed" style="bottom: 0; width: 81%;">
        <form @submit.prevent="Send_msg" class="card p-3" enctype="multipart/form-data" style="box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);">
        
            <div class="row">
            
                <div class="col-md-12">
                    <label for="send_message"
                        class="ul-form__label">{{ __('translate.Send_Message') }}
                    </label>
                    <input type="text" class="form-control mb-3 col-md-6 col-12" placeholder="{{ __('translate.Title') }}" name="title" id="title" value="From Employee" v-model="send_message.title">
                    <span class="error" v-if="errors && errors.title">
                                        @{{ errors.title[0] }}
                    </span>
                    <div class="d-flex">
                    <textarea type="text" v-model="send_message.message"
                        class="form-control" name="message" id="message"
                        placeholder="{{ __('translate.Send_Message') }}"></textarea>
                        <span class="error" v-if="errors && errors.message">
                                        @{{ errors.message[0] }}
                        </span>
                        <button type="submit" class="btn btn-{{$setting->theme_color}} text-white ml-2"
                            :disabled="Submit_Processing_message">
                            <i class="i-Pen"></i>
                        </button>
                        
                    </div>
                   
                </div> 
            
            </div>
        
        </form> 
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
            Submit_Processing_message:false,
            selectedIds:[],
            send_message: {
              message: '',
              title: '',
            },
            errors: [],
        },
        mounted() {
           
             const currentPageUrl = window.location.href;
             console.log(currentPageUrl);
             const isGetMessagesPresent = currentPageUrl.includes('get_messages');
             this.Notification_checked();
        },
        methods: {
            Notification_checked(){
                axios.post(`/notifications_seen/${0}`)
                 .then(response => {
                    //  toastr.success('{{ __('translate.Created_in_successfully') }}');
                    //  console.log('Resource created successfully:', response.data);
                 })
                 .catch(error => {
                     toastr.error('{{ __('translate.There_was_something_wronge') }}');
                     console.error('Error creating resource:', error);
                 });
            },

            Send_msg(){
                if(this.send_message.message === ''){
                  return toastr.error('{{ __('translate.There_was_something_wronge') }}');
                }
                this.Submit_Processing_message = true;
                axios.post(`/send-notification-superadmin`, {
                  title: this.send_message.title,
                  message: this.send_message.message,
                })
                 .then(response => {
                     toastr.success('{{ __('translate.Created_in_successfully') }}');
                     location.reload();
                     console.log('Resource created successfully:', response.data);
                 })
                 .catch(error => {
                     this.Submit_Processing_message = false;
                     toastr.error('{{ __('translate.There_was_something_wronge') }}');
                     this.errors = error.response.data.errors;
                     console.error('Error creating resource:', error);
                 });
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

            //--------------------------------- Remove Employee ---------------------------\\
            Remove_Employee(id) {

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
</script>
<script>
window.addEventListener("load", function() {
   var element = document.getElementById("chat-container");
   element.scrollTop = element.scrollHeight;
});
</script>
@endsection