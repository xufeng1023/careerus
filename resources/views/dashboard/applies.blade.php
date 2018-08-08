@extends('layouts.dashboard')

@section('subcontent')
<div class="container">
    <div class="my-3 p-3 bg-white rounded box-shadow">
        @forelse($applies as $apply)
            <div class="media text-muted pt-3">
                <img alt="32x32" class="mr-2 rounded" src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%2232%22%20height%3D%2232%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%2032%2032%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_162da4f609b%20text%20%7B%20fill%3A%23007bff%3Bfont-weight%3Abold%3Bfont-family%3AArial%2C%20Helvetica%2C%20Open%20Sans%2C%20sans-serif%2C%20monospace%3Bfont-size%3A2pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_162da4f609b%22%3E%3Crect%20width%3D%2232%22%20height%3D%2232%22%20fill%3D%22%23007bff%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%2211.546875%22%20y%3D%2216.9%22%3E32x32%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E" data-holder-rendered="true">
                <div class="media-body pb-3 mb-0 small border-bottom border-gray">
                    <div class="float-left">
                        <a href="{{ $apply->post->link() }}">
                            <strong class="text-gray-dark">{{ $apply->post->chinese_title ?: $apply->post->title }}</strong>
                        </a>
                        <div>{{ $apply->post->company->name }} - {{ $apply->post->location }}</div>
                    </div>

                    <div class="float-right">
                        <div>{{ __('front.applied on', ['time' => $apply->created_at->diffforhumans()]) }}</div>
                        <div>
                            @if($apply->is_applied)
                                <span class="text-success">申请已发送到HR</span>
                            @else
                                <span class="text-warning">申请等待审核</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
        {{ __('front.applies none') }}
        @endforelse
    </div>
    {{ $applies->links() }}
</div>
@endsection