@extends('master')

@section('content')
    <div class="row">
        <div class="col-md-12">
			
			@if (Session::get('successfully'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('successfully') }}</strong>
                        </div>
                    @endif
			@if (Session::get('successreviewer'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('successreviewer') }}</strong>
                        </div>
                    @endif
			@if (Session::get('approved'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('approved') }}</strong>
                        </div>
                    @endif		
			@if (Session::get('rejection'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('rejection') }}</strong>
                        </div>
                    @endif
                    @if (Session::get('jcclaim'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('jcclaim') }}</strong>
                        </div>
                    @endif
                    @if (Session::get('jcalreadyclaim'))
                        <div class="alert alert-warning alert-block">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>{{ Session::get('jcalreadyclaim') }}</strong>
                        </div>
                    @endif
                    
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <!-- Striped table -->
                    <div class="table-responsive card pmd-card">
                        <!-- Table -->
                        <table class="table pmd-table table-striped">
                            <thead class="thead">
                                <h3><b>Journal Coordinator </b><b id="Editor">( Managing Editor )</b></h3>
                                <p class="home"><b>Group Task Table / </b><a href="journalcoordinatormytask"> My Task Table </a></p>
                                <tr>
                                    <th>Sl</th>
                                    <th>Application No</th>
                                    <th>Title</th>
                                    <th>Submitted Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach ($grouptask as $detail)
                                    <tr>
                                        <td>{{ $i++ }}</td>
                                        <td>{{ $detail->Application_No }}</td>
                                        <td class="title">{{ $detail->Name_of_Title }}</td>
                                        <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                        <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                         <td>
                                            <a
                                                href="{{ url('/web/journalclaimapplication/' . $detail->Application_No) }}">
                                                <button class="btn btn-primary" type="button">Claim Application</button>
                                            </a>
                                        </td> 
                                    </tr>
                                @endforeach
                               
                                
                            </tbody>
                            
                        </table>
                        
                        
                </div>
            </div>
        </div>
        <style>
            .thead{
		 background-color: lightgray   
	}
    .thead_mytask{
        background-color: lightblue
            }
            .center{
                text-align: center;
                margin-bottom: 2cm;
            }
            textarea{
                border: hidden;
                width: 7cm;
                background: #10000000;
                resize: none;
            }
            .title{
                max-width: 9cm;
            }
	#Editor{
            color: green;
        }
	.status{
                max-width: 5cm;
            }
        </style>
    @stop
