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
    "audio" => "Audio"
];

?>
<!--

if($slug === 'page'){
    Load First Editor
}
<?php foreach($videos as $video):?>
<?php if($slug === 'news'):?>
    Load Second Editor
    <video controls>
        <source src="$video->url" type="video/mp4">
        Your browser does not support the video tag.
    </video>
<?php endif;?>
<?php endforeach;?>
-->

<div class="editor">
  <div id="palette">
    <?php foreach ($types as $type => $label): ?>
      <div class="palette-item" data-type="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($label) ?></div>
  </div>
    <?php endforeach; ?>
  <div id="canvas" data-slug="<?= htmlspecialchars($$slug ?? '') ?>">
    <p class="placeholder">Dra element hit</p>
  </div>
</div>

<button id="saveLayoutBtn">💾 Spara layout</button>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="/js/editor.js"></script>
