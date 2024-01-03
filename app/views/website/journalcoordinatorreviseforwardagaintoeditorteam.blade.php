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

                    {{ Form::open(['url' => 'web/journalagainforwardtoeditorteam/']) }}
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
                                                <th>
                                                    <h4 style="color:#107C10"><b>Reviewer 1 ( Academician )</b></h4>
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
                                                    <h4 style="color:#107C10"><b>Reviewer 2 ( Field Expert )</b></h4>
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
                                    
                                    <tr>
                                        <th>Give your remarks:</th><br>
                                        <td>
                                            <textarea name="remarkByJctoEditorteam" id="remarksByJctoEditorteam"></textarea>
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
        #Editor{
            color: green;
        }
    </style>
    <script>
    function validateField(e) {
        var status = true;
        $("Form").submit(function(event) {
            debugger;
            if ($('#remarksByJctoEditorteam').val() != '') {

            } else {
                status = false;
                alert('Please give your Remark.');
                event.preventDefault();
            }
        });
    }
</script>
@stop
