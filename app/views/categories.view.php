<?php $this->view('head'); ?>

<h1>Categories</h1>

<a href="<?= ROOT ?>/categories/add">Add Category</a>

<?php if($action == 'add') { ?>
    <h1>Add Category</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="image">Image</label>
            <input type="file" name="image" id="image" required>
        </div>
        <button type="submit">Add Category</button>
    </form>
<?php } ?>

<?php $this->view('footer'); ?>