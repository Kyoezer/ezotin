@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    @if (Session::get('successeditorial'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('successeditorial') }}</strong>
                        </div>
                    @endif
                    <a href="javascript:;" onClick="toggle();"></a>

                    <!-- Striped table -->
                    {{ Form::open(['url' => 'web/journalforwardchieftojc/', 'files'=>true]) }}
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            <thead class="thead-dark">
                                <h3><b>Journal Editor In-Chief</b></h3>
                                 
                            </thead>
                            <tbody class="fond">
                                <?php $totalRadio = 0; ?>
                                @foreach ($content as $item)
                                    <tr>
                                        <th class="width">Application No:</th>
                                        <td>{{ $item->Application_No }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name of the Author:</th>
                                        <!-- < $userName = Session::get('userName');?>
                                        <td></td> -->
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
                                        <th>
                                            Remark from Journal Coordinator:
                                        </th>
                                        <td>
                                            {{ $item->Remark_By_Jc_toChief}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>CreatedOn:</th>
                                        <td>{{ date('d/m/Y', strtotime($item->CreatedOn)) }}</td>
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
                                        <th>Journal Manuscript Edited by Editorial Team:</th>
                                        <td>
                                        <a href="{{ asset($item->File_Forwarded_to_JC_by_EditorialTeam) }}" target="_blank">
                                                <b>Download File</b>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="wid">Upload File:</th>
                                        <td>
                                            <input class="center-block" type="file" name="file_forwarded_to_jc_by_chief"> <br>
                                            @if ($errors->has('file'))
                                                <p class="error1">{{ $errors->first('file') }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Give your remark</th>
                                        <td>
                                            <textarea name="chief_remark" id="chief_remarks"></textarea>
                                            <input type="hidden" name="Application_No" value="{{$item->Application_No}}">
                                        </td>
                                    </tr>
                                    <br>
                                    <br>
                                    <tr>
                                        <td>
                                            <!-- {{-- <a href="{{ url('/web/journalforwardtojc/' . $item->Application_No) }}">
                                                <button class="btn btn-primary" type="button">Forward</button>
                                            </a> --}} -->
                                            <button type="submit" onclick="validateField()" class="btn btn-primary">Forward</button>
                                            <!-- <a href="{{ url('/web/journalrejectedbychief/' . $item->Application_No) }}">
                                                <button class="btn btn-danger" type="button">Return</button>
                                            </a> -->
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
        .center-block{
                background: #78D5CD;
                padding: 10px;
                margin-top: 5px;
                font-family: Arial;
                margin-left: 0%;
            } 
                label{
                    margin-right: .5cm;
                }
                .danger{
            color: #f00;
            font-size: 13px;
        }
        .fond{
            font-size: 14px;
        }
        .width{
            width: 40%;
        }
        textarea{
            width: 40%;
        }
        </style>
        <script>
    function validateField(e) {
        var status = true;
        $("Form").submit(function(event) {
            debugger;
            if ($('#chief_remarks').val() != '') {

            } else {
                status = false;
                alert('Please give your Remark.');
                event.preventDefault();
            }
        });
    }
</script>
@stop
