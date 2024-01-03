@extends('master')

@section('content')

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
                
                    {{ Form::open(['url' => 'web/journalapprovedbyjc/', 'files'=>true]) }}
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            
                            <thead class="thead-dark">
                                <h3><b>Journal Coordinator </b><b id="Editor">( Managing Editor )</b></h3>

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
                                        <th>Email:</th>
                                        @foreach ($authorname as $items)
                                        <td>{{ $items->Email }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Contact:</th>
                                        @foreach ($authorname as $items)
                                        <td>{{ $items->Contact }}</td>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>
                                            Remark by Editor In-Chief:
                                        </th>
                                        <td>
                                            {{ $item->Remark_By_Chief }}
                                        </td>
                                    </tr>
                                    <tr>
                                                <th>
                                                    <h4 style="color:#107C10"><b>Reviewer 1 (Academician)</b></h4>
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
                                                    <h4 style="color:#107C10"><b>Reviewer 2 (Field Expert)</b></h4>
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
                                    <?php if ($item->Task_Status_Id == 5) { ?>
                                    <tr>
                                        <th>Revised Journal Manuscript:</th>
                                        <td>
                                        <a href="{{ asset($item->File_Forwarded_to_JC_by_Author) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php }else {?>
                                        <tr>
                                        <th>Revised Journal Manuscript: <b style="color:#107C10">(Old)</b></th>
                                        <td>
                                        <a href="{{ asset($item->File_Forwarded_to_JC_by_Author) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Revised Journal Manuscript: <b style="color:#107C10">(New)</b></th>
                                        <td>
                                        <a href="{{ asset($item->File_Forwarded_again_to_JC_by_Author) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
					<tr>
                                        <th>Journal Manuscript Edited by Editorial Team:</th>
                                        <td>
                                        <a href="{{ asset($item->File_Forwarded_to_JC_by_EditorialTeam) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
					<tr>
                                        <th>File Forwarded from Editor In-Chief:</th>
                                        <td>
                                            <a href="{{ asset($item->File_Forwarded_to_JC_by_Chief) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Upload file:</th>
                                        <td><div class="form-group">
                                            <input class="center-block" type="file" name="file_publish"> <br>
                                            
                                        </div>
                                        </td>
                                    </tr>
					<hr>
                                    <tr>
                                        <th>Journal Title:</th><br>
                                        <td>
                                            <textarea name="final_title" id="final_title"></textarea>
                                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Author's Name:</th><br>
                                        <td>
                                            <textarea name="final_author_name" id="final_author_name"></textarea>
                                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Abstract:</th><br>
                                        <td>
                                            <textarea name="abstract" id="abstract"></textarea>
                                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                    </tr>
                                    <tr>
                                         <td>
                                            <button type="submit" onclick="validateField()" class="btn green">Publish</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ Form::close() }}
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
            width: 50%;
        }
        #abstract{
            width: 500px;
            height: 200px; 
        }
        .center-block{
                background: #78D5CD;
                padding: 15px;
                margin-top: 20px;
                font-family: Arial;
                margin-left: 0%;
            }
	#Editor{
            color: green;
        }
    </style>
	<script>
    function validateField(e) {
        var status = true;
        $("Form").submit(function(event) {
            debugger;
            if ($('#final_title').val() != '') {
            
            } else {
                status = false;
                alert('Please fill all the fields.');
                event.preventDefault();
            }
            if ($('#final_author_name').val() != '') {
            
        } else {
            status = false;
            alert('Please fill all the fields.');
            event.preventDefault();
        }
        });
    }
</script>
@stop
