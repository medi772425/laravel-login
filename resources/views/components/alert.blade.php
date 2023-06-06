{{-- /app/View/Components/Alert.phpのコンストラクタで定義した変数を使える --}}
@if ($session)
    <div class="alert alert-{{ $type }}">
        {{ $session }}
    </div>
@endif
