@extends('master')

@section('content')
{{ Form::open(['url' => 'web/journalsendbacktoauthor/','role'=>'form','files'=>true]) }}
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
{{ Form::open(['url' => 'web/journalforwardtoeditorial/']) }}
<div id="forward" class="modal fade bs-modal-lg" role="dialog" aria-labelledby="forward" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
            <h3 class="text-center" style="color: green;"><b>FORWARD TO EDITORIAL TEAM</b></h3>
            <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label"><b>Remark:</b></label>
                            <input type="text" name="remark" value="" class="form-control required" placeholder="">
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
                        <?php if ($item->Task_Status_Id == 27) { ?>
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
                                    <th>New File:</th>
                                    <td>
                                        <a href="{{ asset($item->File) }}" target="_blank">
                                            <b>Download File</b>
                                        </a>
                                    </td>
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
                                <th>Submitted Date:</th>
                                <td>{{date('d/m/Y', strtotime($item->CreatedOn))}}</td>
                            </tr>
                            <br><br><br>
                            <tr>
                                <td>
                                        <a href="#forward" data-toggle="modal" class="btn btn-primary"></i>Forward</a>
                                        <a href="#returntoauthor" data-toggle="modal" class="btn btn-danger"></i> Return </a>
                                </td>
                            </tr>
                            @endforeach
                        <?php } else {?>
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
                                <th>Title:</th>
                                <td>{{ $item->Name_of_Title }}</td>
                            </tr>
                            <?php if ($item->Task_Status_Id == 5) { ?>
                                <tr>
                                    <th>File:</th>
                                    <td>
                                        <a href="{{ asset($item->File_Forwarded_to_JC_by_Chief) }}" target="_blank">
                                            <b>Download File</b>
                                        </a>
                                    </td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th>File:</th>
                                    <td>
                                        <a href="{{ asset($item->File) }}" target="_blank">
                                            <b>Download File</b>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th>Submitted Date:</th>
                                <td>{{date('d/m/Y', strtotime($item->CreatedOn))}}</td>
                            </tr>
                            <?php if ($item->Task_Status_Id == 14) { ?>
                                <tr>
                                    <th>Reviewer Assigned by Editorial:</th>
                                    @foreach ($reviewerlist as $items)
                                    <td>{{ $items->FullName }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th>Forward To Reviewer 1:</th>
                                    <td>
                                        <select name="reviewerId" required class="bg-info bold" style="padding: 1mm">
                                            <option value="">Select Reviewer Verifier 1 </option>
                                            @foreach ($reviewerlist as $reviewer)
                                            <option value="{{ $reviewer->Id }}">{{ $reviewer->FullName }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                    </td>
                                </tr>
                                <tr>
                                    <th>Forward To Reviewer 2:</th>
                                    <td>
                                        <select name="reviewer2Id" required class="bg-info bold" style="padding: 1mm">
                                            <option value="">Select Reviewer Verifier 2</option>
                                            @foreach ($reviewerlist as $reviewer)
                                            <option value="{{ $reviewer->Id }}">{{ $reviewer->FullName }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($item->Task_Status_Id == 9) { ?>
                                <tr>
                                <th>Remark from Editorial Team:</th>
                                <td>{{ $item->Return_Remark_By_Editorteam_toJc }}</td>
                            </tr>
                            <?php } ?>
                            <?php if ($item->Task_Status_Id == 11) { ?>
                                <table class="table pmd-table table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <h3 style="color:#107C10"><b>Reviewer 1 Details</b></h3>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fond">
                                        @foreach ($content as $item)
                                        <tr>
                                            <th class="width">Peer Review Checklist:</th>
                                            <td>
                                                <a href="{{ asset($item->Reviewer1_Checklist) }}" target="_blank">
                                                    <b>Download File</b>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Commented Manuscript:</th>
                                            <td>
                                                <a href="{{ asset($item->Reviewer1_Commented) }}" target="_blank">
                                                    <b>Download File</b>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>

                                </table>
                            <?php } ?>
                            <?php if ($item->Task_Status_Id2 == 11) { ?>
                                <table class="table pmd-table table-striped">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>
                                                <h3 style="color:#107C10"><b>Reviewer 2 Details</b></h3>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="fond">
                                        @foreach ($content as $item)
                                        <tr>
                                            <th class="width">Peer Review Checklist:</th>
                                            <td>
                                                <a href="{{ asset($item->Reviewer2_Checklist) }}" target="_blank">
                                                    <b>Download File</b>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Commented Manuscript:</th>
                                            <td>
                                                <a href="{{ asset($item->Reviewer2_Commented) }}" target="_blank">
                                                    <b>Download File</b>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                       
                                    </tbody>

                                </table>
                            <?php } ?>
                            <?php if ($item->Task_Status_Id == 20) { ?>
                            <tr>
                                                <th>
                                                    <h4 style="color:#107C10"><b>Reviewer 1 Details </b><b id="reviewer1_name"> ( Academician )</b></h4>
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
                                                    <h4 style="color:#107C10"><b>Reviewer 2 Details </b><b id="reviewer1_name"> ( Field Expert )</b></h4>
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
                                    <?php } ?>
                            <br><br><br>
                            <tr>
                                <td>
                                    <?php if ($item->Task_Status_Id == 11) { ?>
                                        <a href="{{ url('/web/journalforwardtocf/' . $item->Application_No) }}">
                                            <button class="btn btn-primary" type="button">Forward</button>
                                        </a>
                                        <a href="{{ url('/web/journalsendbacktoeditorial/' . $item->Application_No) }}">
                                            <button class="btn btn-danger" type="button">Return Editorial</button>
                                        </a>
                                    <?php } elseif ($item->Task_Status_Id == 14) { ?>
                                        <button formaction="/web/journalforwardtoreviewer/" type="submit" class="btn btn-primary">Forward</button>
                                        <a href="{{ url('/web/journalsendbacktoeditorial/' . $item->Application_No) }}">
                                            <button class="btn btn-danger" type="button">Return Editorial</button>
                                        </a>
                                    <?php } else { ?>
                                        <a href="#forward" data-toggle="modal" class="btn btn-primary"></i>Forward</a>
                                        <a href="#returntoauthor" data-toggle="modal" class="btn btn-danger"></i> Return </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            @endforeach

                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .fond {
        font-size: 14px;
    }

    .width {
        width: 40%;
    }

    textarea {
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