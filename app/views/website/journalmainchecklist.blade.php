@extends('master')

@section('content')

<div class="container-fluid" id="main">
    <div class="row">
        <div class="col-md-12" id="register">

            <h4 class="head"><strong>Reviewer Checklist</strong></h4><br>
            {{ Form::open(['url' => 'web/checklistview']) }}

                <!-- <div class="form-group">
                    <label class="required"><strong>Checklist :</strong></label>
                    <select  id="groupchecklist" name="groupchecklist" required  class="" >
                    <option value="">Select Group Checklist</option>
                    @foreach ($groupList as $reviewer)
                    <option value="{{ $reviewer->Id }}">{{ $reviewer->Title }}</option>
                    @endforeach
                    </select>
                    <input type="hidden" name="Id" value="{{ $reviewer->Id }}">
                    <button>View</button>
                </div> -->
                <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        
                        <table class="table pmd-table table-striped">
                            
                            <thead class="thead">
                            <div class="add"><a href="addchecklist"><button type="button" id="add" class="btn default btn-xs green-seagreen"><i class="fa fa-edit"></i> Add Group</button></a></div>        
                                <p class="home"><b>Checklist Table / <a href="reviewerchecklist"> Group Checklist Table </a> / <a href="journalreviewersubchecklist"> Sub-Checklist Table </a></b></p>
                                
                                <tr>
                                    <th>Sl</th>
                                    <th>Group</th>
                                    <th>Checklist</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($checkList as $detail)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $detail->Title }}</td>
                                        <td>{{ $detail->Name }}</td>
                                         <td>
                                            <a
                                                href="{{ url('/web/editchecklist/' . $detail->Id) }}">
                                                <button class="btn default btn-xs green-seagreen" type="submit"><i class="fa fa-edit"></i> Edit</button>
                                                
                                            </a>
                                            <a href="{{ url('/web/deletechecklist/' . $detail->Id) }}" class="btn default btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Delete</a>
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
    #groupchecklist{
        padding: 2mm;
        margin-left: 1cm;
        font-weight: bolder;
        letter-spacing: 0.1mm;
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
            
           
        </style>
       
@stop