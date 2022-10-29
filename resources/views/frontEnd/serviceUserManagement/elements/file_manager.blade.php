
<link rel="stylesheet" href="{{ url('public/frontEnd/css/file-uploader/css/jquery.fileupload.css') }}">
<link rel="stylesheet" href="{{ url('public/frontEnd/css/file-uploader/css/jquery.fileupload-ui.css') }}">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript>
    <link rel="stylesheet" href="{{ url('public/frontEnd/css/file-uploader/css/jquery.fileupload-noscript.css') }}">
</noscript>
<noscript>
    <link rel="stylesheet" href="{{ url('public/frontEnd/css/file-uploader/css/jquery.fileupload-ui-noscript.css') }}">
</noscript>

<!-- fancybox -->
<link rel="stylesheet" href="{{ url('public/frontEnd/css/fancy-box/jquery.fancybox.css') }}">
<script src="{{ url('public/frontEnd/css/fancy-box/jquery.fancybox.js') }}"></script>
<!--<script src="{{ url('public/frontEnd/css/fancy-box/jquery.fancybox.min.js') }}"></script> -->

<style type="text/css">
    #careTeamMembersModal .modal-content {
        border: medium none;
        box-shadow: none;
        float: left;
        width: 100%;
    }
    #careTeamMembersModal .select2-container {
        width: 100% !important;
    }
    #careTeamMembersModal .select2-container--default.select2-container--focus .select2-selection--multiple {
        max-height: 32px !important;
        overflow: auto;
        width: 95% !important;
    }
    #careTeamMembersModal .error {
        color: red;
    }
    #careTeamMembersModal .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: 1px solid transparent;
    }
</style>

<!--File Manager -->
<div class="modal fade" id="filemngrModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"> File Manager </h4>
            </div>
            <div class="modal-body">
                <div class="row" >
       
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 serch-btns text-right">
                        <!-- <button class="btn label-default active risk-add-btn show-upload" type="button"> Add New </button>
                        <button class="btn label-default risk-logged-btn logged-plans" type="button"> Logged Files </button>
                        <button class="btn label-default risk-search-btn" type="button"> Search </button> -->
                        <button class="btn label-default add-new-btn show-upload active" type="button"> Add New </button>
                        <button class="btn label-default logged-btn logged-plans" type="button"> Logged files </button>
                        <button class="btn label-default search-btn" type="button"> Search </button>
                    </div>

                    <!-- Add new Details -->
                    <div class="add-new-box risk-tabs">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue fnt-20"> Add New - Details </h3>
                        </div>

                        <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')

                        <!-- 1st field -->
                        <!-- <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Title: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12 r-p-0">
                                    <div class="input-group popovr">
                                        <input type="text" name="file_title" required class="form-control add_title" placeholder="title" />
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- 2nd field -->
                        <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Category: </label>
                                <div class="col-md-11 col-sm-11 col-xs-12 r-p-0">
                                    <div class="input-group popovr m-l-10">
                                        <div class="select-style">
                                            <select name="file_category" class="add_category">
                                                <option value="">Select Category </option>
                                                <?php foreach ($file_category as $key=>$value){ ?>
                                                <option value='{{ $value->id }}'> {{ ucfirst($value->name) }} </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     
                        <div class="col-md-12 col-sm-12 col-xs-12 hide-upload custom-margin">
                            <form id="fileupload1" action="" method="POST" enctype="multipart/form-data">
                      
                                <!-- The table listing the files available for upload/download -->
                                <div class="cus-height">
                                    <table role="presentation" class="table table-striped">
                                        <tbody class="files">
                                        </tbody>
                                    </table>
                                </div>
                                <div class="CUSTOM-TEXT">
                                    <p>Click add files and navigation to the file location in the open file dialogue.</p>
                                </div>
                                <div class="row fileupload-buttonbar modal-footer m-t-0">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <!-- The fileinput-button span is used to style the file input field as button -->
                                        <span class="btn btn-success add_files_btn">
                                            <i class="fa fa-plus"></i>
                                            <span>Add files...</span>
                                            <!-- <input type="file" name="files[]" multiple id="inputImageUpload" > -->
                                        </span>
                                        <input type="file" name="su_files[]" multiple id="inputImageUpload" style="display:none" >
                                        
                                        <button type="submit" class="btn btn-primary start submit_files_btn"> <!--   start -->
                                            <i class="fa fa-upload"></i>
                                            <span>Start upload</span>
                                        </button>
                                        <button type="reset" class="btn btn-warning cancel_full_upload">
                                            <!-- <i class="glyphicon glyphicon-ban-circle"></i> -->
                                            <i class="fa fa-ban"></i>
                                            <span>Cancel upload</span>

                                        </button>
                                        <!-- The global file processing state -->
                                        <span class="fileupload-process"></span>
                                    </div>
                                    
                                </div>
                            </form>
                        </div> 
                    </div>

                    <!-- Logged Files -->
                    <div class="logged-box risk-tabs file-logged-box">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h3 class="m-t-0 m-b-20 clr-blue fnt-20"> Logged files </h3>
                        </div>
                        <!--logged files list shown here!-->
                        <!-- alert messages -->
                        @include('frontEnd.common.popup_alert_messages')
                        <div class="col-md-12 col-sm-12 col-xs-12 logged-plans-list">     
                        <!-- Logged files shown using ajax -->
                        </div>
                    </div>
                   
                   <!-- Search Box -->
                    <div class="search-box risk-tabs">
                        <form method="post" action="" id="files_search_form">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <h3 class="m-t-0 m-b-20 clr-blue fnt-20"> Search </h3>
                            </div>

                            <!-- 1st field -->
                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                    <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Title: </label>
                                    <div class="col-md-11 col-sm-11 col-xs-12 r-p-0">
                                        <div class="input-group popovr  m-l-10">
                                            <input type="text" name="search_file_title" class="form-control" placeholder="Enter Title" maxlength="255"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 cog-panel">
                                <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0 add-rcrd">
                                    <label class="col-md-1 col-sm-1 col-xs-12 p-t-7"> Category: </label>
                                    <div class="col-md-11 col-sm-11 col-xs-12 r-p-0">
                                        <div class="input-group popovr m-l-10">
                                            <div class="select-style">
                                                <select name="search_file_category">
                                                    <option value="">All Category </option>
                                                    <?php foreach ($file_category as $key=>$value){ ?>
                                                    <option value='{{ $value->id }}'> {{ ucfirst($value->name) }} </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="searched-logged-files cus-height" style="padding-left:34px">
                            <!-- Using Ajax -->
                            </div>

                            <div class="modal-footer m-t-0  recent-task-sec"> <!-- p-b-0 -->
                                <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                                <button class="btn btn-warning file_details_submit search_files_btn" type="button"> Confirm </button>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
         </div>
    </div>
</div>

<!-- CareTeam Members for fileManager Modal Start -->
<div class="modal fade" id="careTeamMembersModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <a class="close mdl-back-btn" href="" data-toggle="modal" data-dismiss="modal" data-target="#filemngrModal">
                    <i class="fa fa-arrow-left" title=""></i>
                </a>
                <h4 class="modal-title">Send Email</h4>
            </div>
            <div class="modal-body" >
                <form method="post" action="{{ url('/service/file-manager/email') }}" id="sel_email_member">
                    <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                        <label class="col-md-2 col-sm-2 col-xs-12 p-t-7"> Care team members: </label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <div class="select-style">
                                <select name="sel_care_team_id[]" class="team_member_select2" multiple="" />
                                    @foreach($care_team as $member)
                                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="form-group col-md-12 col-sm-12 col-xs-12 p-0">
                        <label class="col-md-2 col-sm-2 col-xs-12 p-t-7"> Other email: </label>
                        <div class="col-md-10 col-sm-10 col-xs-12">
                                <input type="text" class="form-control" placeholder="" name="other_send_email" />
                        </div>
                    </div> -->
                    
                    <div class="form-group modal-footer m-t-0 modal-bttm">
                        <button class="btn btn-default" type="button" data-dismiss="modal" aria-hidden="true"> Cancel </button>
                        <input type="hidden" name="file_id" value="" id="log_file_id">
                        <input type="hidden" name="service_user_id" value="{{ $service_user_id }}" id="log_file_id">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button class="btn btn-warning" type="submit"> Submit </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- CareTeam Members for fileManager Modal End -->

<script >
    //(function($) {
    $(document).ready(function(){
        /*$('ul.pagination li.active')
            .prev().addClass('show-mobile')
            .prev().addClass('show-mobile');
        $('ul.pagination li.active')
            .next().addClass('show-mobile')
            .next().addClass('show-mobile');
        $('ul.pagination')
            .find('li:first-child, li:last-child, li.active')
            .addClass('show-mobile');*/
    //})(jQuery);
    });
</script>

<script>
    // start butoon i.e. upload all files
    $(document).ready(function(){
      
        var names = [];
        var file_index = 0;

        $(document).on('click','.submit_files_btn', function(){
            
            var file_category_id = $('select[name=\'file_category\']').val();

            if(file_category_id == ''){
                $('select[name=\'file_category\']').parent().addClass('red_border');
                return false;
            } else{
                $('select[name=\'file_category\']').parent().removeClass('red_border');                
            }

            var formData = new FormData();
            //console.log(names);
            var total_files = names.length;
            if(total_files == 0){ // if no image is available to upload in list then do not run ajax
                return false;
            }
            // console.log(names);
            // console.log(total_files);
            
            var no_image = 0;
            for(var i = 0; i < total_files; i++ ){
                
                //sending files from the names array
                formData.append('files['+i+']', names[i]);

                if(names[i] != '') { //checking if keys are not empty
                    no_image++;
                }
            }

            // if no image is available to upload in list then do not run ajax
            if(no_image == 0) {
                return false;
            }

            formData.append('file_category_id', file_category_id);
            formData.append('service_user_id', "{{ $service_user_id }}");

            $('#filemngrModal .progress-bar-success').css('width','100%');
           
            $.ajax({
                url : "{{ url('/service/file-manager/add') }}",
                type : 'post',
                data:  formData,
                dataType : "json",
                processData : false,
                contentType : false,
                cache : false,
                success : function(resp) {
                    //console.log(resp);
                    
                    if(isAuthenticated(resp) == false){
                        $('#filemngrModal .progress-bar-success').css('width','0%');
                        return false;
                    }

                    $(resp).each(function(index) {
                        var data = $(this);
                        //console.log(data);
                        var response  = data[0].response;
                        var save_id   = data[0].save_id;
                        var file_index_value = data[0].file_index;
                        var file_name = data[0].file_name;
                        var row = $("tr[file_index='"+file_index_value+"']");

                        if(response == true){    
                            row.attr('save_id',save_id);
                            row.find('p.name').text(file_name);
                            row.find('.file_actions').html('<button class="btn btn-danger delete delete_file_btn" > <i class="fa fa-trash"></i> <span>Delete</span> </button>');
                            row.find('.progress-striped').remove();
                            names[file_index_value] = ''; //remove this image from image names array
                            //$('#inputImageUpload').val('');
                            //names = [];

                        } else{ 
                            row.find('.error').text("{{ COMMON_ERROR }}");
                            row.find('.progress-bar-success').css('width','0%');
                        } 
                    });
                }
            });
        return false;
        });

        // cancel and remove all the files which was in upload list. 
        $(document).on('click','.cancel_full_upload', function(){
            names = [];
            file_index = 0;
            $('.files').html(''); //make remove all selected files from div 
            return false;
        });

        // click on add file button > open select files
        $('.add_files_btn').click(function(){
            $("#inputImageUpload").click();
        });

        // when new files are selected on clicking on add files button
        $("#inputImageUpload").change(function(){
            var input = $(this)[0];
            var files = $(input.files);
            var invalid     = 0;

            files.each(function(index){
                
                var inp         = $(this);
                var filename    = inp.prop('name');
                var filename_arr= filename.split('.');
                var ext         = filename_arr.pop();
                ext             = ext.toLowerCase();
                
                if(ext == 'jpg' || ext == 'jpeg' || ext == 'png' || ext == 'pdf' || ext == 'doc' || ext == 'docx' || ext == 'wps') {
                    var filesize = inp.prop('size'); //bytes
                    var unit = 'B';
                    
                    if(filesize > 1048576){
                        filesize = filesize/1048576;
                        filesize = filesize.toFixed(2);
                        unit = 'MB';                    
                    } else if(filesize > 1024){
                        filesize = filesize/1024;
                        filesize = filesize.toFixed(2);
                        unit = 'KB';
                    } 
                    names.push(input.files[index]);

                    var reader = new FileReader();
                    reader.onload = function (e) 
                    {        
                        if(ext == 'jpg' || ext == 'jpeg' || ext == 'png') {
                            var preview = '<span class="preview"><image width="80" height="60" src="'+e.target.result+'" alt="No preview" /></span>';
                            var td_class = '';

                        } else if(ext == 'pdf') {
                            var preview = '<span class="preview" style="font-size:55px"><i class="fa fa-file-pdf-o"></i></span>';
                            var td_class = 'padding-top:0px';

                        } else if(ext == 'doc' || ext == 'docx'  || ext == 'wps' ) {
                            var preview = '<span class="preview" style="font-size:55px"><i class="fa fa-file-word-o"></i></span>';
                            var td_class = 'padding-top:0px';
                            
                        } else{
                            var preview = '<span class="preview"><image width="80" height="60" src="'+e.target.result+'" alt="No preview2" /></span>';
                            var td_class = 'padding-top:0px';
                        }
    
                      $('.files').append('<tr class="template-upload fade in" file_index="'+file_index+'" save_id="" > <td style="'+td_class+'">'+preview+' </td> <td><p class="name">'+filename+'</p> <strong class="error text-danger"></strong> </td> <td> <p class="size">'+filesize + unit +'</p> <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div> </td> <td  class="file_actions"> <button class="btn btn-primary start_btn"> <i class="fa fa-upload"></i> <span>Start</span> </button> <button class="btn btn-warning cancel_btn"> <i class="fa fa-ban"></i> <span>Cancel</span> </button> </td> </tr>');
                      file_index++;
                   };
                   reader.readAsDataURL(input.files[index]); 
                } else{ 
                    invalid = 1;
                }
            });
        
            if(invalid == 1){ //if any of the selected file was not of following formats then show this message.
                alert('Only .jpg,jpeg,png,pdf,doc,docx,wps formats are allowed.')
            }
        });        
        
        //SINGLE FILE UPLOAD
        
        // upload single file
        $(document).on('click','.start_btn', function(){

            var file_category_id = $('select[name=\'file_category\']').val();
            if(file_category_id == ''){
                $('select[name=\'file_category\']').parent().addClass('red_border');
                return false;
            } else{
                $('select[name=\'file_category\']').parent().removeClass('red_border');                
            }

            var row = $(this).closest('tr');
            var file_name = row.find('p.name').text();
            var file_index_value = row.attr('file_index');
          
            var formData = new FormData();
            formData.append('file', names[file_index_value]);
            formData.append('file_category_id', file_category_id);
            formData.append('service_user_id', "{{ $service_user_id }}");

            row.find('.progress-bar-success').css('width','100%');
           
            $.ajax({
                url : "{{ url('service/file-manager/upload/add') }}",
                type : 'post',
                data:  formData,
                dataType : "json",
                processData : false,
                contentType : false,
                cache : false,
                success : function(resp) {
                    if(isAuthenticated(resp) == false){
                        row.find('.progress-bar-success').css('width','0%');
                        return false;
                    }

                    var response = resp['response'];
                    var save_id  = resp['save_id'];
                    var file_name  = resp['file_name'];

                    if(response == true){ 
                        row.find('.progress-striped').remove();
                        row.attr('save_id',save_id);
                        row.find('p.name').text(file_name);

                        names[file_index_value] = ''; //remove this image from image names array
                                                
                        row.find('.file_actions').html('<button class="btn btn-danger delete delete_file_btn" > <i class="fa fa-trash"></i> <span>Delete</span> </button>');
                     
                    } else{ 
                        //$('.progress-bar-success').css('width','0%');
                        row.find('.progress-bar-success').css('width','0%');
                        alert('Some Error Occured. Please try again after some time.');
                    }
                }
            });
        return false;
        });

        //cancel file which is in the upload list and is not uploaded yet
        $(document).on('click','.cancel_btn', function(){
            
            var row = $(this).closest('tr');
            var file_name = row.find('p.name').text();
            file_index_value = row.attr('file_index');
            //names.pick_and_remove(file_index_value);

            names[file_index_value] = '';
            //console.log(names[file_index_value]);           
            row.fadeOut();           
            return false;
        });

        // delete uploaded files (delete file from db and remove it from upload list)           
        $(document).on('click','.delete_file_btn', function(){
            var row = $(this).closest('tr');
            var save_id = row.attr('save_id');
            var file_index = row.attr('file_index');
            
            $.ajax({
                url : "{{ url('/service/file-manager/delete') }}"+'/'+save_id,
                type : 'get',
                success:function(resp){
                    if(isAuthenticated(resp) == false){
                        return false;
                    }

                    if(resp == 'true'){
                        row.fadeOut();  
                    } else{
                        alert('Some error occured. Please try again after some time.');
                    }
                }
            });
            return false;
        });
    });
</script>



<script>
    //click search btn
    $(document).ready(function(){

        $('input[name=\'search_file_title\']').keydown(function(event) { 
            var keyCode = (event.keyCode ? event.keyCode : event.which);   
            if (keyCode == 13) {
                $('.search_files_btn').click();        
                return false;
            }
        });
        
        $(document).on('click','.search_files_btn', function(){

            var search = $('input[name=\'search_file_title\']').val();
            var search_file_category = $('select[name=\'search_file_category\']').val();
            //alert(search_file_category); return false;
            // alert(service_user_id); return false; 
            search = jQuery.trim(search);

            if(search == '' && search_file_category == ''){
                //alert('Please select at least o')
                return false;
            }

            /*if(search == '' || search == '0'){
                $('input[name=\'search_file_title\']').addClass('red_border');
                return false;
            } else{
                $('input[name=\'search_file_title\']').removeClass('red_border');
            }

            if(search_file_category == ''){
                $('input[name=\'search_file_category\']').parent().addClass('red_border');
                return false;
            } else{
                $('input[name=\'search_file_category\']').parent().removeClass('red_border');
            }*/

            var service_user_id = "{{ $service_user_id }}";
         
            $('.loader').show();
            $('body').addClass('body-overflow');
            
            $.ajax({
                type : 'get',
                url  : "{{ url('/service/file-managers') }}"+'/'+service_user_id+'?search='+search+'&category='+search_file_category,
                success : function(resp){
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    //alert(resp);
                    if(resp == ''){
                        $('.searched-logged-files').html('No Files found.');
                    } else{
                        $('.searched-logged-files').html(resp);
                    }
                    $('input[name=\'search\']').val('');
                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
        return false;
        });
    });
</script>

<script>
    //in logged btn click get data
    $(document).ready(function(){
        $(document).on('click','.logged-plans', function(){
            
            $('.loader').show();
            $('body').addClass('body-overflow');

            $('.hide-upload').hide();
            // $('.logged-plans-confirm-btn').hide();
            var service_user_id = "{{ $service_user_id }}";
            $.ajax({
                type : 'get',
                url  : "{{ url('/service/file-managers') }}"+'/'+service_user_id,
                success : function(resp){  
                    if(isAuthenticated(resp) == false){
                        return false;
                    } 
                    if(resp == '') {
                        $('.logged-plans-list').html('<div class="text-center p-b-20" style="width:100%">No Records found.</div>');    
                    } else {
                        $('.logged-plans-list').html(resp);
                    }

                    $("[data-fancybox]").fancybox({
                        // Options will go here
                    });
                    // $('.logged-plans-list').html(resp);
                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
        });
        return false;
    });
</script>

<script>
   
    $(document).ready(function(){
        
        //delete a row
        $(document).on('click','.delete-logged-file', function(){
            
            if(!confirm('{{ DEL_CONFIRM }}')){
                return false;
            }

            var logged_file_id = $(this).attr('logged_file_id');
            //alert(logged_file_id); return false;
            $(this).addClass('active_record');
    
            $('.loader').show();
            $('body').addClass('body-overflow');

            $.ajax({
                type : 'get',
                url  : "{{ url('/service/file-manager/delete/') }}"+'/'+logged_file_id,
                //data : { 'logged_file_id' : logged_file_id, '_token' : file_token },
                success : function(resp){
                    if(isAuthenticated(resp) == false){
                        return false;
                    }
                    // alert(resp); return false;

                    if(resp == 'true') {
                        $('.active_record').closest('.delete-file').remove();
                        //show success delete message
                        $('span.popup_success_txt').text('File Deleted Successfully');                   
                        $('.popup_success').show();
                        setTimeout(function(){$(".popup_success").fadeOut()}, 5000);
                    } else{

                        //show delete message error
                        $('span.popup_error_txt').text('Error Occured');
                        $('.popup_error').show();
                    }
                    $('.loader').hide();
                    $('body').removeClass('body-overflow');
                }
            });
            return false;
        });

        $('.show-upload').click(function(){
            $('.hide-upload').show();
        });
        
        //email to careTeam members
        $(document).on('click','.send_email', function() {
            
            var logged_file_id = $(this).attr('logged_file_id');
            
            $('#log_file_id').val(logged_file_id);
            $('#filemngrModal').modal('hide');
            $('#careTeamMembersModal').modal('show');

        });
        
    });
</script>

<script>
    //pagination of file manager
    $(document).on('click','.file_mngr_paginate .pagination li',function(){
        var new_url = $(this).children('a').attr('href');
        if(new_url == undefined){
            return false;
        }
        
        $('.loader').show();
        $('body').addClass('body-overflow');

        $.ajax({
            type : 'get',
            url  : new_url,
            success:function(resp){
                $('.logged-plans-list').html(resp);
                //$('#healthmodal').modal('show');
                $('.loader').hide();
                $('body').removeClass('body-overflow');
            }
        });
        return false;
    });
</script>

<script type="">
    
    //  $(document).ready(function(){

    //     $(document).on('.wrinkled', function(){
    //         $(this).closest('body').find('.fancybox-content').addClass('testing');
    //     });
    // });

    // function printDiv() {

    //     var  id_print = $('.fancybox-container').attr('id');
    //     console.log(id_print);
    //     var divToPrint=document.getElementById(id_print);

    //     var newWin=window.open('','PRINT', 'height=400,width=600');

    //     newWin.document.open();

    //     newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');

    //     newWin.print();
    //     //newWin.document.close();
    //     newWin.close();
    //     return true;

    //     //setTimeout(function(){newWin.close();},1000);

    // }
    
    
</script>
<script type="text/javascript">
//var  id_print = $('.fancybox-container').attr('id');
//console.log(id_print);
// function printDiv(divName) {
//      var printContents = document.getElementById(divName).innerHTML;
//      var originalContents = document.body.innerHTML;
//     alert(printContents);
//      document.body.innerHTML = printContents;

//      window.print();

//      document.body.innerHTML = originalContents;
// }
</script>


<script >
    //select options 
    $(document).ready(function(){
        $(".team_member_select2").select2({
              // dropdownParent: $('#addshiftmodal'),
            placeholder: "Select Care Team Member"
        });
     });
</script>

<script type="text/javascript">
   $(function() {
        $("#sel_email_member").validate({
            errorElement: 'span',
            errorPlacement: function (error, element) {
              error.insertAfter(element.parent());
            },
            rules: {
                'sel_care_team_id[]': {
                    required: true
                }
            },
            messages: {
                'sel_care_team_id[]': {
                    required: "This field is required."
                }
            },
            submitHandler: function(form) {
              form.submit();
            }
        })
        return false;   
    });
</script>
