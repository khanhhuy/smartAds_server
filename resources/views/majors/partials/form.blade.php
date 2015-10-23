<div class="form-group">
    {!! Form::label('store_id','Store',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::select('store_id',$stores,null,['id'=>'store_id',
        'class'=>'form-control','data-placeholder'=>' e.g. Co.opmart Bình Dương','required'=>'required']) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('major','Major',['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-10">
        {!! Form::input('number','major',$nextID,['class'=>'form-control','required'=>'required',
     'min'=>'0','step'=>'1','max'=>'65535','placeholder'=>'Range: 0 - 65535']) !!}
    </div>
</div>

