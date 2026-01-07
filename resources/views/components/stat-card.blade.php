<div class="col-md-{{ $cols ?? '3' }}">
    <div class="card {{ $color ?? 'border-primary' }} h-100">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="text-muted small mb-1">{{ $title }}</h6>
                    <h2 class="mb-0 {{ $textColor ?? 'text-primary' }}">{{ $value }}</h2>
                </div>
                @if(isset($icon))
                    <div class="text-{{ $iconColor ?? 'primary' }}" style="font-size: 2rem; opacity: 0.3;">
                        <i class="bi bi-{{ $icon }}"></i>
                    </div>
                @endif
            </div>
            @if(isset($subtitle))
                <small class="text-muted">{{ $subtitle }}</small>
            @endif
        </div>
    </div>
</div>
