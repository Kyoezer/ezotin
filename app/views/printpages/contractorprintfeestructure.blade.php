@extends('printmaster')
@section('content')
<table class="data-small-with-border">
	<thead>
	<tr>
		<th>Category</th>
		<th>Class</th>
		<th>Fee (Nu.)</th>
	</tr>
	</thead>
	<tbody>
	<?php $totalFee=0; ?>
	@foreach($feeStructures as $feeStructure)
		<tr>
			<td>
				{{$feeStructure->CategoryCode.' ('.$feeStructure->Category.')'}}
			</td>
			<td>
				{{$feeStructure->AppliedClassificationCode.' ('.$feeStructure->AppliedClassification.')'}}
			</td>
			<td align="right">
				<?php $feeAmount=$feeStructure->AppliedRegistrationFee; ?>
				{{number_format($feeStructure->AppliedRegistrationFee,2)}}
			</td>
			<?php $totalFee+=$feeAmount;?>
		</tr>
	@endforeach
	<tr align="right">
		<td colspan="2"><strong>Total</strong></td>
		<td><strong>{{{number_format($totalFee,2)}}}</strong></td>
	</tr>
	</tbody>
</table>
@stop