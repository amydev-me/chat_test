<template>
    <div id="frame">
        <div class="modal"  role="dialog" id="myModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" value="group_name" v-model="group_name" placeholder="Type Group Name">
                        <ul>
                            <li v-for="(ctc,y) in groups_contacts" :key="y">
                                <input type="checkbox" v-model="selected" :value="ctc.id">
                                {{ctc.display_name}}</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="createGroup">Create Group</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="sidepanel">
            <div id="profile">
                <div class="wrap">
                    <img id="profile-img" src="../../images/blank-avatar.png" class="online" alt="" />
                    <p>{{user_name}}</p>
                    <i class="fa fa-chevron-down expand-button" aria-hidden="true"></i>
                    <div id="status-options">
                        <ul>
                            <li id="status-online" class="active"><span class="status-circle"></span> <p>Online</p></li>
                            <li id="status-away"><span class="status-circle"></span> <p>Away</p></li>
                            <li id="status-busy"><span class="status-circle"></span> <p>Busy</p></li>
                            <li id="status-offline"><span class="status-circle"></span> <p>Offline</p></li>
                        </ul>
                    </div>
                    <div id="expanded">
                        <button name="twitter" type="button" @click="showGroupDialog">Group</button>
                        <button name="twitter" type="button" @click="logout">Logout</button>

                    </div>
                </div>
            </div>
            <div id="search">
                <label for=""><i class="fa fa-search" aria-hidden="true"></i></label>
                <input type="text" placeholder="Search contacts..." />
            </div>
            <div id="contacts">
                <ul>
                    <li class="contact" v-for="con in conversations" @click="onClickedContact(con)">
                        <div class="wrap">
                            <span class="contact-status online"></span>
                            <img src="../../images/blank-avatar.png" alt="" />
                            <div class="meta">
                                <p class="name">
                                    {{con.contact_name}}
                                </p>
                                <p class="preview">Hay!!!</p>
                            </div>
                        </div>
                    </li>
                    <li class="contact" v-for="gp in groups" @click="onClickedGroup(gp)">
                        <div class="wrap">
                            <span class="contact-status online"></span>
                            <img src="../../images/blank-avatar.png" alt="" />
                            <div class="meta">
                                <p class="name">{{gp.group_name}}</p>
                                <p class="preview">Hay!!!</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div id="bottom-bar">
                <button id="addcontact"><i class="fa fa-user-plus fa-fw" aria-hidden="true"></i> <span>Add contact</span></button>
                <button id="settings"><i class="fa fa-cog fa-fw" aria-hidden="true"></i> <span>Settings</span></button>
            </div>
        </div>
        <div class="content" v-if="receiver_id==null">
            Click member to send message
        </div>
        <div class="content" v-if="receiver_id!=null">
            <div class="contact-profile">
                <img src="../../images/blank-avatar.png" alt="" />
                <p>{{receiver_name}} <span v-if="typing_id==receiver_id">is typing..</span></p>
                <div class="social-media">
                    <i class="fa fa-facebook" aria-hidden="true"></i>
                    <i class="fa fa-twitter" aria-hidden="true"></i>
                    <i class="fa fa-instagram" aria-hidden="true"></i>
                </div>
            </div>
            <div class="messages" >
                <ul>
                    <li v-for="msg in messages">
                        <img src="../../images/wow.jpg" alt="" />
                        <p>{{msg.message}}</p>
                    </li>
                </ul>
            </div>
            <div class="message-input">
                <div class="wrap">
                    <input type="text" placeholder="Aa" v-model="message" v-on:keyup="onKeyUp"/>
                    <i class="fa fa-paperclip attachment" aria-hidden="true"></i>
                    <button class="button" @click="sendConversations"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import io from 'socket.io-client';
    // var ALICE_ADDRESS = new libsignal.SignalProtocolAddress("xxxxxxxxx", 1);
    // var BOB_ADDRESS   = new libsignal.SignalProtocolAddress("yyyyyyyyyyyyy", 1);

    export default {
        data() {
            return {
                is_group: false,
                typing_id: null,
                typing: false,
                is_typing: false,
                time_out: null,
                messages: [],
                message: null,
                user_name: null,
                user_id: null,
                contacts: [],
                receiver_id: null,
                receiver_name: null,
                socket: io('ec2-54-169-141-37.ap-southeast-1.compute.amazonaws.com:3000'),
                groups_contacts: [],
                group_name: null,
                selected: [],
                groups: [],
                conversations: [],
                channel_id: null

            };
        },
        methods: {
            getContacts() {
                axios.get('/api/get-contacts').then(({data}) => {
                    this.contacts = data;
                });
            },

            getGroups() {
                axios.get('/api/get-groups').then(({data}) => {
                    this.groups = data;
                });
            },

            loadConversations() {
                axios.get('/api/get-conversations').then(({data}) => {
                    this.conversations = data;
                    // $(".messages").animate({scrollTop: $(document).height()}, "fast");
                });

            },

            onKeyUp(e) {
                if (e.keyCode === 13) {
                    this.sendConversations();
                } else {
                    if (!this.is_typing) {
                        this.is_typing = true;
                        this.socket.emit("typing", {
                            is_typing: true,
                            receiver_id: this.receiver_id,
                            sender_id: this.user_id
                        });
                        var that = this;
                        this.time_out = setTimeout(function () {
                            that.is_typing = false;
                            // that.typing_id = null;
                            that.socket.emit("typing", {
                                is_typing: false,
                                receiver_id: that.receiver_id,
                                sender_id: this.user_id
                            });
                        }, 5000)
                    } else {
                        var that = this;
                        this.time_out = null;

                        this.time_out = setTimeout(function () {
                            that.is_typing = false;
                            // that.typing_id = null;
                            that.socket.emit("typing", {
                                is_typing: false,
                                receiver_id: that.receiver_id,
                                sender_id: this.user_id
                            });
                        }, 5000)
                    }
                }
            },

            bindClass(msg) {

                // if (this.user_id == msg.user_id) {
                //     return 'replies'
                // } else {
                //     return 'sent'
                // }
            },

            sendConversations() {
                if (this.message == '' || this.message == null) {
                    return;
                }

                axios.post('/api/send-message', {
                    message: this.message,
                    message_type: 'text',
                    conversation_id: this.receiver_id

                }).then(({data}) => {
                    // this.messages.push(data.conversation);
                });
                this.message = null;
                // $(".messages").animate({scrollTop: $(document).height()}, "fast");
            },
            onSocket() {
                this.socket.on("private-chat-channel-123456:App\\Events\\MessageNotify", function (data) {

                    this.messages.push(data);
                }.bind(this));
                // this.socket.on("private-chat-channel-" + this.receiver_id + ":App\\Events\\MessageNotify", function (data) {
                //     console.log(data)
                //     this.messages.push(data);
                // }.bind(this));
            },
            onListenTyping() {
                this.socket.on("client.typing." + this.user_id, function (data) {
                    this.typing_id = data.sender_id;
                    this.typing = data.is_typing;
                }.bind(this));
            },
            onListenUserConnected() {
                this.socket.on("broadcast", function (data) {
                    console.log(data)
                }.bind(this));
            },
            onListenGroupConversations() {
                this.socket.on("private-groups-chat-" + this.receiver_id + ":App\\Events\\GroupMessage", function (data) {
                    console.log(data)
                    this.messages.push(data);
                }.bind(this));
            },
            logout() {
                auth.logout();
            },
            showGroupDialog() {
                this.group_name = null;
                this.selected = [];
                this.groups_contacts = Array.from(Object.create(this.contacts));
                $('#myModal').modal('show');
            },

            createGroup() {
                axios.post('/api/create-group', {group_name: this.group_name, users: this.selected}).then(({data}) => {

                });
            },

            onClickedContact(contact) {
                this.is_group = false;
                this.receiver_id = contact.conversation_id;
                this.receiver_name = contact.contact_name;
                this.channel_id = contact.channel_name;
                // this.onSocket();
                this.loadConversations();
            },

            onClickedGroup(gp) {
                this.is_group = true;
                this.receiver_id = gp.id;
                this.receiver_name = gp.group_name;
                this.onListenGroupConversations();
            },

            // generateIdentity(store) {
            //     return Promise.all([
            //         KeyHelper.generateIdentityKeyPair(),
            //         KeyHelper.generateRegistrationId(),
            //     ]).then(function(result) {
            //         store.put('identityKey', result[0]);
            //         store.put('registrationId', result[1]);
            //     });
            // },

            // generatePreKeyBundle(store, preKeyId, signedPreKeyId) {
            //     return Promise.all([
            //         store.getIdentityKeyPair(),
            //         store.getLocalRegistrationId()
            //     ]).then(function(result) {
            //         var identity = result[0];
            //         var registrationId = result[1];

            //         return Promise.all([
            //             KeyHelper.generatePreKey(preKeyId),
            //             KeyHelper.generateSignedPreKey(identity, signedPreKeyId),
            //         ]).then(function(keys) {
            //             var preKey = keys[0]
            //             var signedPreKey = keys[1];

            //             store.storePreKey(preKeyId, preKey.keyPair);
            //             store.storeSignedPreKey(signedPreKeyId, signedPreKey.keyPair);

            //             return {
            //                 identityKey: identity.pubKey,
            //                 registrationId : registrationId,
            //                 preKey:  {
            //                     keyId     : preKeyId,
            //                     publicKey : preKey.keyPair.pubKey
            //                 },
            //                 signedPreKey: {
            //                     keyId     : signedPreKeyId,
            //                     publicKey : signedPreKey.keyPair.pubKey,
            //                     signature : signedPreKey.signature
            //                 }
            //             };
            //         });
            //     });
            // }
        },
        mounted() {
            $(".expand-button").click(function () {
                $("#profile").toggleClass("expanded");
                $("#contacts").toggleClass("expanded");
            });

            this.user_id = auth.getAuthInfo().id;
            this.user_name = auth.getAuthInfo().display_name;

            this.loadConversations();
            var that = this;

            this.onSocket();

            // var KeyHelper = signal.KeyHelper;
            // var registrationId = KeyHelper.generateRegistrationId();


            // var room = "abc123";
            // this.socket.on('connect', function () {
            //     // Connected, let's sign-up for to receive messages for this room
            //     that.socket.emit('room', room);
            // });

            // this.socket.on('message', function (data) {
            //     console.log('Incoming message:', data);
            // });

            // this.socket.on('broadcast', m => console.log('Received broadcast:', m));
            // this.getContacts();
            // this.getGroups();
            // this.onListen    Typing();
        },
        computed: {
            selectAll: {
                get: function () {
                    if (this.groups_contacts.length <= 0) {
                        return false;
                    }
                    return this.groups_contacts ? this.selected.length == this.groups_contacts.length : false;
                },
                set: function (value) {
                    var selected = [];
                    if (value) {
                        this.groups_contacts.forEach(function (user) {
                            selected.push(user);
                        });
                    }
                    this.selected = selected;
                }
            },
        }
    }
</script>
<style lang="css" scoped>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: #27ae60;
        font-family: "proxima-nova", "Source Sans Pro", sans-serif;
        font-size: 1em;
        letter-spacing: 0.1px;
        color: #32465a;
        text-rendering: optimizeLegibility;
        text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.004);
        -webkit-font-smoothing: antialiased;
    }

    #frame {
        width: 100%;
        min-width: 360px;
        /*max-width: 1000px;*/
        height: 100vh;
        min-height: 300px;
        /*max-height: 720px;*/
        background: #E6EAEA;
    }
    @media screen and (max-width: 360px) {
        #frame {
            width: 100%;
            height: 100vh;
        }
    }
    #frame #sidepanel {
        float: left;
        min-width: 280px;
        max-width: 340px;
        width: 40%;
        height: 100%;
        background: #2c3e50;
        color: #f5f5f5;
        overflow: hidden;
        position: relative;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel {
            width: 58px;
            min-width: 58px;
        }
    }
    #frame #sidepanel #profile {
        width: 80%;
        margin: 25px auto;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile {
            width: 100%;
            margin: 0 auto;
            padding: 5px 0 0 0;
            background: #32465a;
        }
    }
    #frame #sidepanel #profile.expanded .wrap {
        height: 210px;
        line-height: initial;
    }
    #frame #sidepanel #profile.expanded .wrap p {
        margin-top: 20px;
    }
    #frame #sidepanel #profile.expanded .wrap i.expand-button {
        -moz-transform: scaleY(-1);
        -o-transform: scaleY(-1);
        -webkit-transform: scaleY(-1);
        transform: scaleY(-1);
        filter: "FlipH";
        -ms-filter: "FlipH";
    }
    #frame #sidepanel #profile .wrap {
        height: 60px;
        line-height: 60px;
        overflow: hidden;
        -moz-transition: 0.3s height ease;
        -o-transition: 0.3s height ease;
        -webkit-transition: 0.3s height ease;
        transition: 0.3s height ease;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap {
            height: 55px;
        }
    }
    #frame #sidepanel #profile .wrap img {
        width: 50px;
        border-radius: 50%;
        padding: 3px;
        border: 2px solid #e74c3c;
        height: auto;
        float: left;
        cursor: pointer;
        -moz-transition: 0.3s border ease;
        -o-transition: 0.3s border ease;
        -webkit-transition: 0.3s border ease;
        transition: 0.3s border ease;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap img {
            width: 40px;
            margin-left: 4px;
        }
    }
    #frame #sidepanel #profile .wrap img.online {
        border: 2px solid #2ecc71;
    }
    #frame #sidepanel #profile .wrap img.away {
        border: 2px solid #f1c40f;
    }
    #frame #sidepanel #profile .wrap img.busy {
        border: 2px solid #e74c3c;
    }
    #frame #sidepanel #profile .wrap img.offline {
        border: 2px solid #95a5a6;
    }
    #frame #sidepanel #profile .wrap p {
        float: left;
        margin-left: 15px;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap p {
            display: none;
        }
    }
    #frame #sidepanel #profile .wrap i.expand-button {
        float: right;
        margin-top: 23px;
        font-size: 0.8em;
        cursor: pointer;
        color: #435f7a;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap i.expand-button {
            display: none;
        }
    }
    #frame #sidepanel #profile .wrap #status-options {
        position: absolute;
        opacity: 0;
        visibility: hidden;
        width: 150px;
        margin: 70px 0 0 0;
        border-radius: 6px;
        z-index: 99;
        line-height: initial;
        background: #435f7a;
        -moz-transition: 0.3s all ease;
        -o-transition: 0.3s all ease;
        -webkit-transition: 0.3s all ease;
        transition: 0.3s all ease;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap #status-options {
            width: 58px;
            margin-top: 57px;
        }
    }
    #frame #sidepanel #profile .wrap #status-options.active {
        opacity: 1;
        visibility: visible;
        margin: 75px 0 0 0;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap #status-options.active {
            margin-top: 62px;
        }
    }
    #frame #sidepanel #profile .wrap #status-options:before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border-left: 6px solid transparent;
        border-right: 6px solid transparent;
        border-bottom: 8px solid #435f7a;
        margin: -8px 0 0 24px;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap #status-options:before {
            margin-left: 23px;
        }
    }
    #frame #sidepanel #profile .wrap #status-options ul {
        overflow: hidden;
        border-radius: 6px;
    }
    #frame #sidepanel #profile .wrap #status-options ul li {
        padding: 15px 0 30px 18px;
        display: block;
        cursor: pointer;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap #status-options ul li {
            padding: 15px 0 35px 22px;
        }
    }
    #frame #sidepanel #profile .wrap #status-options ul li:hover {
        background: #496886;
    }
    #frame #sidepanel #profile .wrap #status-options ul li span.status-circle {
        position: absolute;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin: 5px 0 0 0;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap #status-options ul li span.status-circle {
            width: 14px;
            height: 14px;
        }
    }
    #frame #sidepanel #profile .wrap #status-options ul li span.status-circle:before {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        margin: -3px 0 0 -3px;
        background: transparent;
        border-radius: 50%;
        z-index: 0;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap #status-options ul li span.status-circle:before {
            height: 18px;
            width: 18px;
        }
    }
    #frame #sidepanel #profile .wrap #status-options ul li p {
        padding-left: 12px;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #profile .wrap #status-options ul li p {
            display: none;
        }
    }
    #frame #sidepanel #profile .wrap #status-options ul li#status-online span.status-circle {
        background: #2ecc71;
    }
    #frame #sidepanel #profile .wrap #status-options ul li#status-online.active span.status-circle:before {
        border: 1px solid #2ecc71;
    }
    #frame #sidepanel #profile .wrap #status-options ul li#status-away span.status-circle {
        background: #f1c40f;
    }
    #frame #sidepanel #profile .wrap #status-options ul li#status-away.active span.status-circle:before {
        border: 1px solid #f1c40f;
    }
    #frame #sidepanel #profile .wrap #status-options ul li#status-busy span.status-circle {
        background: #e74c3c;
    }
    #frame #sidepanel #profile .wrap #status-options ul li#status-busy.active span.status-circle:before {
        border: 1px solid #e74c3c;
    }
    #frame #sidepanel #profile .wrap #status-options ul li#status-offline span.status-circle {
        background: #95a5a6;
    }
    #frame #sidepanel #profile .wrap #status-options ul li#status-offline.active span.status-circle:before {
        border: 1px solid #95a5a6;
    }
    #frame #sidepanel #profile .wrap #expanded {
        padding: 100px 0 0 0;
        display: block;
        line-height: initial !important;
    }
    #frame #sidepanel #profile .wrap #expanded label {
        float: left;
        clear: both;
        margin: 0 8px 5px 0;
        padding: 5px 0;
    }
    #frame #sidepanel #profile .wrap #expanded input {
        border: none;
        margin-bottom: 6px;
        background: #32465a;
        border-radius: 3px;
        color: #f5f5f5;
        padding: 7px;
        width: calc(100% - 43px);
    }
    #frame #sidepanel #profile .wrap #expanded input:focus {
        outline: none;
        background: #435f7a;
    }
    #frame #sidepanel #search {
        border-top: 1px solid #32465a;
        border-bottom: 1px solid #32465a;
        font-weight: 300;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #search {
            display: none;
        }
    }
    #frame #sidepanel #search label {
        position: absolute;
        margin: 10px 0 0 20px;
    }
    #frame #sidepanel #search input {
        font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
        padding: 10px 0 10px 46px;
        width: calc(100% - 25px);
        border: none;
        background: #32465a;
        color: #f5f5f5;
    }
    #frame #sidepanel #search input:focus {
        outline: none;
        background: #435f7a;
    }
    #frame #sidepanel #search input::-webkit-input-placeholder {
        color: #f5f5f5;
    }
    #frame #sidepanel #search input::-moz-placeholder {
        color: #f5f5f5;
    }
    #frame #sidepanel #search input:-ms-input-placeholder {
        color: #f5f5f5;
    }
    #frame #sidepanel #search input:-moz-placeholder {
        color: #f5f5f5;
    }
    #frame #sidepanel #contacts {
        height: calc(100% - 177px);
        overflow-y: scroll;
        overflow-x: hidden;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #contacts {
            height: calc(100% - 149px);
            overflow-y: scroll;
            overflow-x: hidden;
        }
        #frame #sidepanel #contacts::-webkit-scrollbar {
            display: none;
        }
    }
    #frame #sidepanel #contacts.expanded {
        height: calc(100% - 334px);
    }
    #frame #sidepanel #contacts::-webkit-scrollbar {
        width: 8px;
        background: #2c3e50;
    }
    #frame #sidepanel #contacts::-webkit-scrollbar-thumb {
        background-color: #243140;
    }
    #frame #sidepanel #contacts ul li.contact {
        position: relative;
        padding: 10px 0 15px 0;
        font-size: 0.9em;
        cursor: pointer;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #contacts ul li.contact {
            padding: 6px 0 46px 8px;
        }
    }
    #frame #sidepanel #contacts ul li.contact:hover {
        background: #32465a;
    }
    #frame #sidepanel #contacts ul li.contact.active {
        background: #32465a;
        border-right: 5px solid #435f7a;
    }
    #frame #sidepanel #contacts ul li.contact.active span.contact-status {
        border: 2px solid #32465a !important;
    }
    #frame #sidepanel #contacts ul li.contact .wrap {
        width: 88%;
        margin: 0 auto;
        position: relative;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #contacts ul li.contact .wrap {
            width: 100%;
        }
    }
    #frame #sidepanel #contacts ul li.contact .wrap span {
        position: absolute;
        left: 0;
        margin: -2px 0 0 -2px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: 2px solid #2c3e50;
        background: #95a5a6;
    }
    #frame #sidepanel #contacts ul li.contact .wrap span.online {
        background: #2ecc71;
    }
    #frame #sidepanel #contacts ul li.contact .wrap span.away {
        background: #f1c40f;
    }
    #frame #sidepanel #contacts ul li.contact .wrap span.busy {
        background: #e74c3c;
    }
    #frame #sidepanel #contacts ul li.contact .wrap img {
        width: 40px;
        border-radius: 50%;
        float: left;
        margin-right: 10px;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #contacts ul li.contact .wrap img {
            margin-right: 0px;
        }
    }
    #frame #sidepanel #contacts ul li.contact .wrap .meta {
        padding: 5px 0 0 0;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #contacts ul li.contact .wrap .meta {
            display: none;
        }
    }
    #frame #sidepanel #contacts ul li.contact .wrap .meta .name {
        font-weight: 600;
    }
    #frame #sidepanel #contacts ul li.contact .wrap .meta .preview {
        margin: 5px 0 0 0;
        padding: 0 0 1px;
        font-weight: 400;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        -moz-transition: 1s all ease;
        -o-transition: 1s all ease;
        -webkit-transition: 1s all ease;
        transition: 1s all ease;
    }
    #frame #sidepanel #contacts ul li.contact .wrap .meta .preview span {
        position: initial;
        border-radius: initial;
        background: none;
        border: none;
        padding: 0 2px 0 0;
        margin: 0 0 0 1px;
        opacity: .5;
    }
    #frame #sidepanel #bottom-bar {
        position: absolute;
        width: 100%;
        bottom: 0;
    }
    #frame #sidepanel #bottom-bar button {
        float: left;
        border: none;
        width: 50%;
        padding: 10px 0;
        background: #32465a;
        color: #f5f5f5;
        cursor: pointer;
        font-size: 0.85em;
        font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #bottom-bar button {
            float: none;
            width: 100%;
            padding: 15px 0;
        }
    }
    #frame #sidepanel #bottom-bar button:focus {
        outline: none;
    }
    #frame #sidepanel #bottom-bar button:nth-child(1) {
        border-right: 1px solid #2c3e50;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #bottom-bar button:nth-child(1) {
            border-right: none;
            border-bottom: 1px solid #2c3e50;
        }
    }
    #frame #sidepanel #bottom-bar button:hover {
        background: #435f7a;
    }
    #frame #sidepanel #bottom-bar button i {
        margin-right: 3px;
        font-size: 1em;
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #bottom-bar button i {
            font-size: 1.3em;
        }
    }
    @media screen and (max-width: 735px) {
        #frame #sidepanel #bottom-bar button span {
            display: none;
        }
    }
    #frame .content {
        float: right;
        width: 60%;
        height: 100%;
        overflow: hidden;
        position: relative;
    }
    @media screen and (max-width: 735px) {
        #frame .content {
            width: calc(100% - 58px);
            min-width: 300px !important;
        }
    }
    @media screen and (min-width: 900px) {
        #frame .content {
            width: calc(100% - 340px);
        }
    }
    #frame .content .contact-profile {
        width: 100%;
        height: 60px;
        line-height: 60px;
        background: #f5f5f5;
    }
    #frame .content .contact-profile img {
        width: 40px;
        border-radius: 50%;
        float: left;
        margin: 9px 12px 0 9px;
    }
    #frame .content .contact-profile p {
        float: left;
    }
    #frame .content .contact-profile .social-media {
        float: right;
    }
    #frame .content .contact-profile .social-media i {
        margin-left: 14px;
        cursor: pointer;
    }
    #frame .content .contact-profile .social-media i:nth-last-child(1) {
        margin-right: 20px;
    }
    #frame .content .contact-profile .social-media i:hover {
        color: #435f7a;
    }
    #frame .content .messages {
        height: auto;
        min-height: calc(100% - 93px);
        max-height: calc(100% - 93px);
        overflow-y: scroll;
        overflow-x: hidden;
    }
    @media screen and (max-width: 735px) {
        #frame .content .messages {
            max-height: calc(100% - 105px);
        }
    }
    #frame .content .messages::-webkit-scrollbar {
        width: 8px;
        background: transparent;
    }
    #frame .content .messages::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.3);
    }
    #frame .content .messages ul li {
        display: inline-block;
        clear: both;
        float: left;
        margin: 15px 15px 5px 15px;
        width: calc(100% - 25px);
        font-size: 0.9em;
    }
    #frame .content .messages ul li:nth-last-child(1) {
        margin-bottom: 20px;
    }
    #frame .content .messages ul li.sent img {
        margin: 6px 8px 0 0;
    }
    #frame .content .messages ul li.sent p {
        background: #435f7a;
        color: #f5f5f5;
    }
    #frame .content .messages ul li.replies img {
        float: right;
        margin: 6px 0 0 8px;
    }
    #frame .content .messages ul li.replies p {
        background: #f5f5f5;
        float: right;
    }
    #frame .content .messages ul li img {
        width: 22px;
        border-radius: 50%;
        float: left;
    }
    #frame .content .messages ul li p {
        display: inline-block;
        padding: 10px 15px;
        border-radius: 20px;
        max-width: 205px;
        line-height: 130%;
    }
    @media screen and (min-width: 735px) {
        #frame .content .messages ul li p {
            max-width: 300px;
        }
    }
    #frame .content .message-input {
        position: absolute;
        bottom: 0;
        width: 100%;
        z-index: 99;
    }
    #frame .content .message-input .wrap {
        position: relative;
    }
    #frame .content .message-input .wrap input {
        font-family: "proxima-nova",  "Source Sans Pro", sans-serif;
        float: left;
        border: none;
        width: calc(100% - 90px);
        padding: 11px 32px 10px 8px;
        font-size: 0.8em;
        color: #32465a;
    }
    @media screen and (max-width: 735px) {
        #frame .content .message-input .wrap input {
            padding: 15px 32px 16px 8px;
        }
    }
    #frame .content .message-input .wrap input:focus {
        outline: none;
    }
    #frame .content .message-input .wrap .attachment {
        position: absolute;
        right: 60px;
        z-index: 4;
        margin-top: 10px;
        font-size: 1.1em;
        color: #435f7a;
        opacity: .5;
        cursor: pointer;
    }
    @media screen and (max-width: 735px) {
        #frame .content .message-input .wrap .attachment {
            margin-top: 17px;
            right: 65px;
        }
    }
    #frame .content .message-input .wrap .attachment:hover {
        opacity: 1;
    }
    #frame .content .message-input .wrap button {
        float: right;
        border: none;
        width: 50px;
        padding: 12px 0;
        cursor: pointer;
        background: #32465a;
        color: #f5f5f5;
    }
    @media screen and (max-width: 735px) {
        #frame .content .message-input .wrap button {
            padding: 16px 0;
        }
    }
    #frame .content .message-input .wrap button:hover {
        background: #435f7a;
    }
    #frame .content .message-input .wrap button:focus {
        outline: none;
    }
</style>