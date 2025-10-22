<h2>News</h2>
<ul>
  <?php foreach($news as $new):?>
    <li>
      <div class="add-border">
        <label for="title">Title</label>
        <p><?php echo $new->title;?></p>
        <label for="subtitle">Subtitle</label>
        <p><?php echo $new->subtitle;?></p>
        <label for="content">Content</label>
        <p><?php echo $new->content;?></p>
        <label for="date">Date</label>
        <p><?php echo $new->date;?></p>
        <label for="author">Author</label>
        <p><?php echo $new->author;?></p>
        <label for="media">Media</label>
        <img src="<?php echo $new->media;?>" alt="">
        <label for="tags">Tags</label>
        <p><?php echo $new->tags;?></p>
        <label for="user_id">User ID</label>
        <p><?php echo $new->user_id;?></p>
      </div>
    </li>

    <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
      <li style="list-style-type: none">
        <button><a style="text-decoration: none; color: white" href="/news/edit/<?php echo $new->id;?>">Edit News</a></button>
        <!-- Delete Review -->
        <form action="/news/delete/<?php echo $new->id;?>" method="post">
          <input type="hidden" name="id" id="" value="<?php echo $new->id;?>">
          <button type="submit">Delete News</button>
        </form>
      </li>
    <?php endif;?>
  <?php endforeach; ?>

  <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
    <button><a style="text-decoration: none; color: white;" href="/news/create">Create News</a></button>
  <?php endif;?>
</ul>
<?php

