<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ログインフォーム</title>
    {{-- assetでpublicディレクトリ配下という意味になる --}}
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/signin.css') }}" rel="stylesheet">
</head>

<body>
    <form class="form-signin" method="post" action="{{ route('login') }}">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">ログインフォーム</h1>
        @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        {{-- コンポーネント化する前 --}}
        {{-- @if (session('login_success'))
            <ul>
                <li class="alert alert-danger">
                    {{ session('login_success') }}
                </li>
            </ul>
        @endif

        @if (session('logout'))
            <ul>
                <li class="alert alert-success">
                    {{ session('logout') }}
                </li>
            </ul>
        @endif --}}

        {{-- コンポーネント呼び出し app/View/Components/Alert.php のコンストラクタへ値を渡している。 --}}
        <x-alert type="danger" :session="session('login_success')" />
        <x-alert type="success" :session="session('logout')" />


        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Email address" autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password">
        <button class="btn btn-lg btn-primary btn-block" type="submit">ログイン</button>
    </form>

</body>

</html>
