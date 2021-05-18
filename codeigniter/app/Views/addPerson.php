<?php
$validation = \Config\Services::validation();
?>


<div class="container">
    <h1 class="mt-3 text-light">Add Person</h1>
    <div class="row mt-3">

        <div class="col-lg-12">
            <form action="<?php echo base_url("people/addPerson_Validation")?>" method="post" id="new-person-form">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="new-prename">Prename:</label>
                                    <input type="text" class="form-control" name="new-prename" id="new-prename" placeholder="Peter">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('new-prename')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('new-prename') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="new-surname">Surname:</label>
                                    <input type="text" class="form-control" name="new-surname" id="new-surname" placeholder="Mustermann">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('new-surname')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('new-surname') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="new-street">Street:</label>
                                    <input type="text" class="form-control" name="new-street" id="new-street" placeholder="Musterstr. 11">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('new-street')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('new-street') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="new-postcode">Postcode:</label>
                                    <input type="text" class="form-control" name="new-postcode" id="new-postcode" placeholder="54299">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('new-plz')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('new-plz') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="new-city">City:</label>
                                    <input type="text" class="form-control" name="new-city" id="new-city" placeholder="Musterhausen">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('new-city')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('new-city') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <a href="/people" class="btn btn-warning">Cancel</a>
                                <button type="submit" class="btn btn-primary float-right">Save</button>
                            </div>
                        </div>


                    </div>

                </div>
            </form>
        </div>

    </div>
</div>


