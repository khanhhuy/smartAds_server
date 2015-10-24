<fieldset>
    <legend>Targeted Ads Info</legend>
    <div class="form-group">
        {!! Form::label('itemsID','Items',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-7 col-lg-6">
            {!! Form::select('itemsID[]',$items,null,['id'=>'itemsID','class'=>'form-control','multiple','required'=>'required','data-placeholder'=>' e.g. Tide Downy Washing Powder 4.5kg']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('is_whole_system','Apply on whole system?',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 ">
            <div class="checkbox-inline">
                {!! Form::checkbox('is_whole_system',1,true) !!}
            </div>
        </div>
    </div>
    <div class="form-group" id="target-group">
        {!! Form::label('targetsID','Target Stores/Areas',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-7 col-lg-6">
            {!! Form::select('targetsID[]',$targets,null,['id'=>'targetsID',
            'class'=>'form-control','multiple','data-placeholder'=>' e.g. Tp. Hồ Chí Minh + Co.opmart Bình Dương']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('start_date','From',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-4 col-lg-3">
            {!! Form::input('date','start_date',$ads->start_date,['class'=>'form-control my-inline-control','required'=>'required']) !!}
        </div>
        <div class="col-sm-5 col-lg-6">
            {!! Form::label('end_date','To',['class'=>'control-label my-between-label','required'=>'required']) !!}
            {!! Form::input('date','end_date',$ads->end_date,['class'=>'form-control my-inline-control','required'=>'required']) !!}
            <div id="date-error" style="display: none">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                End Date must not before Start Date
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('age','Customers\' Age',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-2 col-lg-1">
            {!! Form::input('number','from_age', null, ['class'=>'form-control inline-width', 'id' => 'from_age', 'placeholder'=>'0']) !!}
        </div>
        <span class="seperator control-label">-</span>
        <div class="col-sm-2 col-lg-1">
            {!! Form::input('number','to_age', null, ['class'=>'form-control inline-width', 'id' => 'to_age', 'placeholder'=>'18']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('gender','Customers\' Gender',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-3 col-lg-2">
            {!! Form::select('gender', ['Male', 'Femal', 'Male & Female'], 2, ['class' => 'form-control', 'required'=>'required'])!!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('family','Customers\' Family Member',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-2 col-lg-1">
            {!! Form::input('number','from_member', null, ['class'=>'form-control inline-width', 'id' => 'from_member', 'placeholder'=>'0']) !!}
        </div>
        <span class="seperator control-label">-</span>
        <div class="col-sm-2 col-lg-1">
            {!! Form::input('number','to_member', null, ['class'=>'form-control inline-width', 'id' => 'to_member', 'placeholder'=>'2']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('jobsDesc','Customers\' Jobs',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-6 col-lg-6 form-horizontal">
                @foreach ($jobs as $job)
                    <div class="jobs">
                        <label class="jobs-label">
                            {!! Form::checkbox('job-'.$job["id"], $job["name"], false, ['class' => 'jobs-input', 'id' => 'job-'.$job["id"]]) !!}
                            <span>{{ $job["name"] }}</span>
                        </label>
                    </div>
                @endforeach
        </div>
    </div>
</fieldset>
<br/>
@include('ads.partials.mobile-display-form')
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        <input type="submit" class="btn btn-primary" value="{{$btnSubmitName}}"/>
    </div>
</div>