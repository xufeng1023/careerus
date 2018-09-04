@extends('layouts.dashboard')

@section('subcontent')
<div class="container">
    <div class="my-3 p-3 bg-white rounded box-shadow">
        @forelse(auth()->user()->favoriteJobs as $post)
            <div class="media text-muted pt-3">
                <img alt="32x32" class="mr-2 rounded" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2232%22%20height%3D%2232%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_162da4f609b%20text%20%7B%20fill%3A%23007bff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A2pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_162da4f609b%22%3E%3Crect%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22%23007bff%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2211.546875%22%20y%3D%2216.9%22%3E32x32%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
                <div class="media-body pb-3 mb-0 small border-bottom border-gray d-flex justify-content-between align-items-end">
                    <div>
                        <h6 class="mb-0">
                            <strong class="d-block text-gray-dark">{{ $post->chinese_title ?: $post->title }}</strong>
                        </h6>
                        <div>{{ __('front.posted on', ['time' => $post->created_at->diffforhumans()]) }} - {{ $post->company->name }} - {{ $post->location }}</div>
                        <div>{{ strip_tags($post->chinese_description ?: $post->description) }}</div>
                    </div>
                    <div>
                        <span>{{ __('front.favorited on', ['time' => $post->pivot->created_at->diffforhumans()]) }}</span>
                        <form class="text-right" action="/job/favorite/toggle/{{ $post->id }}" method="post" onsubmit="cancelFavorite(event)">
                            <button type="submit" class="btn btn-link btn-sm p-0">{{ __('front.favorite cancel') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
        {{ __('front.favorites none') }}
        @endforelse
    </div>
</div>
@endsection

@section('script')
<script>
    function cancelFavorite(e) {
        e.preventDefault();
        $.post(e.target.getAttribute('action'), [], function() {
            location.reload();
        });
    }
</script>
@endsection
