@extends('master')
@section('pagescripts')
{{ HTML::script('assets/global/scripts/sys.js') }}
@stop
@section('content')
<div class="portlet light bordered">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>
			<span class="caption-subject">Send EMail and SMS</span>
		</div>
	</div>
	<div class="portlet-body form">
		{{ Form::open(array('url' => 'sys/msendmailsms','role'=>'form'))}}
			<div class="form-body">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="Date">Date</label>
							<div class="input-icon right">
								<i class="fa fa-calendar"></i>
								<input type="text" name="Date" value="" class="form-control datepicker required" placeholder="" readonly>
							</div>
						</div>
						<div class="form-group">
							<label>Send Message As</label>
							<select name="MessageAs" class="form-control required">
								<option value="">---SELECT ONE---</option>
								<option value="1">Email</option>
								<option value="2">SMS</option>
							</select>
						</div>
						<div class="form-group">
							<label>Message For</label>
							<select name="MessageFor" class="form-control required mailandsmsto">
								<option value="">---SELECT ONE---</option>
								<option value="1">CRPS Users</option>
								<option value="2">Etool Users</option>
								<option value="3">CiNet Users</option>
								<option value="4">Contractors</option>
								<option value="5">Consultants</option>
								<option value="6">Architects</option>
								<option value="7">Engineers</option>
								<option value="8">Specialized Trade</option>
								<option value="9">Single/Multiple Recipient(s)</option>
							</select>
						</div>
						<div class="contractoroptions hide">
							<div class="form-group">
								<label>Classification</label>
								<select name="ContractorClassification[]" multiple class="form-control select2">
									@foreach($contractorClassifications as $contractorClassification)
									<option value="{{$contractorClassification->Id}}">{{$contractorClassification->Name}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label>Category</label>
								<select name="ContractorCategory[]" multiple class="form-control select2">
									@foreach($contractorCategories as $contractorCategory)
										<option value="{{$contractorCategory->Id}}">{{$contractorCategory->Code}}</option>
									@endforeach
								</select>
							</div>

						</div>
						<div class="consultantoptions hide">
							<div class="form-group">
								<label>Category</label>
								<select name="ConsultantCategory[]" multiple class="form-control select2">
									@foreach($consultantServiceCategories as $consultantServiceCategory)
										<option value="{{$consultantServiceCategory->Id}}">{{$consultantServiceCategory->Code}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label>Service</label>
								<select name="ConsultantService[]" multiple class="form-control select2">
									@foreach($consultantServices as $consultantService)
										<option value="{{$consultantService->Id}}">{{$consultantService->Code}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="spoptions hide">
							<label for="">Category</label>
							<select name="SPCategory[]" id="" multiple class="form-control select2">
								@foreach($spCategories as $spCategory)
									<option value="{{$spCategory->Id}}">{{$spCategory->Code}}</option>
								@endforeach
							</select>
						</div>
						<div class="commonoptions hide">
							<div class="form-group">
								<label>Dzongkhags</label>
								<select name="Dzongkhag[]" multiple class="form-control select2">
									@foreach($dzongkhags as $dzongkhag)
										<option value="{{$dzongkhag->Id}}">{{$dzongkhag->NameEn}}</option>
									@endforeach
								</select>
							</div>
						</div>
                        <div class="form-group hide" id="etool-user">
                            <label class="control-label">Etool User | Send to all<input type="checkbox" name="AllEtool" value="1"/></label>
                            <table class="table table-bordered table-condensed" id="etooluser-table">
                                <tbody>
                                    <tr>
                                        <td width="5%">
                                            <button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
                                        </td>
                                        <td>
                                            <div class="ui-widget">
                                                <input type="hidden" class="etooluser-id" name="SysUserId[]" disabled="disabled"/>
                                                <input type="text" class="form-control input-sm etooluser-autocomplete"/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="notremovefornew">
                                        <td>
                                            <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group hide" id="cinet-user">
                            <label class="control-label">Cinet User | Send to all<input type="checkbox" name="AllCinet" value="1"/> </label>
                            <table class="table table-bordered table-condensed" id="cinetuser-table">
                                <tbody>
                                <tr>
                                    <td width="5%">
                                        <button type="button" class="deletetablerow"><i class="fa fa-times fa-lg"></i></button>
                                    </td>
                                    <td>
                                        <div class="ui-widget">
                                            <input type="hidden" class="cinetuser-id" name="SysUserId[]" disabled="disabled"/>
                                            <input type="text" class="form-control input-sm cinetuser-autocomplete"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="notremovefornew">
                                    <td>
                                        <button type="button" class="addtablerow"><i class="fa fa-plus fa-lg"></i></button>
                                    </td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
						<div class="hide" id="single-user-email">
							<div class="form-group">
								<label for="Date">Address/No (Separate using commas)</label>
								<div class="input-icon right">
									<textarea name="AddressNo" id="" cols="30" rows="6" class="form-control"
											  placeholder="Address/Mobile No."></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="Date">Name</label>
								<div class="input-icon right">
									<input type="text" name="Name" value="" class="form-control" placeholder="Name">
								</div>
							</div>
						</div>

						<div class="form-actions">
							<div class="btn-set">
								<button type="submit" class="btn green">Save</button>
								<a href="{{Request::url()}}" class="btn red">Cancel</a>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<label>Message</label>
							<textarea name="Message" class="form-control required" rows="10">{{Input::old('Message')}}</textarea>
						</div>
					</div>
				</div>		
			</div>
		{{Form::close()}}
	</div>
</div>
@stop