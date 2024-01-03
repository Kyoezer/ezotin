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
                                <thead class="thead_mytask">
                                    <h3><b>Journal Coordinator </b><b id="Editor">( Managing Editor )</b></h3>
                                    <p class="home"><b>My Task Table / </b><a href="journalcoordinatorverification"> Group Task Table </a></p>
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
                                    @foreach ($mytask as $detail)
                                    <?php if($detail->Task_Status_Id == 14){?>
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatordetailstoselectreviewer/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php }else if($detail->Task_Status_Id == 5 || $detail->Task_Status_Id == 26){?>
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatordetailsforpublication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php }else if($detail->Task_Status_Id == 11){?>
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatordetailstosendbacktoauthor/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php }else if($detail->Task_Status_Id == 17){?>
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatorreviseforwardtoeditorteam/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php }else if($detail->Task_Status_Id == 22){?>
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatorreviseforwardagaintoeditorteam/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php }else if($detail->Task_Status_Id == 19){?>
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatorreviseforwardfromeditorteam/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
					<?php }else if($detail->Task_Status_Id == 24){?>
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatorreviseagainforwardfromeditorteam/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php }else if($detail->Task_Status_Id == 20){?>
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatorreviserejectedfromeditorteam/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php }else {?>
                                            <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            <td class="title">{{ $detail->Name_of_Title }}</td>
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>
                                            <td class="status"><b>{{ $detail->Task_Status }}</b></td>
                                            <td>  
                                                <a
                                                    href="{{ url('/web/journalcoordinatordetails/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">View Details</button>
                                                </a>
                                                <a
                                                    href="{{ url('/web/journalunclaimapplication/' . $detail->Application_No) }}">
                                                    <button class="btn btn-primary" type="button">Unclaim</button>
                                                </a>
                                            </td>
                                        </tr>
                                            <?php }?>
                                    @endforeach
                                    
                                </tbody>
                            </table>
                    </div>
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
            .status{
                max-width: 5cm;
            }
            #Editor{
            color: green;
        }
        </style>
    @stop
