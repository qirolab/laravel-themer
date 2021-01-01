@extends('layouts.app')

@section('content')
<main class="sm:container sm:mx-auto sm:max-w-lg sm:mt-10">
    <div class="flex">
        <div class="w-full">

            @if (session('status') == 'verification-link-sent')
                <div class="px-3 py-4 mb-4 text-sm text-green-700 bg-green-100 border border-t-8 border-green-600 rounded"
                    role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <section class="flex flex-col break-words bg-white sm:border-1 sm:rounded-md sm:shadow-sm sm:shadow-lg">
                <header class="px-6 py-5 font-semibold text-gray-700 bg-gray-200 sm:py-6 sm:px-8 sm:rounded-t-md">
                    {{ __('Verify Your Email Address') }}
                </header>

                <div class="flex flex-wrap w-full p-6 space-y-4 text-sm leading-normal text-gray-700 sm:text-base sm:space-y-6">
                    <p>
                        {{ __('Before proceeding, please check your email for a verification link.') }}
                    </p>

                    <p>
                        {{ __('If you did not receive the email') }}, <a
                            class="text-blue-500 no-underline cursor-pointer hover:text-blue-700 hover:underline"
                            onclick="event.preventDefault(); document.getElementById('resend-verification-form').submit();">{{ __('click here to request another') }}</a>.
                    </p>

                    <form id="resend-verification-form" method="POST" action="{{ route('verification.send') }}"
                        class="hidden">
                        @csrf
                    </form>
                </div>

            </section>
        </div>
    </div>
</main>
@endsection
