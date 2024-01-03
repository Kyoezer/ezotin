@extends('emailmaster')
@section('emailcontent')
<p><strong>{{$applicantName}}</strong></p>
<p class="lead">Application No:<i>{{$applicationNo}}</i> Dt.<i>{{$applicationDate}}</i></p>
<p>{{$mailMessage}}</p>
<p><strong>Reapply within 1 month (30 days). After this period, your application will be cancelled.</strong></p>
<p><strong>Reason for Rejection (Remarks)</strong></p>
<p>{{$remarksByRejector}}</p>
<!-- Callout Panel -->                           
@stop
@section('notes')
<table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; width: 100%; margin: 0; padding: 0;"><tr style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;"><td style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 10px 0;">&#13;
		Please Reapply
		<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6; font-weight: normal; margin: 0 0 10px; padding: 0;"><a href="http://www.cdb.gov.bt/{{$prefix.'/apprejected/'.$referenceApplicant.'/'.$rejectionSysCode}}" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 2; color: #FFF; text-decoration: none; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 25px; background-color: #1ba39c; margin: 0 10px 0 0; padding: 0; border-color: #1ba39c; border-style: solid; border-width: 10px 20px;">Click Here to Reapply</a> </p>&#13;
	</td>&#13;
</tr></table>
@stop