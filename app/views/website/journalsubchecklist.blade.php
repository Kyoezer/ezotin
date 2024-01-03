@extends('master')

@section('content')

<div class="container-fluid" id="main">
    <div class="row">
        <div class="col-md-12" id="register">

            <h4 class="head"><strong>Reviewer Sub-Checklist</strong></h4><br>
            {{ Form::open(['url' => 'web/journalregister']) }}

               
                <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        
                        <table class="table pmd-table table-striped">
                            
                            <thead class="thead">
                            <div class="add"><a href="addsubchecklist"><button type="button" id="add" class="btn default btn-xs green-seagreen"><i class="fa fa-edit"></i> Add Group</button></a></div>        
                                <p class="home"><b>Sub-Checklist Table / <a href="reviewerchecklist"> Group Checklist Table </a> / <a href="reviewerchecklist"> Checklist Table </a></b></p>
                                
                                <tr>
                                    <th>Sl</th>
                                    <th>Group</th>
                                    <th>Checklist</th>
                                    <th>Sub Checklist</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($subcheckList as $detail)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $detail->groupChekclist }}</td>
                                        <td>{{ $detail->cheklist }}</td>
                                        <td>{{ $detail->subChecklist }}</td>
                                         <td>
                                            <a
                                                href="{{ url('/web/editsubchecklist/' . $detail->Id) }}">
                                                <button class="btn default btn-xs green-seagreen" type="submit"><i class="fa fa-edit"></i> Edit</button>
                                                
                                            </a>
                                            <a href="{{ url('/web/deletesubchecklist/' . $detail->Id) }}" class="btn default btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Delete</a>
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

<style>
    #groupchecklist1{
        padding: 2mm;
        margin-left: 1cm;
        background: #0AAC00;
        font-weight: bolder;
        color: #fff;
        letter-spacing: 0.1mm;
        width: 8cm;
    }
            .thead{
		 background-color: lightgray   
	        }
    
            textarea{
                border: hidden;
                width: 9cm;
                background: #10000000;
                resize: none;
            }
            .add{
                float: right;
                
            }
            #add{
                background-color: #0AAC00;
                padding-left: 1cm;
                padding-right: 1cm;
                color: #fff;
                margin-bottom: 1cm;
            }
            .required{
                color: #0AAC00;
            }
            .form-group{
                margin-bottom: 1cm;
            }
            
           
        </style>
       
@stop