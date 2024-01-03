<table class="table table-bordered table-striped table-condensed flip-content">
    <thead class="">
    <tr>
        <th width="5%" class="table-checkbox"></th>
        <th width="40%">
            Category
        </th>
        <th>
            Apply for Class
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($workClassification as $classification)
        <?php $randomKey=randomString(); ?>
        <tr>
            <td>
                <input type="checkbox" class="tablerowcheckbox" value="1" @if((bool)$classification->ClassId!=null){{'checked=checked'}}@endif/>
            </td>
            <td>
                <input type="hidden" name="ContractorWorkClassificationModel[{{$randomKey}}][CrpContractorId]" value="{{$contractorId}}" class="tablerowcontrol" @if((bool)$classification->ClassId==null){{"disabled=disabled"}} @endif/>
                <input type="hidden" name="ContractorWorkClassificationModel[{{$randomKey}}][CmnProjectCategoryId]" value="{{$classification->CategoryId}}" class="tablerowcontrol" @if((bool)$classification->ClassId==null){{"disabled=disabled"}} @endif/>
                {{{$classification->Category}}}
            </td>
            <td>
                <select name="ContractorWorkClassificationModel[{{$randomKey}}][ClassId]" class="form-control input-sm input-medium tablerowcontrol" @if((bool)$classification->ClassId==null)disabled="disabled"@endif>
                    <option value="">---SELECT ONE---</option>
                    @if((int)$classification->ReferenceNo!=6002)
                        @foreach($classes as $class)
                            @if((int)$class->ReferenceNo!=4)
                                <option value="{{$class->Id}}" @if($classification->ClassId==$class->Id)selected="selected"@endif>{{$class->Name}}</option>
                            @endif
                        @endforeach
                    @else
                        @foreach($classes as $class)
                            @if((int)$class->ReferenceNo==4)
                                <option value="{{$class->Id}}" @if($classification->ClassId==$class->Id)selected="selected"@endif>{{$class->Name}}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>