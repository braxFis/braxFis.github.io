// Get references to the video element, canvas, buttons, and recorded video element
const videoElement = document.getElementById('video');
const canvasElement = document.getElementById('canvas');
const recordedVideoElement = document.getElementById('recorded-video');
const startCameraButton = document.getElementById('start-camera');
const stopCameraButton = document.getElementById('stop-camera');
const captureImageButton = document.getElementById('capture-image');
const startRecordingButton = document.getElementById('start-recording');
const stopRecordingButton = document.getElementById('stop-recording');

let mediaStream; // Variable to store the media stream
let mediaRecorder; // Variable to store the media recorder
let recordedChunks = []; // Array to store video data

// Check if MediaRecorder is supported
if (!window.MediaRecorder) {
    alert('MediaRecorder API is not supported in this browser.');
    startRecordingButton.disabled = true;
    stopRecordingButton.disabled = true;
}

// Start the camera when the "Start Camera" button is clicked
startCameraButton.addEventListener('click', async () => {
    try {
        // Request access to the user's camera
        mediaStream = await navigator.mediaDevices.getUserMedia({ video: true });

        // Set the video element's source to the media stream
        videoElement.srcObject = mediaStream;

        // Enable buttons for image capture and video recording
        captureImageButton.disabled = false;
        startRecordingButton.disabled = false;

        // Hide the start button and show the stop button
        startCameraButton.disabled = true;
        stopCameraButton.disabled = false;
    } catch (error) {
        console.error('Error accessing the camera:', error);
        alert('Could not access the camera. Please try again.');
    }
});

// Stop the camera when the "Stop Camera" button is clicked
stopCameraButton.addEventListener('click', () => {
    if (mediaStream) {
        // Stop all tracks (camera, microphone)
        const tracks = mediaStream.getTracks();
        tracks.forEach(track => track.stop());

        // Clear the video element's source object
        videoElement.srcObject = null;

        // Disable buttons for image capture and video recording
        captureImageButton.disabled = true;
        startRecordingButton.disabled = true;

        // Enable the start button and disable the stop button
        startCameraButton.disabled = false;
        stopCameraButton.disabled = true;
    }
});

// Capture an image when the "Capture Image" button is clicked
captureImageButton.addEventListener('click', () => {
    const context = canvasElement.getContext('2d');

    // Draw the current video frame onto the canvas
    context.drawImage(videoElement, 0, 0, canvasElement.width, canvasElement.height);
});

// Start recording when the "Start Recording" button is clicked
startRecordingButton.addEventListener('click', () => {
    if (!mediaStream) {
        console.error('Camera not initialized');
        alert('Please start the camera first.');
        return;
    }

    // Define MIME type options to try
    const mimeTypes = [
        'video/webm;codecs=vp8',  // VP8 codec in WebM format
        'video/webm',              // WebM without codec specifier
        'video/mp4',               // MP4 format (may not work on all browsers)
    ];

    let selectedMimeType = null;

    // Check which MIME type is supported by the browser
    for (let mimeType of mimeTypes) {
        if (MediaRecorder.isTypeSupported(mimeType)) {
            selectedMimeType = mimeType;
            console.log('Using supported MIME type:', mimeType);
            break;
        }
    }

    if (!selectedMimeType) {
        console.error('No supported MIME type found.');
        alert('No supported video recording format found.');
        return;
    }

    try {
        // Initialize MediaRecorder with the media stream and selected MIME type
        mediaRecorder = new MediaRecorder(mediaStream, { mimeType: selectedMimeType });

        // Collect data when recording
        mediaRecorder.ondataavailable = (event) => {
            recordedChunks.push(event.data);
        };

        // When recording stops, create a video URL from the collected chunks
        mediaRecorder.onstop = () => {
            const recordedBlob = new Blob(recordedChunks, { type: selectedMimeType });
            const videoURL = URL.createObjectURL(recordedBlob);
            recordedVideoElement.src = videoURL;
            recordedVideoElement.style.display = 'block';
        };

        // Start recording
        mediaRecorder.start();
        console.log('Recording started.');

        // Disable the start recording button and enable the stop recording button
        startRecordingButton.disabled = true;
        stopRecordingButton.disabled = false;
    } catch (error) {
        console.error('Error starting MediaRecorder:', error);
        alert('An error occurred while starting the recording.');
    }
});

// Stop recording when the "Stop Recording" button is clicked
stopRecordingButton.addEventListener('click', () => {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
        console.log('Recording stopped.');

        // Disable the stop recording button and enable the start recording button
        stopRecordingButton.disabled = true;
        startRecordingButton.disabled = false;
    }
});
