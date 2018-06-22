<a href="{{ $post->url ?: $post->company->website }}" class="btn btn-primary">
    {{ __('front.jump to apply') }}
</a>

@auth
    @if($apply = auth()->user()->isApplied($post->id))
        <div class="text-muted">*{{ __('front.applied already', ['time' => $apply->created_at->diffForHumans()]) }}</div>
    @elseif($post->applyTimes() >= cache('job_applies_a_day'))
        <div class="text-muted">*{{ __('front.job full today') }}</div>
    @elseif(auth()->user()->apply_count >= cache('apply_times_a_day'))
        <div class="text-muted">*{{ __('front.applied enough', ['times' => cache('apply_times_a_day')]) }}</div>
    @else
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal">
            {{ __('front.apply') }}
        </button>
        <div class="text-muted">*{{ __('front.job apply times left', ['times' => cache('job_applies_a_day') - $post->applyTimes()]) }}</div>
    @endif
@else
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal">
        {{ __('front.apply') }}
    </button>
    <div class="text-muted">*{{ __('front.job apply times left', ['times' => cache('job_applies_a_day') - $post->applyTimes()]) }}</div>
@endauth