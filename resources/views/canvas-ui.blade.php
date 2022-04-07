<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>小梁天地</title>

    <link rel="stylesheet" type="text/css" href="{{ mix('css/canvas-ui.css') }}">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="//fonts.googleapis.com/css2?family=Karla&family=Merriweather:wght@400;700&display=swap">

    <link rel="stylesheet" href="//cdn.jsdelivr.net/gh/highlightjs/cdn-release@10.1.2/build/styles/github.min.css">
</head>
<body class="mb-5">

<div id="ui">
    <router-view></router-view>
</div>

<script>
    window.CanvasUI = @json($config);
</script>

<script type="text/javascript" src="{{ mix('js/canvas-ui.js') }}"></script>
<div style="width: 100%;position: fixed;z-index: 302;bottom: 5px;padding-top: 1px;overflow: hidden;zoom: 1;margin: 0;background: #fff;text-align: center">
<a class="text-color" href="https://beian.miit.gov.cn" target="_blank">粤ICP备2022033864号</a>
    &nbsp;
    &nbsp;
<a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=44180302000147" style="display:inline-block;text-decoration:none;height:20px;line-height:20px;"><img src="/beian.png" style="float:left;"/><p style="float:left;height:20px;line-height:20px;margin: 0px 0px 0px 5px; color:#939393;">粤公网安备 44180302000147号</p></a>
</div>
</body>
</html>

