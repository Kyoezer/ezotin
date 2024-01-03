@extends('master')

@section('content')

    <div class="row">
        <div class="col-md-12"> 
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!-- Striped table -->
                    
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        
                        <table class="table pmd-table table-striped">
                            
                            <thead class="thead">
                            <div class="add"><a href="addgroupchecklist"><button type="button" id="add" class="btn default btn-xs green-seagreen"><i class="fa fa-edit"></i> Add Group</button></a></div>
                                <h3><b>Journal Checklist</b></h3>
                                
                                <p class="home"><b>Group Checklist Table / <a href="journalreviewermainchecklist"> Checklist Table </a> / <a href="journalreviewersubchecklist"> Sub-Checklist Table </a></b></p>
                                
                                <tr>
                                    <th>Sl</th>
                                    <th>Group Title</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($groupList as $detail)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td><textarea readonly class="title">{{ $detail->Title }}</textarea></td>
                                         <td>
                                            <a
                                                href="{{ url('/web/editgroupchecklist/' . $detail->Id) }}">
                                                <button class="btn default btn-xs green-seagreen" type="submit"><i class="fa fa-edit"></i> Edit</button>
                                                
                                            </a>
                                            <a href="{{ url('/web/deletegroupchecklist/' . $detail->Id) }}" class="btn default btn-xs bg-red-sunglo"><i class="fa fa-edit"></i> Delete</a>
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
            .thead{
		 background-color: lightgray   
	        }
    
            textarea{
                border: hidden;
                width: 7cm;
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
           
        </style>
        

@stop