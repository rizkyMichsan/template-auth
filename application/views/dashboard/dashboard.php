<!-- Page Heading -->
<?php $poto = str_replace(" ", "_", $user['name']);; ?>
<h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>
<div class="card mb-3" style="max-width: 540px;">
    <div class="row no-gutters">
        <div class="col-md-4">
            <img src="<?php echo base_url('assets/user/') . $poto . '/' . $user['image']; ?>" class="card-img" alt="...">
        </div>
        <div class="col-md-8">
            <div class="card-body">
                <h5 class="card-title"><?php echo $user['name']; ?></h5>
                <p class="card-text"><?php echo $user['email']; ?></p>
                <p class="card-text"><small class="text-muted">Member since <?php echo date('d F Y', $user['date_created']); ?></small></p>
            </div>
        </div>
    </div>
</div>
</div>