@extends('horizontalmenumaster')
@section('pagescripts')
    {{ HTML::script('assets/global/scripts/etool.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>{{$title}}
		</div>
	</div>
	<div class="portlet-body flip-scroll">
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
                                    Work Id
                                </th>
                                <th>Procuring Agency</th>
                                <th class="">
                                     Last Dt and Time of Submission
                                </th>
                                <th class="">
                                     Opening Dt. and Time
                                </th>
                                <th>
                                    Category
                                </th>
                                <th>
                                    Classification
                                </th>
                                <th class="">
                                     Name of the Work
                                </th>
                                <th class="">
                                     Contract Period (Months)
                                </th>
                                <th>
                                    Estimated Project Cost
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $count = 1; ?>
                        @forelse($reportData as $uploadedTender)
                            <tr>
                                <td>
                                     {{$count}}
                                </td>
                                <td>
                                     {{$uploadedTender->WorkId}}
                                </td>
                                <td>
                                    {{$uploadedTender->Agency}}
                                </td>
                                <td class="">
                                     {{convertDateTimeToClientFormat($uploadedTender->LastDateAndTimeOfSubmission)}}
                                </td>
                                <td class="">
                                    {{convertDateTimeToClientFormat($uploadedTender->TenderOpeningDateAndTime)}}
                                </td>
                                <td>
                                    {{$uploadedTender->Category}}
                                </td>
                                <td>
                                    {{$uploadedTender->Class}}
                                </td>
                                <td class="">
                                    {{stripslashes(strip_tags($uploadedTender->NameOfWork))}}
                                </td>
                                <td>
                                    {{$uploadedTender->ContractPeriod}}
                                </td>
                                <td>
                                    {{$uploadedTender->ProjectEstimateCost}}
                                </td>
                            </tr>
                            <?php $count++; ?>
                            @empty
                            <tr><td colspan="10" class="font-red text-center">No data to display</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
        </div>
        <?php $count++; ?>
        </div>
	</div>
</div>
@stop