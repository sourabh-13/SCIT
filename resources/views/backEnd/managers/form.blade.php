@extends('backEnd.layouts.master')
@section('title',' Manager Form')
@section('content')
<?php
    if(isset($manager)){
        $form       = 'Edit';
        $form_id    = 'edit_manager_form';
        $action     = url('admin/managers/edit').'/'.$manager['id'];
    }else{
        $form       = 'Add';
        $form_id    = 'add_manager_form';
        $action     = url('admin/managers/add');
    }
?>
 <section id="main-content" class="">
    <section class="wrapper">
        <div class="row">
			<div class="col-lg-12">
                <section class="panel">
                    <header class="panel-heading">
                       {{ $form }} Manager
                    </header>
                    <div class="panel-body">
                        <div class="position-center">
                            <form class="form-horizontal" role="form" method="post" action="{{ $action }}" id="{{ $form_id }}" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Name</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="name" class="form-control" value="{{ isset($manager->name)? $manager->name : ''}}" maxlength="255" placeholder="Name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Email</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ isset($manager->email)? $manager->email : ''}}" maxlength="255">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Contact Number</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="contact_no" id="contact_no" class="form-control" placeholder="Contact Number" value="{{ isset($manager->contact_no)? $manager->contact_no : ''}}" maxlength="255">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Address</label>
                                    <div class="col-lg-10">
                                        <input type="text" name="address" class="form-control" placeholder="Address" value="{{ isset($manager->address)? $manager->address : ''}}" maxlength="255">
                                    </div>
                                </div>
                                <?php
                                    $image = managerImagePath.'/default_user.jpg';
                                    if(isset($manager->image))
                                    {
                                        $image = managerImagePath.'/'.$manager->image;
                                    }
                                ?>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label"></label>
                                    <div class="col-lg-10">
                                        <img src="{{ $image }}" id="old_image" alt="No image" style="max-width: auto; max-height: 150px; min-width: 150px; min-height: 100px; line-height: 100px;">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Image</label>
                                    <div class="col-md-8">
                                        <input type="file" id="img_upload" name="image">
                                    </div>
                                </div>
    							
    							<div class="form-actions">
    								<div class="row">
    									<div class="col-lg-offset-2 col-lg-10">
                                            <input type="hidden" name="manager_id" id="manager_id" value="{{ isset($manager->id)? $manager->id : '' }}">
    										<input type="hidden" name="_token" value="{{ csrf_token() }}">
    										<button type="submit" class="btn green btn-primary">Save</button>
    										<a href="{{ url('admin/managers') }}">
    											<button type="button" class="btn default btn-default" name="cancel">Cancel</button>
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

<script>
    $(document).ready(function()
    {
      function readURL(input) 
        {
          	if (input.files && input.files[0])
            {
                var reader = new FileReader();
                reader.onload = function (e) 
                    {
                        $('#old_image').attr('src', e.target.result);
                    };

                reader.readAsDataURL(input.files[0]);
            }
        }
    	  	$("#img_upload").change(function()
    	  	{	
    	      	var img_name = $(this).val();
    	      
    	      	if(img_name != "" && img_name!=null)
    	      	{
    	        	var img_arr=img_name.split('.');
    	        	var ext = img_arr.pop();
    	        	ext     = ext.toLowerCase();
    	        	if(ext =="jpg" || ext =="jpeg" || ext =="gif" || ext =="png")
    				{
    		            input=document.getElementById('img_upload');
    		            if(input.files[0].size > 2097152 || input.files[0].size <  10240)
    		            {
    		              $(this).val('');
    		              $("#img_upload").removeAttr("src");
    		              alert("image size should be at least 10KB and upto 2MB");
    		              return false;
    		            }
    		            else
    		            {
    		              readURL(this);
    		            }   
    		        }
    	           else
    		        {
    		           	$(this).val('');
    		           	alert('Please select an image .jpg, .png, .gif file format type.');
    		        }
    		    }
    	    return true;
    	}); 
    });
</script>
@endsection