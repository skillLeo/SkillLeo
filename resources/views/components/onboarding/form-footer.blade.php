@props([
    'backUrl' => null,
    'backLabel' => 'Back',
    'nextLabel' => 'Continue',
    'nextDisabled' => false,
    'skipUrl' => null
])

<div class="form-footer">
    @if($backUrl)
        <a href="{{ $backUrl }}" class="btn btn-secondary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                <path d="M19 12H5M12 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            {{ $backLabel }}
        </a>
    @else
        <div></div>
    @endif

    @if($skipUrl)
        <a href="{{ $skipUrl }}" class="skip-center">
            Skip for now
        </a>
    @endif

    <button 
        type="submit" 
        class="btn btn-primary" 
        id="continueBtn"
        @if($nextDisabled) disabled @endif
        {{ $attributes }}
    >
        <span id="btnText">{{ $nextLabel }}</span>
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
            <path d="M5 12h14M12 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
</div>