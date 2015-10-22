<div class="panel-heading text-center"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    Add
</div>
<div class="panel-body">
    @include('errors.list')
    {!! Form::open(['route'=> 'majors.store','class'=>'form-horizontal promotion-form']) !!}
    @include('majors.partials.form')
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" id="my-submit-btn">
                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
