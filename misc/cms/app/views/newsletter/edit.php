<form action="/newsletter/update/<?php echo $newsletter->id;?>" method="post">
    <input type="hidden" name="id" id="<?php echo $newsletter->id;?>" value="<?php echo $newsletter->id;?>">
    <div>
        <label for="name">Newsletter Name</label>
        <input type="text" name="title" id="title" value="<?php echo $newsletter->title;?>">

        <label for="body">Body</label>
        <textarea name="body" id="body" cols="30" rows="10"><?php echo $newsletter->body;?></textarea>

        <label for="created_at">Created At</label>
        <input type="date" name="created_at" id="created_at" value="<?php echo $newsletter->created_at;?>">

        <label for="status">Status</label>
        <select name="status" id="status">
            <?php foreach($enums as $enum):?>
                <option value="<?php echo $enum->status;?>">
                    <?php echo $enum->status;?>
                </option>
            <?php endforeach;?>
        </select>

        <button type="submit">Submit</button>
    </div>
</form>