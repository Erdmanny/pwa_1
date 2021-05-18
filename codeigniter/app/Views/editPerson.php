<?php
$validation = \Config\Services::validation();
?>


<div class="container">
    <h1 class="mt-3 text-light">Edit Person</h1>
    <div class="row mt-3">

        <div class="col-lg-12">
            <form action="<?php echo base_url("people/editPerson_Validation")?>" method="post" id="edit-person-form">
                <div class="card">
                    <div class="card-body">
                        <div class="row m-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="edit-prename">Prename:</label>
                                    <input type="text" class="form-control" name="edit-prename" id="edit-prename"
                                           placeholder="Peter" value="<?= $person->vorname ?>">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('edit-prename')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('edit-prename') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="edit-surname">Surname:</label>
                                    <input type="text" class="form-control" name="edit-surname" id="edit-surname"
                                           placeholder="Mustermann" value="<?= $person->name?>"">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('edit-surname')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('edit-surname') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="edit-street">Street:</label>
                                    <input type="text" class="form-control" name="edit-street" id="edit-street"
                                           placeholder="Musterstr. 11" value="<?= $person->strasse?>">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('edit-street')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('edit-street') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="edit-postcode">Postcode:</label>
                                    <input type="text" class="form-control" name="edit-postcode" id="edit-postcode"
                                           placeholder="54299" value="<?= $person->plz?>">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('edit-postcode')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('edit-postcode') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <div class="form-group">
                                    <label for="edit-city">City:</label>
                                    <input type="text" class="form-control" name="edit-city" id="edit-city"
                                           placeholder="Musterhausen" value="<?= $person->ort?>">
                                </div>
                            </div>
                            <?php
                            if ($validation->getError('edit-city')):
                                ?>
                                <div class="col-12">
                                    <div class="alert alert-danger" role="alert">
                                        <?= $validation->getError('edit-city') ?>
                                    </div>
                                </div>
                            <?php
                            endif;
                            ?>

                            <div class="col-12">
                                <a href="/people" class="btn btn-warning">Cancel</a>
                                <input type="hidden" name="id" value="<?= $person->id ?>">
                                <button type="submit" class="btn btn-primary float-right">Edit</button>
                            </div>
                        </div>


                    </div>

                </div>
            </form>
        </div>

    </div>
</div>


