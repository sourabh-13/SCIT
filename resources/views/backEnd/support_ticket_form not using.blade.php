@extends('backEnd.layouts.master')

@section('title',' Support Ticket Form')

@section('content')

<?php
	if(isset($support_tickets))
	{
		$action = url('admin/users/support-tickets/edit/'.$support_tickets->id);
		$task = "Edit";
		$form_id = 'edit_support_ticket_form';
	}
	else
	{
		$action = url('admin/users/support-tickets/add/'.$service_user_id);
		$task = "Add";
		$form_id = 'add_support_ticket_form';
	}
?>
 <section id="main-content" class="">
    <section class="wrapper">
        <div class="row">
			<div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                        {{ $task }} Support Ticket Form
                    </header>
                    <div class="panel-body">
                        <div class="position-center">
                            <form class="form-horizontal" role="form" method="post" action="{{ $action }}" id="{{ $form_id }}" enctype="multipart/form-data">

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Title</label>
                                <div class="col-lg-10">
                                    <input type="text" name="title" class="form-control" placeholder="title" value="{{ (isset($support_tickets->title)) ? $support_tickets->title : '' }}">
                                </div>
                            </div>
							
                            <div class="form-group">
                                <label class="col-lg-2 control-label">Message</label>
                                <div class="col-lg-10">
                                    <textarea name="message" class="form-control" placeholder="message" rows="4">{{ (isset($support_tickets_message->message)) ? $support_tickets_message->message : '' }}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-lg-2 control-label">Status</label>
                                <div class="col-lg-10">
                                    <select name="status" class="form-control">
                                            <option value="">Select Status</option>
                                            <option value="1" <?php if(isset($support_tickets->status)) { if($support_tickets->status == '1'){ echo 'selected'; } }   ?>>Open
                                            
                                            </option>
                                            <option value="0" <?php if(isset($support_tickets->status)) { if($support_tickets->status == '0'){ echo 'selected'; } }   ?>>Close

                                            </option>           
                                    </select>
                                </div>
                            </div>                            
                            
                            <div class="form-actions">
								<div class="row">
									<div class="col-lg-offset-2 col-lg-10">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="id" value="{{ (isset($support_ticket->id)) ? $support_ticket->id : '' }}">
										<button type="submit" class="btn green" name="">Submit</button>
										<a href="{{ url('admin/users/support-tickets/'.$service_user_id) }}">
											<button type="button" class="btn default" name="cancel">Cancel</button>
										</a>
									</div>
								</div>
							</div>
                        </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
	</section>
</section>						
<!-- <script>
$(document).ready(function() {

    $('.default-date-picker').datepicker({
        //format: 'yyyy-mm-dd'
         format: 'dd-mm-yyyy',
        // maxDate:'+13-02-2017'
        });
    });
</script>
 -->
@endsection