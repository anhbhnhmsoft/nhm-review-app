<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>{{ $title ?? 'AFY - Website review các địa điểm nổi tiếng' }}</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="@yield('meta_description', 'AFY - Website review các địa điểm nổi tiếng')">
<meta name="keywords" content="@yield('meta_keywords', 'AFY - Website review các địa điểm nổi tiếng')">
<meta name="author" content="Tên của bạn">

<!-- Open Graph -->
<meta property="og:title" content="@yield('og_title', 'Auctions Clone')">
<meta property="og:description" content="@yield('og_description', 'AFY - Website review các địa điểm nổi tiếng')">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="@yield('og_image', asset('images/default-og.jpg'))">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('twitter_title', 'AFY - Website review các địa điểm nổi tiếng')">
<meta name="twitter:description"
      content="@yield('twitter_description', 'AFY - Website review các địa điểm nổi tiếng')">
<meta name="twitter:image" content="@yield('twitter_image', asset('images/default-twitter.jpg'))">

<!-- Canonical -->
<link rel="canonical" href="{{ url()->current() }}">

@livewireStyles

@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('vite_includes')
@endif
