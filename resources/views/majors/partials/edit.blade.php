<div class="panel-heading text-center">
    <h3 class="panel-title">
        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
        Edit
    </h3>
</div>
<div class="panel-body">
    @include('errors.list')
    {!! Form::model($major,['route'=> ['majors.update',$major->major],'method'=>'PUT','class'=>'form-horizontal promotion-form']) !!}
    @include('majors.partials.form')
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary" id="my-submit-btn">
                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save
            </button>
            <button type="button" class="btn btn-default my-cancel-edit-btn" onclick="loadCreateForm()">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancel
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
