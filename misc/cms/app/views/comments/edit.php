<?php
if (!$comment){
    echo 'Comment not found';
    return;
}
?>

<form action="/comments/update/<?php echo $comment->id;?>" method="post">
    <input type="hidden" name="id" value="<?php echo $comment->id;?>">
    <input type="hidden" name="post_id" value="<?php echo $comment->post_id;?>">

    <div style="margin-bottom: 20px;">
        <label for="content">Comment Content</label>
        <textarea name="content" id="content" cols="30" rows="10"><?php echo htmlspecialchars($comment->content);?></textarea>
    </div>

    <button type="submit">Update Comment</button>
</form>
