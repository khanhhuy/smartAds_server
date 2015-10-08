<fieldset>
    <legend>Promotion Info</legend>
    <div class="form-group">
        {!! Form::label('itemsID','Items',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-5">
            {!! Form::select('itemsID[]',[],null,['id'=>'itemsID','class'=>'form-control','multiple','data-placeholder'=>' e.g. Tide Downy Washing Powder 4.5kg']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('is_whole_system','Is whole system Ads?',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-9 ">
            <div class="checkbox-inline">
                {!! Form::checkbox('is_whole_system',1,null) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('targetsID','Target Stores/Areas',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-5">
            {!! Form::select('targetsID[]',$targets,null,['id'=>'targetsID','class'=>'form-control','multiple','data-placeholder'=>' e.g. Tp. Hồ Chí Minh + Co.opmart Bình Dương']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('start_date','From',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-4 col-lg-3">
            <input type="date" name="start_date" id="start_date" class="form-control my-inline-control"/>
        </div>
        <div class="col-sm-5 col-lg-6">
            {!! Form::label('end_date','To',['class'=>'control-label my-between-label']) !!}
            <input type="date" name="end_date" id="end_date" class="form-control my-inline-control"/>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('discount_value','Discount Amount',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-4 col-lg-3">
            <div class="input-group my-inline-input-group">
                {!! Form::input('number','discount_value',null,['class'=>'form-control my-inline-control', 'min'=>'0.001','step'=>'0.001','placeholder'=>'e.g. 10.50']) !!}
                <div class="input-group-addon">VND</div>
            </div>
        </div>
        <div class="col-sm-5 col-lg-6">
            {!! Form::label('','Rate',['class'=>'control-label my-between-label']) !!}
            <div class="input-group my-inline-input-group">
                {!! Form::input('number','discount_rate',null,['class'=>'form-control my-inline-control', 'min'=>'0.01','step'=>'0.01','max'=>'100','placeholder'=>'e.g. 20']) !!}
                <div class="input-group-addon">%</div>
            </div>
        </div>
    </div>
</fieldset>
<fieldset>
    <legend>Mobile Display</legend>
    <div class="form-group">
        {!! Form::label('title','Title',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-6 col-md-5">
            {!! Form::text('title',null,['class'=>'form-control','required'=>'required','minlength'=>'3','placeholder'=>'e.g. Tide Downy Washing Powder 4.5kg Discount 20%']) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('display_image','Display Type',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-xs-9">
            <label class="radio-inline">
                {!! Form::radio('display_image',1,true,['id'=>'display_image']) !!} Image
            </label>
            <label class="radio-inline">
                {!! Form::radio('display_image',0,null) !!} Web Page
            </label>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label(null,'Image',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-8 col-md-6">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab-image-upload" aria-controls="image-upload"
                                                          role="tab"
                                                          data-toggle="tab">Upload</a></li>
                <li role="presentation"><a href="#tab-image-link" aria-controls="image-link" role="tab"
                                           data-toggle="tab">Link</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="tab-image-upload">
                    <br/>
                    {!! Form::file('image_file',['id'=>'image_file']) !!}
                </div>
                <div role="tabpanel" class="tab-pane" id="tab-image-link">
                    <br/>
                    {!! Form::url('image_url',null,['class'=>'form-control','placeholder'=>'Image URL']) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('web_url','Web Page URL',['class'=>'col-sm-3 control-label']) !!}
        <div class="col-sm-8 col-md-6">
            {!! Form::url('web_url',null,['class'=>'form-control','placeholder'=>'eg. http://example.com/ads/a100.html']) !!}
        </div>
    </div>
</fieldset>
