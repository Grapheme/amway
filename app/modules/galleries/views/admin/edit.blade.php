@extends(Helper::acclayout())
@section('content')
    <h1>Галерея #{{ $gallery->id }}: &laquo;{{ $gallery->name }}&raquo;</h1>
   {{ ExtForm::gallery('gallery', $gallery->id) }}
@stop
@section('scripts')
    <script>
    //loadScript("{{ asset('js/modules/gallery.js') }}");
    </script>
@stop