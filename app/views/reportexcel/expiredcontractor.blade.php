<th>List of Expired Contractors</th>
		<table class="table table-bordered table-striped table-condensed flip-content" id="contractorhumanresource">
			<thead class="flip-content">
            <tr>
                    <th>
                        Sl.No.
                    </th>
					<th class="order">
						 CDB No.
					</th>
					<th>
						Firm/Name
					</th>
					<th class="">
						 Proprietor Name
					</th>
					<th class="">
						 Gender
					</th>
					<th class="">
						 Address
					</th>
					<th class="">
						 Country
					</th>
					<th>
						Dzongkhag
					</th>
					<th>
						Tel.No.
					</th>
					
					<th class="">
						 Mobile No.
					</th>
					<th class="">
						W1
					</th>
                    <th class="">
                        W2
                    </th>
                    <th class="">
                        W3
                    </th>
                    <th class="">
                        W4
                    </th>
                    <th>
                        Expiry Date
                    </th>
                    <th>Status</th>

               

				</tr>
			</thead>
			<tbody>
            @forelse($contractorList as $contractor )
            <tr>
                    <td>{{$start++}}</td>
                    <td>{{$contractor->CDBNo}}</td>
                    <td>{{$contractor->NameOfFirm}}</td>
                    <td>{{$contractor->ownerName}}</td>
                    <td>{{$contractor->gender}}</td>
                    <td>{{$contractor->Address}}</td>
                    <td>{{$contractor->Country}}</td>
                    <td>{{$contractor->Dzongkhag}}</td>
                    <td>{{$contractor->TelephoneNo}}</td>
                    <td>{{$contractor->MobileNo}}</td>
                    <td>{{$contractor->Classification1}}</td>
                    <td>{{$contractor->Classification2}}</td>
                    <td>{{$contractor->Classification3}}</td>
                    <td>{{$contractor->Classification4}}</td>
                    <td>{{convertDateTimeToClientFormat($contractor->ExpiryDate)}}</td>
                  

                    							<td >
					
							@if($contractorList [0]->ExpiryDate<=date('Y-m-d G:i:s'))
							<p class="font-red bold warning">Expired</p>
							@else
							{{convertDateToClientFormat($contractorList [0]->ExpiryDate)}}
							@endif
					
					</td>
						

				</tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center font-red">No data to display!</td>
                </tr>
            @endforelse
			</tbody>
		</table>

	</div>
</div>
