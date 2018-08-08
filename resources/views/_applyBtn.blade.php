<a href="{{ $post->url ?: $post->company->website }}" class="btn btn-primary" rel="nofollow">
    {{ __('front.jump to apply') }}
</a>

@auth
    @if(auth()->user()->confirmed)
        @if(auth()->user()->suspended)
            <div class="text-muted">*{{ __('front.bad resume msg') }}</div>
        @else
            @if($apply = auth()->user()->isApplied($post->id))
                <div class="text-muted">*{{ __('front.applied already', ['time' => $apply->created_at->diffForHumans()]) }}</div>
            @elseif($post->applyTimes() >= cache('job_applies_limit', 10))
                <div class="text-muted">*该工作申请名额已满</div>
            @elseif(auth()->user()->apply_count >= cache('apply_times_a_day'))
                <div class="text-muted">*{{ __('front.applied enough', ['times' => cache('apply_times_a_day')]) }}</div>
            @else
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal">
                    {{ __('front.apply') }}
                </button>
                <div class="text-muted">*该工作申请名额还有{{ $post->applyTimesLeft }}次。</div>
            @endif
        @endif
    @else
        <div class="text-muted">*该工作申请名额还有{{ $post->applyTimesLeft }}次。请先到邮箱验证完成注册。</div>
    @endif
@else
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal">
        {{ __('front.apply') }}
    </button>
    <div class="text-muted">*该工作申请名额还有{{ $post->applyTimesLeft }}次。</div>
@endauth