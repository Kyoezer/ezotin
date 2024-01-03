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

                    {{ Form::open(['url' => 'web/journalforwardtoauhtor/', 'files'=>true]) }}
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
                                                    <h3 style="color:#107C10"><b>Reviewer 1 </b><b id="reviewer1_name">( Academician )</b></h3>
                                                </th>
                                            </tr>
                                        
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
                                   
                                    
                                            <tr>
                                                <th>
                                                    <h3 style="color:#107C10"><b>Reviewer 2 </b><b id="reviewer1_name">( Field Expert )</b></h3>                                                </th>
                                            </tr>
                                        
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
                                    <tr><th> </th></tr>
                                    <tr>
                                        <th>Peer Review Checklist Upload( Reviewer 1 ):<p id="reviewer1_name"><b>( Academician  )</b></p></th>
                                        <td><div class="form-group">
                                            <input class="center-block" type="file" name="reviewer1_checklist_edition"> <br>
                                            
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Commented Manuscript Upload( Reviewer 1 ):<p id="reviewer1_name"><b>( Academician  )</b></p></th>
                                        <td><div class="form-group">
                                            <input class="center-block" type="file" name="reviewer1_commented_edition"> <br>
                                            
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Peer Review Checklist Upload( Reviewer 2 ):<p id="reviewer1_name"><b>( Field Expert )</b></p></th>
                                        <td><div class="form-group">
                                            <input class="center-block" type="file" name="reviewer2_checklist_edition"  id="filediv"> <br>
                                            
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Commented Manuscript Upload( Reviewer 2 ):<p id="reviewer1_name"><b>( Field Expert )</b></p></th>
                                        <td><div class="form-group">
                                            <input class="center-block" type="file" name="reviewer2_commented_edition"  id="filediv"> <br>
                                            
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Give your remarks:</th><br>
                                        <td>
                                            <textarea name="remarkByJctoAuhtor" id="jcremarktoauthor"></textarea>
                                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                        
                                    </tr>
                                    <br><br><br>
                                    <tr>
                                         <td>
                                            <button type="submit" onclick="validateField()" class="btn btn-primary">Forward</button>
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
            width: 40%;
        }
        .center-block{
                background: #78D5CD;
                padding: 15px;
                margin-top: 0px;
                /* font-family: Arial; */
                margin-left: 0%;
            }
            #filediv{
                background: #CB9EF1;
                padding: 15px;
                margin-top: 0px;
                /* font-family: Arial; */
                margin-left: 0%;
            }
		#Editor{
            color: green;
        }
	#reviewer1_name{
            color: red;
        }
    </style>
	<script>
    function validateField(e) {
        var status = true;
        $("Form").submit(function(event) {
            debugger;
            if ($('#jcremarktoauthor').val() != '') {

            } else {
                status = false;
                alert('Please give your Remark.');
                event.preventDefault();
            }
        });
    }
</script>
@stop
