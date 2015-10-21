@extends('admin-master')

@section('title','Majors - Stores Management')

@section('head-footer')
    <link rel="stylesheet" type="text/css" href="{{asset('/datatables/datatables.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('/css/manage.css')}}"/>
@endsection

@section('content')
    <br/>
    <div class="row" id="manage-ads">
        <div class="col-sm-8">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table id="manage-table" class="table table-striped table-bordered table-hover" cellspacing="0"
                           width="100%">
                        <thead>
                        <tr>
                            <th id="select-all-chkbox"></th>
                            <th>Store</th>
                            <th>Area</th>
                            <th>Major</th>
                            <th>Updated At</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="panel panel-default">
                <div class="panel-body">
                    @include('errors.list')
                    {!! Form::open(['route'=> 'majors.store','class'=>'form-horizontal promotion-form']) !!}
                    <div class="form-group">
                        {!! Form::label('store','Store',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::select('store',$stores,null,['id'=>'store',
                            'class'=>'form-control','data-placeholder'=>' e.g. Co.opmart Bình Dương','required'=>'required']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('minor','Minor',['class'=>'col-sm-2 control-label']) !!}
                        <div class="col-sm-10">
                            {!! Form::input('number','minor',null,['class'=>'form-control','required'=>'required',
                         'min'=>'0','step'=>'1','max'=>'65535','placeholder'=>'Range: 0 - 65535']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" class="btn btn-primary" value="Save"/>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('body-footer')
    <script>
        var myTableURL = "{{url('/majors/table')}}";
        var myOrder = [[4, 'desc']];
        var myDeleteURL='{{route('majors.deleteMulti')}}';
        var myColumns = [
            {
                data: 0,
            },
            {
                data: 1,
            },
            {
                data: 2,
            },
            {
                data: 3,
            }
        ];
    </script>

    @include('partials.manage-footer-script')
@endsection