<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public $user;

    public function __construct(User $user)
    {
        // IoCコンテナ new User()　とせずに、Userを使う方法
        $this->user = $user;
    }

    /**
     * @return view
     */
    public function showLogin()
    {
        return view('login.login_form');
    }

    /**
     * 認証はスターターキットを使わずに、Laravelの認証クラスを用いる
     *
     * @param App\Http\Requests\LoginFormRequest $request
     */
    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // アカウントがロックされていたら弾く
        $user = $this->user->getUserByEmail($credentials["email"]);

        if (!is_null($user)) {
            if ($this->user->isAccountLocked($user)) {
                // 前のページへ戻す。withErrorsでエラーをsessionで返す
                return back()->withErrors([
                    'login_error' => 'アカウントがロックされています。'
                ]);
            }

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                // ログインが成功したら、エラーカウントを0にする
                $this->user->resetErrorCount($user);

                // with で一時的なセッションを返している。リロードすると消える。フラッシュメッセージ
                return redirect()->route('home')->with([
                    'login_success' => 'ログイン成功しました！'
                ]);
            }
        }

        // ログイン失敗したら、エラーカウントを1増やす
        $user->error_count = $this->user->addErrorCount($user->error_count);

        // エラーカウントが6以上の時はロックフラグをオンにする
        if ($this->user->lockAccount($user)) {
            // 前のページへ戻す。withErrorsでエラーをsessionで返す
            return back()->withErrors([
                'login_error' => 'アカウントがロックされました。'
            ]);
        }

        $user->save();

        // 前のページへ戻す。withErrorsでエラーをsessionで返す
        return back()->withErrors([
            'login_error' => 'メールアドレスかパスワードが間違っています。'
        ]);
    }


    /**
     * ユーザーをアプリケーションからログアウトさせる
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout(); // ユーザーのセッションを削除

        $request->session()->invalidate();  // 全セッション削除

        $request->session()->regenerateToken();  // セッション再生成

        // with で一時的なセッションを返している。リロードすると消える。フラッシュメッセージ
        return redirect()->route('login.show')->with("logout", "ログアウトしました");
    }
}
