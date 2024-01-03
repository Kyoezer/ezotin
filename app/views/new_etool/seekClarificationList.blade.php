@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<input type="hidden" name="RoutePrefix" value="{{Request::segment(1)}}"/>
 
<div class="portlet light bordered">
	<div class="portlet-title">
        <div class="caption col-lg-12">
			<i class="fa fa-cogs"></i>Seek Clarification
            <a class="btn btn-primary pull-right" href="#addSeekClarification" data-toggle="modal" ><i class="fa fa-plus"></i> Seek Clarification</a>
		</div>
	</div>
	<div class="portlet-body flip-scroll">
        <?php $routePrefix = Request::segment(1); ?>
     
        <div class="panel-group accordion" id="tender-accordion">
        <?php $count = 1; ?>
        <div class="panel panel-default">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
                        <thead class="flip-content">
                            <tr>
                                <th class="order">
                                     Sl. No.
                                </th>
                                <th>
                                    Tender Id
                                </th>
                                <th class="">
                                     CDB No.
                                </th>
                                <th class="">
                                     Enquiry Date
                                </th>
                                <th class="">
                                     Status
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach($seekClarificationList as $seekClarification)
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td>{{$seekClarification->Tender_Id}}</td>
                                    <td>{{$seekClarification->CDB_No}}</td>
                                    <td>{{$seekClarification->Created_On}}</td>
                                    <td>@if($seekClarification->Status=='A')
                                            Active 
                                        @elseif($seekClarification->Status=='C')
                                            Close
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{URL::to("$routePrefix/viewseekclarification/$seekClarification->Id/$contractorId")}}" class="btn btn-sm btn-success">
                                            <i class="fa fa-eye"></i> View
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
</div>

<div id="addSeekClarification" class="modal fade in" role="dialog" aria-labelledby="modalmessagebox" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        {{Form::open(array('url'=>'newEtl/etlPostSeekClarification'))}}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h3 id="modalmessageheader" class="modal-title font-red-intense">Seek Clarification</h3>
            </div>
            <div class="modal-body">
                {{Form::hidden('Status','A',array('class'=>'Status'))}}
                {{Form::hidden('Tender_Id',$tenderId,array('class'=>'etltenderid'))}}
                {{Form::hidden('CDB_No',$cdbNo,array('class'=>'contractorCDBno'))}}
                {{Form::hidden('contractorId',$contractorId,array('class'=>'contractorId'))}}
                <label>Enquiry</label>
                <textarea name="Enquiry" class="form-control"></textarea>
            </div>

          
               
            <div class="modal-footer">
                
                <div class="btn-set">
                    <button type="submit" class="btn green">Post</button>
                    <button type="button" class="btn red" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            {{Form::close()}}
        </div>
    </div>
</div>


@stop