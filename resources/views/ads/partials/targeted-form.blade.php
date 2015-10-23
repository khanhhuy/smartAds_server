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
            {!! Form::input('number','from_age', null, ['class'=>'form-control inline-width','required'=>'required', 'placeholder'=>'0']) !!}
        </div>
        <span class="seperator control-label">-</span>
        <div class="col-sm-2 col-lg-1">
            {!! Form::input('number','to_age', null, ['class'=>'form-control inline-width','required'=>'required', 'placeholder'=>'18']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('gender','Customers\' Gender',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-3 col-lg-2">
            {!! Form::select('gender', ['Male', 'Femal', 'Male & Female'], 2, ['class' => 'form-control'])!!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('family','Customers\' Family Member',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-2 col-lg-1">
            {!! Form::input('number','from_member', null, ['class'=>'form-control inline-width','required'=>'required', 'placeholder'=>'0']) !!}
        </div>
        <span class="seperator control-label">-</span>
        <div class="col-sm-2 col-lg-1">
            {!! Form::input('number','to_member', null, ['class'=>'form-control inline-width','required'=>'required', 'placeholder'=>'2']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('jobs','Customers\' Jobs',['class'=>'col-sm-3 col-lg-3 control-label']) !!}
        <div class="col-sm-6 col-lg-6">
                @foreach ($jobs as $job)
                    <div class="jobs">
                        {!! Form::checkbox('jobs', $job["name"], false) !!} {{ $job["name"] }}
                    </div>
                @endforeach
        </div>
    </div>
</fieldset>
<fieldset>
    <legend>Mobile Display</legend>
    <div class="form-group">
        {!! Form::label('title','Title',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-7 col-md-6">
            {!! Form::text('title',null,['class'=>'form-control','required'=>'required','minlength'=>'3','placeholder'=>'e.g. Tide Downy Washing Powder 4.5kg Discount 20%']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('image_display','Display Type',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9">
            <label class="radio-inline">
                {!! Form::radio('image_display',1,true,['id'=>'image_display']) !!} Image
            </label>
            <label class="radio-inline">
                {!! Form::radio('image_display',0,null) !!} Web Page
            </label>
        </div>
    </div>
    <div class="form-group" id="imageInputGroup">
        {!! Form::label(null,'Image',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-8 col-md-6">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-image-link" aria-controls="image-link" role="tab"
                                                          data-toggle="tab">Link</a></li>
                <li role="presentation"><a href="#tab-image-upload" aria-controls="image-upload"
                                           role="tab"
                                           data-toggle="tab">Upload</a></li>
            </ul>
            <input type="hidden" id="provide_image_link" name="provide_image_link" value="1"/>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tab-image-link">
                    <br/>
                    {!! Form::url('image_url',null,['class'=>'form-control','placeholder'=>'Image URL']) !!}
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-image-upload">
                    <br/>
                    {!! Form::file('image_file',['id'=>'image_file','accept'=>'image/*']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group myHiddenInputGroup" id="webInputGroup">
        {!! Form::label('web_url','Web Page URL',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-8 col-md-7">
            {!! Form::url('web_url',null,['class'=>'form-control','placeholder'=>'eg. http://example.com/ads/a100.html']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('auto_thumbnail','Auto generate thumbnail?',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 ">
            <div class="checkbox-inline">
                {!! Form::checkbox('auto_thumbnail',1,true) !!}
            </div>
        </div>
    </div>
    <div class="form-group " id="thumbnailInputGroup">
        {!! Form::label(null,'Thumbnail Image',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-8 col-md-6">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-thumbnail-link" aria-controls="thumbnail-link" role="tab"
                                                          data-toggle="tab">Link</a></li>
                <li role="presentation"><a href="#tab-thumbnail-upload" aria-controls="thumbnail-upload"
                                           role="tab"
                                           data-toggle="tab">Upload</a></li>
            </ul>
            <input type="hidden" id="provide_thumbnail_link" name="provide_thumbnail_link" value="1"/>
            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="tab-thumbnail-link">
                    <br/>
                    {!! Form::url('thumbnail_url',null,['class'=>'form-control','placeholder'=>'Thumbnail URL']) !!}
                </div>
                <div role="tabpanel" class="tab-pane fade" id="tab-thumbnail-upload">
                    <br/>
                    {!! Form::file('thumbnail_file',['id'=>'thumbnail_file','accept'=>'image/*']) !!}
                </div>
            </div>
        </div>
    </div>
</fieldset>
<div class="form-group">
    <div class="col-sm-offset-3 col-sm-9">
        <input type="submit" class="btn btn-primary" value="{{$btnSubmitName}}"/>
    </div>
</div>