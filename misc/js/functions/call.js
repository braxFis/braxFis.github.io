// Get DOM elements
const startCallBtn = document.getElementById("startCallBtn");
const hangUpBtn = document.getElementById("hangUpBtn");
const localVideo = document.getElementById("localVideo");
const remoteVideo = document.getElementById("remoteVideo");

// WebRTC configuration (use a public STUN server here)
const configuration = {
    iceServers: [{ urls: "stun:stun.l.google.com:19302" }]
};

// Define some global variables for the peer connection and local media stream
let localStream;
let peerConnection;

// Handle user media (camera/microphone)
async function startMedia() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = stream;
        localStream = stream;
    } catch (err) {
        console.error("Error accessing media devices.", err);
    }
}

// Create a new peer connection
function createPeerConnection() {
    peerConnection = new RTCPeerConnection(configuration);

    // When remote stream is added to the peer connection, display it
    peerConnection.ontrack = (event) => {
        remoteVideo.srcObject = event.streams[0];
    };

    // Add all tracks from the local stream to the peer connection
    localStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localStream);
    });

    // Handle ICE candidates
    peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
            sendSignal("ice-candidate", event.candidate);
        }
    };
}

// Handle start call
startCallBtn.addEventListener("click", async () => {
    // Start media
    await startMedia();

    // Create a peer connection
    createPeerConnection();

    // Create an offer to start the call
    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);

    // Send the offer to the remote peer (this is where you'd use signaling)
    sendSignal("offer", offer);

    // Disable start button, enable hang up button
    startCallBtn.disabled = true;
    hangUpBtn.disabled = false;
});

// Handle hang up
hangUpBtn.addEventListener("click", () => {
    peerConnection.close();
    localStream.getTracks().forEach(track => track.stop());
    localVideo.srcObject = null;
    remoteVideo.srcObject = null;

    // Re-enable start button, disable hang up button
    startCallBtn.disabled = false;
    hangUpBtn.disabled = true;
});

// Simulate sending a signal to a remote peer (in a real app, use WebSockets or another signaling method)
function sendSignal(type, data) {
    console.log(`Sending signal: ${type}`, data);
    // Here you'd send the signal (offer, answer, ICE candidate) to the remote peer.
    // This is where you'd integrate with a backend signaling server.
}

// Simulate receiving a signal (in a real app, use WebSockets or another signaling method)
function receiveSignal(type, data) {
    console.log(`Received signal: ${type}`, data);

    if (type === "offer") {
        // If we receive an offer, create an answer
        peerConnection.setRemoteDescription(new RTCSessionDescription(data));
        createAnswer();
    } else if (type === "answer") {
        // If we receive an answer, set it as remote description
        peerConnection.setRemoteDescription(new RTCSessionDescription(data));
    } else if (type === "ice-candidate") {
        // If we receive an ICE candidate, add it to the connection
        peerConnection.addIceCandidate(new RTCIceCandidate(data));
    }
}

// Simulate creating an answer for the call
async function createAnswer() {
    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);

    // Send the answer to the remote peer
    sendSignal("answer", answer);
}
