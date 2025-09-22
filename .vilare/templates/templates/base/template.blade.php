@extends('index')

@section('content')
    <main
        class="template-base -wrapper"
        data-template="base"
    >
        {!! the_content() !!}
    </main>
@endsection
