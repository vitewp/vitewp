@extends('base')

@section('header')
    
@endsection

@section('content')
    <main
        class="template-playground"
        data-template="playground"
    >
        {{ the_content() }}
    </main>
@endsection

@section('footer')
    
@endsection
