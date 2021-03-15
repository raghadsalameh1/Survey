<div class="container">
    <h1 style="text-align: center;">Add Survey</h1>
    <h2 style="text-align: center;">List of questions:</h2>
    <?php if (!empty($validationErrorsArray))
    echo'<div class="alert alert-danger" role="alert">'. $validationErrorsArray.'</div>';?>

    <form method="POST">
        <?php
        $num = $this->Session->read('number_of_questions');
        for ($i = 1; $i <= $num; $i++) { ?>
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="validationCustom <?php echo $i; ?>">Question <?php echo $i; ?></label>
                    <input type="text" name="Question<?php echo $i; ?>" class="form-control" id="validationCustom<?php echo $i; ?>" value="<?php echo  array_key_exists("Question" . $i,$requestData) ? $requestData["Question" . $i] : ""; ?>" required>
                    <div class="invalid-feedback">
                        Please provide a valid Question.
                    </div>
                </div>
                <?php //echo  $requestData["Yes" . $i] != null ? 'selected' : ''; 
                ?>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom<?php echo $i; ?>">Yes Next Question</label>
                    <select name="Yes<?php echo $i; ?>" class="custom-select" id="validationCustom<?php echo $i; ?>" required>
                        <option selected value="0">Submit</option>
                        <?php for ($j = 2; $j <= $num; $j++) { ?>
                            <option value="<?php echo $j; ?>">Q <?php echo $j; ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        Please select a valid Question.
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom<?php echo $i; ?>">No Next Question</label>
                    <select name="No<?php echo $i; ?>" class="custom-select" id="validationCustom<?php echo $i; ?>" required>
                        <option selected value="0">Submit</option>
                        <?php for ($j = 2; $j <= $num; $j++) { ?>
                            <option value="<?php echo $j; ?>">Q <?php echo $j; ?></option>
                        <?php } ?>
                    </select>
                    <div class="invalid-feedback">
                        Please select a valid Question.
                    </div>
                </div>
            </div>
        <?php }
        echo $this->Html->link(
            'Previous step',
            array('action' => 'msf_step', $params['currentStep'] - 1),
            array('class' => 'btn btn-secondary')
        );
        ?>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>