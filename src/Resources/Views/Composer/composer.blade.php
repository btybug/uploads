@extends('btybug::layouts.mTabs',['index'=>'upload_market'])
<!-- Nav tabs -->
@section('tab')

    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <h1>Avatar Composer</h1>
            <hr/>
            <h3>Commands:</h3>
            <div class="form-inline">
                <input type="text" id="path" style="width:300px;" class="form-control disabled"
                       placeholder="" readonly value="{!! base_path($path)!!}"/>
                <button id="install" onclick="call('install')" class="btn btn-success disabled">install</button>
                <button id="update" onclick="call('update')" class="btn btn-success disabled">update</button>
                <button id="dump-autoload" onclick="call('dump-autoload')" class="btn btn-success disabled">
                    dump-autoload
                </button>
            </div>
            <div class="form-inline">
                <br/><br/>
                <input type="text" id="package" style="width:300px;" class="form-control disabled"
                       placeholder="sahak.avatar/provaldation:dev-master" value="{!! $plugin !!}"/>
                <button id="require" onclick="call('require')" class="btn btn-success disabled">Require plugin</button>
                <button id="remove" onclick="call('remove')" class="btn btn-success disabled">Delete plugin</button>

            </div>
            <h3>Console Output:</h3>
            <pre id="output" class="well"></pre>
        </div>

        <div class="col-lg-1"></div>
    </div>
    <div class="bookshelf_wrapper hidden">
        <ul class="books_list">
            <li class="book_item first"></li>
            <li class="book_item second"></li>
            <li class="book_item third"></li>
            <li class="book_item fourth"></li>
            <li class="book_item fifth"></li>
            <li class="book_item sixth"></li>
        </ul>
        <div class="shelf"></div>
    </div>
@stop
@section('CSS')
    <style>
        #output {
            width: 100%;
            height: 350px;
            overflow-y: scroll;
            background: black;
            color: darkturquoise;
            font-family: monospace;
        }
    </style>
    <style>
        body {
            margin: 0;
        }

        .bookshelf_wrapper {
            position: relative;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .books_list {
            margin: 0 auto;
            width: 300px;
            padding: 0;
        }

        .book_item {
            position: absolute;
            top: -120px;
            box-sizing: border-box;
            list-style: none;
            width: 40px;
            height: 120px;
            opacity: 0;
            background-color: #f9f6ff;
            border: 5px solid #4948fa;
            transform-origin: bottom left;
            transform: translateX(300px);
            animation: travel 2500ms linear infinite;
        }

        .book_item.first {
            top: -140px;
            height: 140px;
        }

        .book_item.first:before, .book_item.first:after {
            content: '';
            position: absolute;
            top: 10px;
            left: 0;
            width: 100%;
            height: 5px;
            background-color: #4948fa;
        }

        .book_item.first:after {
            top: initial;
            bottom: 10px;
        }

        .book_item.second:before, .book_item.second:after, .book_item.fifth:before, .book_item.fifth:after {
            box-sizing: border-box;
            content: '';
            position: absolute;
            top: 10px;
            left: 0;
            width: 100%;
            height: 17.5px;
            border-top: 5px solid #4948fa;
            border-bottom: 5px solid #4948fa;
        }

        .book_item.second:after, .book_item.fifth:after {
            top: initial;
            bottom: 10px;
        }

        .book_item.third:before, .book_item.third:after {
            box-sizing: border-box;
            content: '';
            position: absolute;
            top: 10px;
            left: 9px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 5px solid #4948fa;
        }

        .book_item.third:after {
            top: initial;
            bottom: 10px;
        }

        .book_item.fourth {
            top: -130px;
            height: 130px;
        }

        .book_item.fourth:before {
            box-sizing: border-box;
            content: '';
            position: absolute;
            top: 46px;
            left: 0;
            width: 100%;
            height: 17.5px;
            border-top: 5px solid #4948fa;
            border-bottom: 5px solid #4948fa;
        }

        .book_item.fifth {
            top: -100px;
            height: 100px;
        }

        .book_item.sixth {
            top: -140px;
            height: 140px;
        }

        .book_item.sixth:before {
            box-sizing: border-box;
            content: '';
            position: absolute;
            bottom: 31px;
            left: 0px;
            width: 100%;
            height: 5px;
            background-color: #4948fa;
        }

        .book_item.sixth:after {
            box-sizing: border-box;
            content: '';
            position: absolute;
            bottom: 10px;
            left: 9px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 5px solid #4948fa;
        }

        .book_item:nth-child(2) {
            animation-delay: 416.66667ms;
        }

        .book_item:nth-child(3) {
            animation-delay: 833.33333ms;
        }

        .book_item:nth-child(4) {
            animation-delay: 1250ms;
        }

        .book_item:nth-child(5) {
            animation-delay: 1666.66667ms;
        }

        .book_item:nth-child(6) {
            animation-delay: 2083.33333ms;
        }

        .shelf {
            width: 300px;
            height: 5px;
            margin: 0 auto;
            background-color: #4948fa;
            position: relative;
        }

        .shelf:before, .shelf:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: #1e6cc7;
            background-image: radial-gradient(rgba(255, 255, 255, 0.5) 30%, transparent 0);
            background-size: 10px 10px;
            background-position: 0 -2.5px;
            top: 200%;
            left: 5%;
            animation: move 250ms linear infinite;
        }

        .shelf:after {
            top: 400%;
            left: 7.5%;
        }

        @keyframes move {
            from {
                background-position-x: 0;
            }

            to {
                background-position-x: 10px;
            }
        }

        @keyframes travel {
            0% {
                opacity: 0;
                transform: translateX(300px) rotateZ(0deg) scaleY(1);
            }

            6.5% {
                transform: translateX(279.5px) rotateZ(0deg) scaleY(1.1);
            }

            8.8% {
                transform: translateX(273.6px) rotateZ(0deg) scaleY(1);
            }

            10% {
                opacity: 1;
                transform: translateX(270px) rotateZ(0deg);
            }

            17.6% {
                transform: translateX(247.2px) rotateZ(-30deg);
            }

            45% {
                transform: translateX(165px) rotateZ(-30deg);
            }

            49.5% {
                transform: translateX(151.5px) rotateZ(-45deg);
            }

            61.5% {
                transform: translateX(115.5px) rotateZ(-45deg);
            }

            67% {
                transform: translateX(99px) rotateZ(-60deg);
            }

            76% {
                transform: translateX(72px) rotateZ(-60deg);
            }

            83.5% {
                opacity: 1;
                transform: translateX(49.5px) rotateZ(-90deg);
            }

            90% {
                opacity: 0;
            }

            100% {
                opacity: 0;
                transform: translateX(0px) rotateZ(-90deg);
            }
        }
    </style>
@stop
@section('JS')
    <script type="text/javascript">
        $(document).ready(function () {
            check();
        });

        function url() {
            return '{!! route('composer_main') !!}';
        }

        function call(func) {
            $(".bookshelf_wrapper").removeClass("hidden");
            $("#output").append("\nplease wait...\n");
            $("#output").append("\n===================================================================\n");
            $("#output").append("Executing Started");
            $("#output").append("\n===================================================================\n");
            $.post('{!! route('composer_main') !!}',
                {
                    "path": $("#path").val(),
                    "package": $("#package").val(),
                    "command": func,
                    "function": "command",
                    "_token": "<?php echo csrf_token()?>"

                },
                function (data) {
                    $("#output").append(data);
                    $("#output").append("\n===================================================================\n");
                    $("#output").append("Execution Ended");
                    $("#output").append("\n===================================================================\n");
                    $(".bookshelf_wrapper").addClass("hidden");

                }
            );
        }

        function check() {
            $("#output").append('\nloading...\n');
            $.post(url(),
                {
                    "function": "getStatus",
                    "password": $("#password").val(),
                    "_token": "<?php echo csrf_token()?>"
                },
                function (data) {
                    if (data.composer_extracted) {
                        $("#output").html("Ready. All commands are available.\n");
                        $("button").removeClass('disabled');
                    }
                    else if (data.composer) {
                        $.post(url(),
                            {
                                "password": $("#password").val(),
                                "function": "extractComposer",
                                "_token": "<?php echo csrf_token()?>"
                            },
                            function (data) {
                                $("#output").append(data);
                                window.location.reload();
                            }, 'text');
                    }
                    else {
                        $("#output").html("Please wait till composer is being installed...\n");
                        $.post(url(),
                            {
                                "password": $("#password").val(),
                                "function": "downloadComposer",
                                "_token": "<?php echo csrf_token()?>"
                            },
                            function (data) {
                                $("#output").append(data);
//                                check();
                            }, 'text');
                    }
                });
        }
    </script>
@stop