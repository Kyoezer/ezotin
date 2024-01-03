@extends('websitemaster')
@section('main-content')
    <div class="container">
        <div class="row">
            <div class="col-md-9" id="main_front">
                <h4 class="text-primary"><strong>Current Issue</strong></h4>
                {{HTML::decode($content)}}
                {{-- <p>
                    Vol. 8 No. 2 (2021) <br><br>
                    Published: 2022-01-01<br><br>
                </p> --}}

                <div class="portlet light bordered">
                    <div class="portlet-body form">
                        <!-- Striped table -->
                        <div class="table-responsive card pmd-card">
                            <!-- Table -->
                            <table class="table pmd-table table-striped">
                                <thead class="thead">

                                    <tr>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    @foreach ($content as $detail)
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>{{ $detail->Application_No }}</td>
                                            {{-- <td>{{ $detail->Remarks }}</td> --}}
                                            <td>{{ date('d/m/Y', strtotime($detail->CreatedOn)) }}</td>

                                            <td>
                                                <a href="#">
                                                    <button class="btn btn-primary" type="button">View</button>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="container">
                <div class="row">
                    <div class="col-md-3" id="submission">
                        <h4><strong>Open Access</strong></h4> 
                    </div>
                    <div class="col-md-3" id="inner">

                    </div>
                    <div class="col-md-3" id="submission">
                        <h4><strong>Make Submission</strong></h4> 
                    </div>
                    <div class="col-md-3" id="inner">
                        <a href="journal"><br><br> Click here..</a>
                    </div>
                    <div class="col-md-3" id="submission">
                        <h4><strong>Information</strong></h4> 
                    </div>
                    <div class="col-md-3" id="inner">

                    </div>
                    
                    
                </div>
                
            </div>
            
        </div> 
    </div>
        <style>
            #main_front {
                border: 1px solid lightgray;
                border-radius: 2mm;
                height: 10cm;
                
            }
            #submission{
                border: 1px solid lightgray;
                border-radius: 2mm;
                background-color: lightblue;
            }
            #inner{
                border: 1px solid lightgray;
                min-height: 2.5cm;
                border-radius: 2mm;
                margin-bottom: 4mm;
            }

        </style>
 @stop
