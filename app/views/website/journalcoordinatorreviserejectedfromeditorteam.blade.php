@extends('master')

@section('content')
{{ Form::open(['url' => 'web/journalforwardagaintoauhtor/','role'=>'form','files'=>true]) }}
<div id="returntoauthor" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="returntoauthor" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            <h3 class="text-center" style="color: green;"><b>SEND TO AUTHOR</b></h3>
            <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><b>Remark:</b></label>
                            <input type="text" name="Rejected_Remark" value="" class="form-control required" placeholder="">
                            @foreach ($content as $item)
                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                            @endforeach
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
					<div class="col-md-8 table-responsive">
						<h5 class="font-blue-madison bold">Attach Documents:</h5>
						<table id="specializedtradehumanresource" class="table table-bordered table-striped table-condensed">

							<thead>
								<tr>
									<th></th>
									<th>Upload File</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td></td>
									<td>
										<input type="file" name="attachments" class="input-sm" multiple="multiple" />
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
                <div class="modal-footer">
                <button type="submit" class="btn green"> Send </button>
				<button type="button" class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
              </div>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
{{ Form::open(['url' => 'web/journalforwardtoeditorteam/','role'=>'form']) }}
<div id="forwardtoeditorialteam" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="forwardtoeditorialteam" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            <h3 class="text-center" style="color: green;"><b>FORWARD TO EDITORIAL TEAM</b></h3>
            <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><b>Remark:</b></label>
                            <input type="text" name="remarkByJctoEditorteam" value="" class="form-control required" placeholder="">
                            @foreach ($content as $item)
                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn green"> Send </button>
				<button type="button" class="btn red" data-dismiss="modal" aria-hidden="true">Cancel</button>
              </div>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}
    <div class="row">
        <div class="col-md-12">
            @if (Session::get('messagesuccess'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('messagesuccess') }}</strong>
                        </div>
                    @endif

            <div class="portlet light bordered"> 
                <div class="portlet-body form">  
                    <!-- Striped table -->

                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            
                            <thead class="thead-dark">
                                <h3><b>Journal Coordinator </b><b id="Editor">( Managing Editor )</b></h3>
                                <tr>

                                </tr>
                            </thead>
                            <tbody class="fond">

                                @foreach ($content as $item)
                                    <tr>
                                        <th class="width">Application No:</th>
                                        <td>{{ $item->Application_No }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name of the Author:</th>
                                        @foreach ($authorname as $items)
                                        <td>{{ $items->Name }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Title:</th>
                                        <td>{{ $item->Name_of_Title }}</td>
                                    </tr>
                                    <tr>
                                        <th>File:</th>
                                        <td>
                                            <a href="{{ asset($item->File) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>   
                                        <th>Remark from Editorial Team:</th>
                                        <td>{{ $item->Remark_From_Editorteam_toJc_for_revised }}</td>
                                    </tr>
                                    <tr>
                                        <th>Submitted Date:</th>
                                        <td>{{date('d/m/Y', strtotime($item->CreatedOn))}}</td>
                                    </tr>
                                    <tr>
                                <th>Email:</th>
                                @foreach ($authorname as $items)
                                <td>{{ $items->Email }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <th>Contact Number:</th>
                                @foreach ($authorname as $items)
                                <td>{{ $items->Contact }}</td>
                                @endforeach
                            </tr>
                                            <tr>
                                                <th>
                                                    <h4 style="color:#107C10"><b>Reviewer 1 Details</b></h4>
                                                </th>
                                            </tr>
                                        
                                        <tr>
                                        <th class="width">Peer Review Checklist:</th>
                                        <td>
                                            <a href="{{ asset($item->Reviewer1_Checklist_Edition) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                    <th>Commented Manuscript:</th>
                                        <td>
                                            <a href="{{ asset($item->Reviewer1_Commented_Edition) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                   
                                    
                                            <tr>
                                                <th>
                                                    <h4 style="color:#107C10"><b>Reviewer 2 Details</b></h4>
                                                </th>
                                            </tr>
                                        
                                        <tr>
                                        <th class="width">Peer Review Checklist:</th>
                                        <td>
                                            <a href="{{ asset($item->Reviewer2_Checklist_Edition) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Commented Manuscript:</th>
                                        <td>
                                            <a href="{{ asset($item->Reviewer2_Commented_Edition) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr><th> </th></tr>
                                    <tr>
                                        <th>Revised Journal Manuscript:</th>
                                        <td>
                                        <a href="{{ asset($item->File_Forwarded_to_JC_by_Author) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Commented Journal Manuscript by Editorial:</th>
                                        <td>
                                        <a href="{{ asset($item->File_Return_Manuscript_After_Revised) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr><td></td></tr>
                                    <tr>
                                         <td>
                                            <a href="#forwardtoeditorialteam" data-toggle="modal" class="btn btn-primary"></i> Forward Editorial </a>
                                            <a href="#returntoauthor" data-toggle="modal" class="btn btn-danger"></i> Return To Author </a>
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
    <style>
        .fond{
            font-size: 14px;
        }
        .width{
            width: 40%;
        }
        textarea{
            width: 40%;
        }
        #Editor{
            color: green;
        }
    </style>
    <script>
    $('#returntoauthor').modal('show');
</script>
@stop