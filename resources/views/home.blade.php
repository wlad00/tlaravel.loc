@extends('layouts.app')

@section('content')
<div class="container"  ng-app="appHome">

    <div id="user" data-content="{{$user}}"
         style="display: none;"></div>


    <div class="row justify-content-center"  ng-controller="AngHome">

        <div ng-if="!isUserLoggedIn" class="row">
            <div class="col-md-8">
                <input type="text" ng-model="userName" class="form-control" placeholder="Your name">
            </div>
            <div class="col-md-4 text-right">
                <button ng-click="login(userName)" class="btn btn-primary btn-block">Login</button>
            </div>
        </div>

        {{---------------------------------------------------}}

        {{--<div ng-if="isUserLoggedIn" class="row">

            <div class="col-md-4 offset-md-8">
                <button ng-click="logout()" class="btn btn-primary btn-block">Logout</button>
            </div>
            <h2>I'm @{{loggedInUser}}</h2>
        </div>--}}


        <div class="container-fluid h-100" {{--ng-if="onlineUsers"--}}>
            <div class="row justify-content-center h-100">
                <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">

                        <div class="card-body contacts_body">

                            <small {{--ng-if="newMessage != null && newMessage != toUser"--}}>
                                New message {{--@{{ newMessage}}--}}
                                {{--@{{ loggedInUser }}--}}
                            </small>
                            <ui class="contacts">

                                {{--<li class="active" ng-repeat="(key, value) in onlineUsers" --}}{{--ng-if="loggedInUser != value"--}}{{--
                                    ng-click="selectUser(value)">

                                    <div class="d-flex bd-highlight">
                                        <div class="user_info">
                                            <span>@{{ value }}</span>
                                            <p>@{{ value }} is online</p>
                                        </div>
                                    </div>
                                </li>--}}
                                <li class="active" {{--ng-if="loggedInUser != value"--}}
                                    ng-click="selectUser(null)">

                                    <div class="d-flex bd-highlight">
                                        <div class="user_info">
                                            <span>value </span>
                                            <p>value is online</p>
                                        </div>
                                    </div>
                                </li>

                            </ui>
                        </div>
                        <div class="card-footer"></div>
                    </div></div>
                <div class="col-md-8 col-xl-6 chat">




                    <div class="card" {{--ng-repeat="(key, value) in onlineUsers"--}} {{--ng-if="toUser == value"--}}>
                        <div class="card-header msg_head">
                            <div class="d-flex bd-highlight">

                                <div class="user_info">
                                    {{--<span>Chat with @{{ toUser }}</span>--}}
                                    <span>Chat with  toUser </span>

                                </div>

                            </div>

                        </div>
                        <div class="card-body msg_card_body">


                            {{--<div ng-repeat="msg in messages" >
                                <div --}}{{--ng-if="msg.to == toUser"--}}{{-- class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                        @{{ msg.message }}
                                    </div>
                                </div>
                                <div --}}{{--ng-if="msg.from == toUser"--}}{{-- class="d-flex justify-content-start mb-4">
                                    <div class="msg_cotainer">@{{ msg.message }}</div>
                                </div>
                            </div>--}}
                            <div  >
                                <div {{--ng-if="msg.to == toUser"--}} class="d-flex justify-content-end mb-4">
                                    <div class="msg_cotainer_send">
                                         msg.message
                                    </div>
                                </div>
                                <div {{--ng-if="msg.from == toUser"--}} class="d-flex justify-content-start mb-4">
                                    <div class="msg_cotainer"> msg.message </div>
                                </div>
                            </div>


                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                <div class="input-group-append">
                                    <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                                </div>
                                <textarea class="form-control type_msg" placeholder="Type your message..." ng-model="messageModel"></textarea>
                                <div class="input-group-append"
                                     ng-click="sendMsg(messageModel)">
                                    <span class="input-group-text send_btn"><i class="fas fa-location-arrow"></i>Send</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
