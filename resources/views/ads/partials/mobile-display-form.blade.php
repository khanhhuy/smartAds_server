<fieldset>
    <legend>Mobile Display</legend>
    <div class="form-group">
        {!! Form::label('title','Title',['class'=>"$labelClass"]) !!}
        <div class="col-sm-7">
            {!! Form::text('title',null,['class'=>'form-control','required'=>'required','minlength'=>'3','placeholder'=>'e.g. Tide Downy Washing Powder 4.5kg Discount 20%']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('image_display','Display Type',['class'=>"$labelClass"]) !!}
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
        {!! Form::label(null,'Image',['class'=>"$labelClass"]) !!}
        <div class="{{$urlGroupClass}}">
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
        {!! Form::label('web_url','Web Page URL',['class'=>"$labelClass"]) !!}
        <div class="{{$urlGroupClass}}">
            {!! Form::url('web_url',null,['class'=>'form-control','placeholder'=>'eg. http://example.com/ads/a100.html']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('auto_thumbnail','Auto generate thumbnail?',['class'=>"$labelClass"]) !!}
        <div class="col-sm-9 ">
            <div class="checkbox-inline">
                {!! Form::checkbox('auto_thumbnail',1,true) !!}
            </div>
        </div>
    </div>
    <div class="form-group " id="thumbnailInputGroup">
        {!! Form::label(null,'Thumbnail Image',['class'=>"$labelClass"]) !!}
        <div class="{{$urlGroupClass}}">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-thumbnail-link" aria-controls="thumbnail-link"
                                                          role="tab"
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