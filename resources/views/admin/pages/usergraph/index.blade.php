@extends('admin.layouts.app')
@section('title')
Admin Dashboard |
@endsection
@section('content')

<div class="col-lg-6">
    <div class="panel panel-border panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Bar Chart </h3>
        </div>
        <div class="panel-body">
            <div id="morris-bar-example" style="height: 300px; position: relative;"><svg height="300" version="1.1"
                    width="485" xmlns="http://www.w3.org/2000/svg" style="overflow: hidden; position: relative;">
                    <desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with RaphaĂŤl 2.1.0</desc>
                    <defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><text x="32.84765625" y="261"
                        text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0</tspan>
                    </text>
                    <path fill="none" stroke="#aaaaaa" d="M45.34765625,261H460" stroke-width="0.5"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="32.84765625" y="202"
                        text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">25</tspan>
                    </text>
                    <path fill="none" stroke="#aaaaaa" d="M45.34765625,202H460" stroke-width="0.5"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="32.84765625" y="143"
                        text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">50</tspan>
                    </text>
                    <path fill="none" stroke="#aaaaaa" d="M45.34765625,143H460" stroke-width="0.5"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="32.84765625" y="84"
                        text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">75</tspan>
                    </text>
                    <path fill="none" stroke="#aaaaaa" d="M45.34765625,84H460" stroke-width="0.5"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="32.84765625" y="25"
                        text-anchor="end" font="10px &quot;Arial&quot;" stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">100</tspan>
                    </text>
                    <path fill="none" stroke="#aaaaaa" d="M45.34765625,25H460" stroke-width="0.5"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="430.38197544642856"
                        y="273.5" text-anchor="middle" font="10px &quot;Arial&quot;" stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2015</tspan>
                    </text><text x="311.90987723214283" y="273.5" text-anchor="middle" font="10px &quot;Arial&quot;"
                        stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal"
                        transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2013</tspan>
                    </text><text x="193.43777901785714" y="273.5" text-anchor="middle" font="10px &quot;Arial&quot;"
                        stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal"
                        transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2011</tspan>
                    </text><text x="74.96568080357143" y="273.5" text-anchor="middle" font="10px &quot;Arial&quot;"
                        stroke="none" fill="#888888"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font: 12px sans-serif;"
                        font-size="12px" font-family="sans-serif" font-weight="normal"
                        transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2009</tspan>
                    </text>
                    <rect x="52.752162388392854" y="25" width="20.71351841517857" height="236" r="0" rx="0"
                        ry="0" fill="#317eeb" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="76.46568080357142" y="48.60000000000002" width="20.71351841517857"
                        height="212.39999999999998" r="0" rx="0" ry="0" fill="#bcbcbc"
                        stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="111.98821149553572" y="84" width="20.71351841517857" height="177" r="0" rx="0"
                        ry="0" fill="#317eeb" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="135.70172991071428" y="107.6" width="20.71351841517857" height="153.4" r="0"
                        rx="0" ry="0" fill="#bcbcbc" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="171.22426060267858" y="143" width="20.71351841517857" height="118" r="0"
                        rx="0" ry="0" fill="#317eeb" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="194.93777901785717" y="166.60000000000002" width="20.71351841517857"
                        height="94.39999999999998" r="0" rx="0" ry="0" fill="#bcbcbc"
                        stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="230.46030970982142" y="84" width="20.71351841517857" height="177" r="0" rx="0"
                        ry="0" fill="#317eeb" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="254.173828125" y="107.6" width="20.71351841517857" height="153.4" r="0" rx="0"
                        ry="0" fill="#bcbcbc" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="289.6963588169643" y="143" width="20.71351841517857" height="118" r="0" rx="0"
                        ry="0" fill="#317eeb" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="313.40987723214283" y="166.60000000000002" width="20.71351841517857"
                        height="94.39999999999998" r="0" rx="0" ry="0" fill="#bcbcbc"
                        stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="348.9324079241071" y="84" width="20.71351841517857" height="177" r="0" rx="0"
                        ry="0" fill="#317eeb" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="372.64592633928567" y="107.6" width="20.71351841517857" height="153.4" r="0"
                        rx="0" ry="0" fill="#bcbcbc" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="408.16845703124994" y="25" width="20.71351841517857" height="236" r="0" rx="0"
                        ry="0" fill="#317eeb" stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="431.8819754464285" y="48.60000000000002" width="20.71351841517857"
                        height="212.39999999999998" r="0" rx="0" ry="0" fill="#bcbcbc"
                        stroke="none" fill-opacity="1"
                        style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                </svg>
                <div class="morris-hover morris-default-style" style="left: 271.41px; top: 112px;">
                    <div class="morris-hover-row-label">2013</div>
                    <div class="morris-hover-point" style="color: #317eeb">
                        Series A:
                        50
                    </div>
                    <div class="morris-hover-point" style="color: #bcbcbc">
                        Series B:
                        40
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
