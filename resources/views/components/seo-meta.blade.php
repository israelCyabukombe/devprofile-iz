{{-- Search and link-preview tags for <head>. HomeController::buildSeoTags() prepares the values. --}}
@props(['seo'])

<meta name="description" content="{{ $seo['description'] }}">
<link rel="canonical" href="{{ $seo['canonicalUrl'] }}">

{{-- Open Graph: the title, text and image LinkedIn, Slack and iMessage show when the link is shared. --}}
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $seo['title'] }}">
<meta property="og:title" content="{{ $seo['title'] }}">
<meta property="og:description" content="{{ $seo['description'] }}">
<meta property="og:url" content="{{ $seo['canonicalUrl'] }}">
@if ($seo['imageUrl'])
    <meta property="og:image" content="{{ $seo['imageUrl'] }}">
    <meta property="og:image:alt" content="{{ $seo['imageAlt'] }}">
@endif

{{-- X / Twitter reads the og: tags above; it only needs the card type. --}}
<meta name="twitter:card" content="summary">

@if ($seo['structuredData'])
    <script type="application/ld+json">@json($seo['structuredData'])</script>
@endif
