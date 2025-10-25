<style>
 .news-container {
  display: flex;
  flex-direction: column;
  gap: 1.5rem; /* mellanrum mellan nyheter */
  max-width: 900px;
  margin: 0 auto;
  padding: 1rem;
}

.news-ind-container {
  display: flex;
  align-items: flex-start;
  background: #f9f9f9;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  overflow: hidden;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.news-ind-container:hover {
  transform: translateY(-3px);
  box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

.news-ind-container img {
  width: 200px;
  height: 140px;
  object-fit: cover;
  flex-shrink: 0;
}

.news-text {
  padding: 1rem 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.news-text h1 {
  font-size: 1.4rem;
  margin: 0 0 0.5rem;
  color: #222;
}

.news-text p {
  font-size: 1rem;
  color: #555;
  margin: 0 0 1rem;
  line-height: 1.5;
}

.news-text button {
  align-self: start;
  padding: 0.5rem 1.2rem;
  background: #007BFF;
  color: #fff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background 0.2s ease;
}

.news-text button:hover {
  background: #0056b3;
}

/* Responsivt: stapla på små skärmar */
@media (max-width: 600px) {
  .news-ind-container {
    flex-direction: column;
    align-items: center;
  }

  .news-ind-container img {
    width: 100%;
    height: 180px;
  }

  .news-text {
    text-align: center;
  }

  .news-text button {
    align-self: center;
  }
}

</style>
<h2>Reviews Directory</h2>
<div class="news-container">
<?php foreach($reviews as $review):?>
  <div class="news-ind-container">
    <img src="<?= $review->media?>" alt="">
    <div class="news-text">
      <h1><?= $review->title?></h1>
      <p><?= $review->content?></p>
      <button><a href="/review/indie/<?=$review->id?>">Read More</a></button>
    </div>
  </div>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'):?>
          <li style="list-style-type: none">
            <button><a style="text-decoration: none; color: white" href="/news/edit/<?php echo $new->id;?>">Edit News</a></button>
            <!-- Delete News -->
            <form action="/review/edit/<?php echo $review->id;?>" method="post">
              <input type="hidden" name="id" id="" value="<?php echo $review->id;?>">
            </form>
          </li>
        <?php endif;?>
<?php endforeach;?>
</div>