<?php
// plugins/HelloWorld/HelloWorld.php
class HelloWorld {
  public function onInit() {
    echo "Hello World plugin laddat!<br>";
  }

  public function onRenderFooter() {
    echo "<p>© HelloWorld plugin</p>";
  }
}
