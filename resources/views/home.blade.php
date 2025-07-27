@extends('layouts.dashboard')

@section('content')
<script>
    window.location.href = "{{ route('dashboard') }}";
</script>
@endsection
