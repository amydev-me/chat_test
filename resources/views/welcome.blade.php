<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="{{URL::asset('css/chat.css')}}" />

    </head>
    <body>

    <div id="app">
        <app></app>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{asset('js/admin/manifest.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/admin/vendor.js') }}"></script>
    <script type="text/javascript" src="{{asset('js/admin/main.js') }}"></script>
    {{--<div class="container" id="app">--}}

        {{--<div class="member">--}}
            {{--<div class="member-txt">Members</div>--}}
            {{--<input type="text" v-model="user_id" placeholder="User Id" class="message-input">--}}
            {{--<button class="btn btn-default" type="button" @click="onSocket">Send</button>--}}
            {{--<input type="text" v-model="send_to" placeholder="Send message to" class="message-input">--}}
            {{--<ul class="member-item">--}}
                {{--<li class="member-item-list">--}}
                    {{--<img class="member-item-list-photo" src="{{URL::asset('images/joke.jpg')}}"><span class="member-item-list-name">huai siam</span>--}}
                {{--</li>--}}
                {{--<li class="member-item-list">--}}
                    {{--<img class="member-item-list-photo" src="{{URL::asset('images/joke.jpg')}}"><span class="member-item-list-name">huai siam</span>--}}
                {{--</li>--}}
            {{--</ul>--}}
        {{--</div>--}}
        {{--<div class="message">--}}
            {{--<div class="message-txt">Messages</div>--}}
            {{--<ul class="member-item">--}}
                {{--<li v-for="msg in messages" class="member-item-list">--}}
                    {{--<span>--}}
                        {{--<img class="message-item-list-photo" src="{{URL::to('images/kiss.png')}}">--}}
                        {{--<div class="message-item-list-txt">@{{ msg }}</div>--}}
                    {{--</span>--}}
                {{--</li>--}}
            {{--</ul>--}}
            {{--<form action="" @submit.prevent="emitData" class="message-compose">--}}
                {{--<input type="text" class="message-input"  placeholder="Message Text..." v-model="message">--}}
                {{--<button class="btn btn-default" type="submit">Send</button>--}}
            {{--</form>--}}
        {{--</div>--}}
    {{--</div>--}}
            {{--<div class="content" id="app">--}}
                {{--<ul id="messages">--}}
                    {{--<li v-for="msg in messages">--}}
                        {{--@{{msg}}--}}
                    {{--</li>--}}
                {{--</ul>--}}

                {{--<ul class="message-item">--}}
                    {{--<li class="message-item-list" v-for="msg in messages">--}}
                        {{--<span>--}}
                            {{--<img class="message-item-list-photo" src="{{URL::to('images/kiss.png')}}">--}}
                            {{--<div class="message-item-list-txt">@{{ msg }}</div>--}}
                        {{--</span>--}}
                        {{--<span>Huai Siam @ 7:30 PM</span>--}}
                    {{--</li>--}}
                {{--</ul>--}}
                {{--<div class="message-compose">--}}
                    {{--<form action="" @submit.prevent="emitData">--}}
                        {{--<input type="text" class="message-input"  placeholder="Message Text..." v-model="message">--}}
                        {{--<button class="btn btn-default" type="submit">Send</button>--}}
                    {{--</form>--}}
                {{--</div>--}}
                {{--<form action="" @submit.prevent="emitData">--}}
                    {{--<input id="m" autocomplete="off" v-model="message" /><button>Send</button>--}}
                {{--</form>--}}
            {{--</div>--}}
    {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>--}}
    {{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.17/vue.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>--}}
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>--}}
    {{--<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>--}}
    {{--<script>--}}
        {{--var socket = io('http://localhost:3000');--}}
        {{--const app=new Vue({--}}
            {{--el: '#app',--}}
            {{--data:function() {--}}
                {{--return{--}}
                    {{--message:null,--}}
                    {{--messages: [],--}}
                    {{--send_to:null,--}}
                    {{--user_id:null--}}
                {{--}--}}
            {{--},--}}
            {{--methods: {--}}
               {{--emitData() {--}}
                   {{--axios.post('/api/test', {message: this.message,send_to:this.send_to}).then((data) => {--}}
                        {{--console.log('Sending....');--}}
                   {{--});--}}
                   {{--this.message = null;--}}
               {{--},--}}
                {{--onSocket(){--}}
                    {{--socket.on("test-channel-" + this.user_id + ":App\\Events\\MessageNotify", function (data) {--}}
                        {{--this.messages.push(data.request.message);--}}
                    {{--}.bind(this));--}}
                {{--}--}}
            {{--},--}}
            {{--mounted:function() {--}}
                {{--this.onSocket();--}}
            {{--}--}}
        {{--});--}}
    {{--</script>--}}
    </body>
</html>
