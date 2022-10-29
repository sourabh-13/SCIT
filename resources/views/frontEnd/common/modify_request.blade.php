<!-- send Modification request Modal Start -->
<div class="modal fade" id="ModifyRequestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"> Modification Request </h4>
            </div>
            <form method="post" action="{{ url('/send-modify-request') }}" ng-app="myApp" ng-controller="validateController" name="ModiForm" >
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="col-md-2 m-t-5 text-right">Action</label>
                            <div class="col-md-9">
                                <select name="action" class="form-control" required="" ng-model="action">
                                    <option value="">Select Action</option>
                                    <option value="add">Add</option>
                                    <option value="edit">Edit</option>
                                    <option value="Delete">Delete</option>
                                    <option value="view">View</option>
                                </select>
                                <p>Select action to perform</p>
                           </div>
                        </div>

                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="col-md-2 m-t-5 text-right">Content</label>
                            <div class="col-md-9">
                                <textarea name="content" value="" rows="5" class="form-control" required="" ng-model="content" ></textarea>
                                <p>Enter the Content to modify</p>
                                <p ng-show="ModiForm.content.$error.required"></p>
                            </div>
                        </div>
                        
                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                            <label class="col-md-2 m-t-5 text-right">Reason</label>
                            <div class="col-md-9">
                                <textarea name="reason" value="" rows="5" class="form-control"  required="" ng-model="reason"></textarea>
                                <p>Enter the Reason</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer incen-foot">
                    {{ csrf_field() }}
                    <button type="button" class="btn btn-default" data-dismiss="modal"> Close </button>
                    <button type="submit" class="btn btn-warning modify-req-btn" ng-disabled="ModiForm.$invalid"> Send Request </button>
                </div>  
            </form>
            <div class="row"></div>
        </div>
    </div>
</div>
<!-- send Modification request Modal end -->

<script>
var app = angular.module('myApp', []);
app.controller('validateController', function($scope) {
    //$scope.content = 'John Doe';
});
</script>