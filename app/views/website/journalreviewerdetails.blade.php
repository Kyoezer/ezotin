@extends('master')

@section('content')

    <div class="row">
        <div class="col-md-12">
            @if (Session::get('successreviewer'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('successreviewer') }}</strong>
                        </div>
                    @endif
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    
                    <!-- Striped table -->
                    {{ Form::open(['url' => 'web/reviewerforwardtojc/', 'files'=>true]) }}

                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            <thead class="thead-dark">
                                <h3><b>Journal Reviewer</b></h3>
                                <tr>
                                </tr>
                            </thead>
                            <tbody class="fond">
                                @foreach ($content as $item)
                                    <tr>
                                        <th style="width:40%">Application No:</th>
                                        <td><input type="hidden" name="Application_No"
                                                value="{{ $item->Application_No }}">{{ $item->Application_No }}</td>
                                    </tr>
                                    <tr>
                                        <th>Title:</th>
                                        <td>{{ $item->Name_of_Title }}</td>
                                    </tr>
                                    <tr>
                                        <th>File:</th>
                                        <td>
                                            <a href="{{ asset($item->File_Forwarded_to_Reviewer) }}" target="_blank">
                                                Download File
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Remark by Journal Coordinator:
                                        </th>
                                        <td>
                                            {{ $item->Jc_Remark_to_Reviewer}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Submitted Date:</th>
                                        <td>{{ date('d/m/Y', strtotime($item->CreatedOn)) }}</td>
                                    </tr>

                                    <tr>
                                        <th>Upload Peer Review Checklist:</th>
                                        <td><div class="form-group">
                                            <input class="center-block" type="file" name="file"> <br>
                                            @if ($errors->has('file'))
                                                <p class="error1">{{ $errors->first('file') }}</p>
                                            @endif
                                            
                                        </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Upload Commented Manuscript:</th>
                                        <td><div class="form-group">
                                            <input class="center-block" type="file" name="commented_file"> <br>
                                            @if ($errors->has('file'))
                                                <p class="error1">{{ $errors->first('file') }}</p>
                                            @endif
                                            
                                        </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td>
                                           <!-- <a href="{{ url('/web/journalforwardtochief/'. $item->Application_No) }}">
                                        <button class="btn btn-primary" type="button">Forward</button>
                                        </a>  -->
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
        input{
        cursor: pointer;
        position: absolute;
        }
        .danger{
            color: #f00;
            font-size: 13px;
        }
        input[type="radio"] {
            -ms-transform: scale(0); /*IE 9 */
            -webkit-transform: scale(1); /* Chrome, Safari, Opera */
             transform: scale(2);
            padding: 1cm;
        }
        label{
            margin-right: .5cm;
        }
        .fond{
            font-size: 14px;
        }
        .center-block{
                background: #78D5CD;
                padding: 10px;
                margin-top: 5px;
                font-family: Arial;
            }
        
    </style>
    <script>
        function validateField(e)
        {
            var status = true;
            $( "form" ).submit(function( event ) {  
                debugger;
               
                if(status == true)
                {
                    alert('Do you want to forward ?');
                    return;
                }
                
            });  
        }
    </script>
@stop
