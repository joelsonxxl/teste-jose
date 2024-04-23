@props(['action', 'post' => null, 'put' => null, 'delete' => null, 'patch' => null, 'get' => null])


<form action="{{ $action }}" method="POST" {{ $attributes }}>
    @csrf
    @if ($put)
        @method('PUT')
    @endif
    @if ($patch)
        @method('PATCH')
    @endif
    @if ($delete)
        @method('DELETE')
    @endif
    @if($get)
        @method('GET')
    @endif
    {{ $slot }}
</form>
