<div class="container">
    <h1 style="text-align: center;">Add Survey</h1>
    <form method="POST">
        <div class="form-group">
            <label for="Name">Survey Name</label>
            <input class="form-control <?php
                                        if (!empty($validationErrorsArray["Name"])) {
                                            echo ' is-invalid';
                                        } ?>" aria-describedby="NamevalidationFeedback" id="Name" name="Name" placeholder="Survey Name" value="<?php echo $requestData != null ? $requestData["Name"] : "" ?>" required>
            <?php if (!empty($validationErrorsArray["Name"])) { ?>
                <div id="NamevalidationFeedback" class="invalid-feedback">
                    <?php foreach ($validationErrorsArray["Name"] as $value) {
                        echo $value;
                    } ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group">
            <label for="Email">Creator Email</label>
            <input class="form-control <?php
                                        if (!empty($validationErrorsArray["Email"])) {
                                            echo ' is-invalid';
                                        } ?>" type="email" aria-describedby="EmailvalidationFeedback" id="Email" name="Email" placeholder="example@gmail.com" value="<?php echo $requestData != null ? $requestData["Email"] : "" ?>" required>
            <?php if (!empty($validationErrorsArray["Email"])) { ?>
                <div id="EmailvalidationFeedback" class="invalid-feedback">
                    <?php foreach ($validationErrorsArray["Email"] as $value) {
                        echo $value;
                    } ?>
                </div>
            <?php } ?>
        </div>
        <div class="form-group">
            <label for="numberOfQues">Number of questions:</label>
            <?php
            $numvalidation = $this->Session->read('numberOfQuesValidationError'); ?>
            <input class="form-control<?php if (!empty($numvalidation)) {
                                            echo ' is-invalid';
                                        } ?>" aria-describedby="NumbervalidationFeedback" onKeyDown="return false" id="numberOfQues" type="number" name="numberOfQues" value="<?php echo  $requestData != null ? $requestData["numberOfQues"] : 1; ?>" min="1" onchange="addFields()" required />
            <?php if (!empty($numvalidation)) {
                //echo print_r($validationErrorsArray);
                echo ' <div id="NumbervalidationFeedback" class="invalid-feedback">' . $numvalidation . '</div>';
            }
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Next step</button>
    </form>
</div>