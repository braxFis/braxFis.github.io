<link rel="stylesheet" href="/css/editor.css">
<h2>Bygg layout</h2>
<?php 

$types = [
    "h1" => "Rubrik H1",
    "h2" => "Rubrik H2",
    "h3" => "Rubrik H3",
    "h4" => "Rubrik H4",
    "h5" => "Rubrik H5",
    "h6" => "Rubrik H6",
    "p" => "Text P",
    "img" => "Bild",
    "video" => "Video",
    "audio" => "Audio",
    "input" => "Textfält"
];

?>
<!--

if($slug === 'page'){
    Load First Editor
}

<?php //foreach($videos as $video):?>
<?php //if($slug === 'news'):?>
    Load Second Editor
    <video controls>
        <source src="$video->url" type="video/mp4">
        Your browser does not support the video tag.
    </video>
<?php //endif;?>
<?php //endforeach;?>
-->

<?= 

$audioFile = '/media/sample-audio.mp3';
$videoFile = '/media/sample-video.mp4';
$imgFile = '/media/sample-image.jpg';
?>
<div class="editor">
  <div id="palette">
    <?= $src = '/media/sample-video.mp4'; ?>
    <?php foreach ($types as $type => $label): ?>
      <div class="palette-item" data-type="<?= htmlspecialchars($type) ?>">
        <?= htmlspecialchars($label) ?>
        <?php if($type === 'input'):?>
            <input type="text" id="fieldTitle" placeholder="Ange titel här">
        <?php endif;?>
        <?php if($type === 'h1'):?>
            <h1><?= $label?></h1>
        <?php endif;?>
        <?php if($type === 'p'):?>
            <p><?= $label ?></p>
        <?php endif;?>
        <?php if($type === 'video'):?>
                <?= $label ?>
            <video width="160" height="90" id="videoSource" controls>
                <source src="<?= $label ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        <?php endif;?>
        <?php if($type === 'audio'):?>
            <audio id="audioSource" controls>
                <source src="<?= $audioSource ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        <?php endif;?>
        <?php if($type === 'img'):?>
            <img src="<?= $imgFile ?>"/>
        <?php endif;?>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<button id="saveLayoutBtn">💾 Spara layout</button>
<script>
    const audioFile = "<?= $audioFile ?>";
    document.getElementById('audioSource').src = audioFile;
    const videoFile = "<?= $videoFile ?>";
    document.getElementById('videoSource').src = videoFile;
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="/js/editor.js"></script>
