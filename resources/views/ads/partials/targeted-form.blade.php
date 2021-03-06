<fieldset>
    <legend>Targeted Ads Info</legend>
    @yield('id-group')
    <div class="form-group">
        {!! Form::label('is_whole_system','Apply on whole system?',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 ">
            <div class="checkbox-inline">
                {!! Form::checkbox('is_whole_system',1, true) !!}
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
        <div class="col-sm-2 col-lg-2">
            {!! Form::input('number','from_age', $rule->from_age, ['class'=>'form-control inline-width', 'id' => 'from_age','min'=>0]) !!}
        </div>
        <span class="seperator control-label">to</span>
        <div class="col-sm-2 col-lg-2">
            {!! Form::input('number','to_age', $rule->to_age, ['class'=>'form-control inline-width', 'id' => 'to_age','min'=>0]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('gender','Customers\' Gender',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-2 col-lg-2">
            {!! Form::select('gender', ['Female', 'Male', 'All'], $rule->gender, ['class' => 'form-control initial-width-control', 'required'=>'required'])!!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('family','Customers\' Family Member',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-2 col-lg-2">
            {!! Form::input('number','from_family_members', $rule->from_family_members, ['class'=>'form-control inline-width','min'=>0]) !!}
        </div>
        <span class="seperator control-label">to</span>
        <div class="col-sm-2 col-lg-2">
            {!! Form::input('number','to_family_members', $rule->to_family_members, ['class'=>'form-control inline-width','min'=>0]) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('jobsDesc','Customers\' Jobs',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-6 col-lg-6 form-horizontal">
                @foreach ($jobs as $job)
                    <div class="jobs">
                        <label class="jobs-label">
                            <?php 
                                $checked = true;
                                if ($rule->jobs_desc != null) {
                                    if (!in_array($job["id"], $rule->jobs_desc))
                                        $checked = false;
                                }
                            ?>
                            {!! Form::checkbox('jobs_desc[]', $job["id"], $checked, ['class' => 'jobs-input']) !!}
                            <span>{{ $job["name"] }}</span>
                        </label>
                    </div>
                @endforeach
        </div>
    </div>
</fieldset>
<br/>
@include('ads.partials.mobile-display-form')
<hr/>