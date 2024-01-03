@extends('master')

@section('content')
{{ Form::open(['url' => 'web/journalreturnbacktojcfromeditorialteam/','role'=>'form', 'files'=>true]) }}
<div id="returntojc" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="returntojc" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            <h3 class="text-center" style="color: green;"><b>GIVE YOUR REMARK.</b></h3>
            <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><b>Remark:</b></label>
                            <input type="text" name="remarkfromeditorialteam" value="" class="form-control required" placeholder="">
                            @foreach ($content as $item)
                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>File:</b></label>
                            <input type="file" name="file_return_to_jc_byeditorialteam" class="form-control">
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
{{ Form::open(['url' => 'web/journalforwardtojcbyeditorteam/','role'=>'form', 'files'=>true]) }}
<div id="forwardtojc" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="forwardtojc" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            <h3 class="text-center" style="color: green;"><b>Forward to Journal Coordinator/ Managing Editor.</b></h3>
            <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><b>File:</b></label>
                            <input type="file" name="file_forwarded_to_jc_byeditorialteam" class="form-control required">
                        </div>
                        <div class="form-group">
                            <label class="control-label"><b>Remark:</b></label>
                            <input type="text" name="remarkByEditorteamtoJc" value="" class="form-control required" placeholder="">
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
                                <h3><b>Editorial Team</b></h3>
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
                                        <th>Submitted Date:</th>
                                        <td>{{date('d/m/Y', strtotime($item->CreatedOn))}}</td>
                                    </tr>
                                    <tr>
                                        <th>Remark by Journal Coordinator:</th>
                                        <td>{{ $item->Remark_By_JC_toEditorteam }}</td>
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
                                    <tr><th> </th></tr>
                                    <tr>
                                        <th>Revised Journal Manuscript:</th>
                                        <td>
                                        <a href="{{ asset($item->File_Forwarded_to_JC_by_Author) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr><th> </th></tr>
                                    <tr>
                                         <td>
                                            <a href="#forwardtojc" data-toggle="modal" class="btn btn-primary"></i> Forward </a>
                                            <a href="#returntojc" data-toggle="modal" class="btn btn-danger"></i> Return </a>
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
        .center-block{
                background: #78D5CD;
                padding: 15px;
                margin-top: 20px;
                font-family: Arial;
                margin-left: 0%;
            }
    </style>
    <script>
    $('#returntojc').modal('show');
</script>
@stop
