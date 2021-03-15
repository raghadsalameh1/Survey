<style>
    .btn {
        margin: 10px;
    }
</style>
<div class="container">
    <div class="row align-items-center" style="min-height: 25rem">
        <div class="col-8">
            <div class="card">
                <h5 class="card-header" style="text-align: center;"><?php echo $survey["Survey"]["Name"] ?></h5>
                <div class="card-body" style="margin: 10px;" Id="question">
                    <p class="card-text" style="text-align: center;"><?php echo $survey["Question"][0]["Question"] ?></p>
                    <div Id="choices" class="row justify-content-center align-self-center">
                        <button onclick="Next(<?php echo empty($survey['Question'][0]['Yes_id']) ? -1 : $survey['Question'][0]['Yes_id'] ?> ,1)" class="btn btn-success">yes</button>
                        <button onclick="Next(<?php echo empty($survey['Question'][0]['No_id']) ? -1 : $survey['Question'][0]['No_id'] ?>,0)" class="btn btn-danger">No</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="btn-group">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-display="static" aria-haspopup="true" aria-expanded="false">
                    Need to add notes?
                </button>
                <div class="dropdown-menu" style="min-width: 20rem;">
                    <div class="px-4 py-3">
                        <div class="form-group">
                            <label for="note">Enter your note here</label>
                            <textarea class="form-control" id="note" rows="3"></textarea>
                        </div>
                        <button onclick="SaveNote()" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    <?php echo $this->Html->script('jquery-3.5.1.min.js', array('inline' => false)); ?>

    function Next(param, r) {
        //alert(param);
        $.ajax({
            url: "http://survey.test/questions/getquestionInfo/" + param + "/" + r,
            type: 'GET',
            cache: false,
            success: function(data) {
                if (data == "done") {
                    document.getElementById('question').innerHTML = '';
                    $('#question').append('<h5 style="text-align: center;">The survey quesitons has finished. Please submit your application</h5>');
                    $('#question').append('<div Id="choices" class="row justify-content-center align-self-center"></div>')
                    $('#choices').append('<a href="/questions/submit" class="btn btn-primary">Submit</a>');
                } else {
                    var res = JSON.parse(data);
                    document.getElementById('question').innerHTML = '';
                    $('#question').append('<p class="card-text" style="text-align: center;">' + res.Question + '</p>');
                    $('#question').append('<div Id="choices" class="row justify-content-center align-self-center"></div>')
                    $('#choices').append('<button href="" onclick="Next(' + res.Yes_id + ',1)" class="btn btn-success">yes</button>');
                    $('#choices').append(' <button href="" onclick="Next(' + res.No_id + ',0)" class="btn btn-danger">No</button>');
                    //alert(res.Question);
                }
            },
            error: function(error) {
                alert(error);
            }
        });
        return false;
        //preventDefault();
    }

    function SaveNote() {
        var x = document.getElementById('note');
        if (x.value != "") {
            $.ajax({
                type: 'POST',
                url: "http://survey.test/questions/cache_note",
                data: {
                    "note": x.value
                },
                success: function(data, textStatus, xhr) {
                    x.value ='';
                    alert(data);
                },
                error: function(xhr, textStatus, error) {
                    alert(textStatus);
                }
            });
            return false;
        }
    }
</script>
<!--<script type = "text/javascript" >
    $(document).ready(function() {
        $('#saveForm').submit(function() {
            //serialize form data
            var formData = $(this).serialize();
            //get form action
            var formUrl = $(this).attr('action');

            $.ajax({
                type: 'POST',
                url: formUrl,
                data: formData,
                success: function(data, textStatus, xhr) {
                    alert(data);
                },
                error: function(xhr, textStatus, error) {
                    alert(textStatus);
                }
            });

            return false;
        });
    });
</script>-->