'use strict'

let callBtn = $("#callBtn");
let call_modal = $("#call_modal");
let answer = $("#answerBtn");
let decline = $("#declineBtn");

let pc;
let sendTo = callBtn.data("user");
let localStream;
const localVideo = document.querySelector("#localVideo");
const remoteVideo = document.querySelector("#remoteVideo");
const mediaConst = {
    video: true,
    audio: true
}
const config = {
    iceServers: [
        {urls: "stun:stun1.l.google.com:19302"},
    ]
}
const options ={
    offerToReceiveVideo: 1,
    offerToReceiveAudio: 1,
}

function getConn(){
    if(!pc){
        pc = new RTCPeerConnection(config);
    }
}
async function getCam(){
    let mediaStream;
    try{
        if(!pc){
            await getConn();
        }
        mediaStream = await navigator.mediaDevices.getUserMedia(mediaConst);
        localVideo.srcObject = mediaStream;
        localStream = mediaStream;
        localStream.getTracks().forEach(function(track){
            pc.addTrack(track, localStream);
        });
    }catch(error){
        console.log(error);
    }
}
async function createOffer(sendTo){
    if(!pc){
        await getConn();
    }
    if(!localStream){
        await getCam();
    }
    await sendIceCandidate(sendTo);
    await pc.createOffer(options);
    await pc.setLocalDescription(pc.localDescription);
    send("client-offer", pc.localDescription, sendTo);
}
async function createAnswer(sendTo, data){
    if(!pc){
        await getConn();
    }
    if(!localStream){
        //This is what is giving the error
        await getCam();
    }
    await sendIceCandidate(sendTo);
    await pc.setRemoteDescription(data);
    await pc.createAnswer();
    await pc.setLocalDescription(pc.localDescription);
    send("client-answer", pc.localDescription, sendTo);
}
function sendIceCandidate(sendTo){
    pc.onicecandidate = function(e){
        if(e.candidate !== null){
            send("client-candidate", e.candidate, sendTo);
        }
    }
    pc.ontrack = function(e){
        $("#video").removeClass("d-none");
        $(".login-section").addClass("d-none");
        remoteVideo.srcObject = e.streams[0];
    }
}
function hangup(){
    send("client-hangup", null, sendTo);
    pc.close();
    pc = null;
}
$("#callBtn").on("click", function(){
    getCam();
    send("is-client-ready", null, sendTo);
})
$("#hangupBtn").on("click", function(){
    hangup();
    location.reload(true);
})

conn.onopen = function(e){
    console.log("connected to websocket");
}
conn.onmessage = async function(e){
    let message = JSON.parse(e.data);
    let by = message.by;
    let data = message.data;
    let type = message.type;
    let profileImage = message.profileImage;
    let username = message.username;
    $("#caller_name").text(username);
    $("#caller_image").attr("src", `assets/images/${profileImage}`);
    switch(type){
        case "client-candidate":
            if(pc.localDescription){
                await pc.addIceCandidate(new RTCIceCandidate(data));
            }
        break;
        case "is-client-ready":
            if(!pc){
                await getConn();
            }
            if(pc.iceConnectionState === "connected"){
                send("client-already-oncall", null, by);
            }else{
                displayCall();
                if(window.location.href.indexOf(username > -1)){
                    answer.on("click", function(){
                        $(document).ready(function(){
                            call_modal.removeClass("d-flex");
                            call_modal.addClass("d-none");
                            call_modal.modal("hide");
                        });
                        send("client-is-ready", null, sendTo);
                    })
                }else{
                    $(document).ready(function(){
                        call_modal.removeClass("d-flex");
                        call_modal.addClass("d-none");
                        call_modal.modal("hide");
                    });
                    redirectToCall(username, by);
                }
                decline.on("click", function(){
                    send("client-rejected", null, sendTo);
                    location.reload(true);
                })
            }
        break;
        case "client-answer":
            if(pc.localDescription){
                await pc.setRemoteDescription(data);
                $("#call_timer").timer({format: "%m:%s"});
            }
        break;
        case "client-offer":
            if(!pc){
                await getConn();
            }
            if(!localStream){
                await getCam();
            }
            createAnswer(sendTo, data);
            $("#call_timer").timer({format: "%m:%s"});
        break;
        case "client-is-ready":
            createOffer(sendTo);
        break;
        case "client-already-oncall":
            alert("user is on another call");
            setTimeout("window.location.reload(true)", 3000);
        break;
        case "client-rejected":
            alert("user is busy");
        break;
        case "client-hangup":
            alert("Call Disconnected");
            $("#video").addClass("d-none");
            $(".login-section").removeClass("d-none");
            setTimeout("window.location.reload(true)", 3000);
        break;
    }
}
function send(type, data, sendTo){
    conn.send(JSON.stringify({
        sendTo: sendTo,
        type: type,
        data: data
    }))
}


function displayCall(){
        $(document).ready(function(){
            call_modal.removeClass("d-none");
            call_modal.addClass("d-flex");
            call_modal.modal("show");
        })
}

function redirectToCall(username, sendTo){
    if(window.location.href.indexOf(username) == -1){
        sessionStorage.setItem("redirect", true);
        sessionStorage.setItem("sendTo", sendTo);
        window.location.href = "/my_v_chat/" + username; 
    }
}
if(sessionStorage.getItem("redirect")){
    sendTo = sessionStorage.getItem("sendTo");
    let waitForWs = setInterval(function(){
        if(conn.readyState === 1){
            send("client-is-ready", null, sendTo);
            clearInterval(waitForWs);
        }
    }, 500);
    sessionStorage.removeItem("redirect");
    sessionStorage.removeItem("sendTo");
}