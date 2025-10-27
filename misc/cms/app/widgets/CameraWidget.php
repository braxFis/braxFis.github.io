<?php

namespace app\widgets;

use app\models\Camera;

class CameraWidget {
    public static function renderCamera(){
        $html = <<<HTML
        <video id="video" width="640" height="480" autoplay></video>
        <button id="start-camera">Start Camera</button>
        <button id="stop-camera" disabled>Stop Camera</button>
        <button id="capture-image" disabled>Capture Image</button>
        <button id="start-recording" disabled>Start Recording</button>
        <button id="stop-recording" disabled>Stop Recording</button>
        <h2>Captured Image</h2>
        <canvas id="canvas" width="640" height="480"></canvas>
        <h2>Recorded Video</h2>
        <video id="recorded-video" width="640" height="480" controls></video>
        <script src="/js/functions/camera.js"></script>
        HTML;

        return $html;
    }
}