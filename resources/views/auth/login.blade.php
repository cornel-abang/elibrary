@extends('layouts.app')

@section('content')
    <section id="login">
        <h1>Login</h1>
        <form id="login-form">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <div id="login-errors"></div>
    </section>
@endsection
