@extends('layout.front')


@section('content')

<h3>{{$title}}</h3>

{{Former::open_for_files($submit,'POST',array('class'=>'custom'))}}

<div class="row">

    <div class="span6">

        {{ Former::file($input_name)->name($input_name)->label('Excel file ( .xls, .xlsx )')->accept('xls','xlsx') }}

    </div>
    <div class="span6">

    </div>
</div>

<div class="row">
    <div class="span12 offset2">
        {{ Form::submit('Save',array('class'=>'btn primary'))}}
    </div>
</div>

{{Former::close()}}

@stop