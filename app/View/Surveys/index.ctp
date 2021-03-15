<div class="container">
    <div class="row">
        <div class="col-9">
            <h1 style="text-align: center;">List of Surveys</h1>
            <?php if (count($surveys) == 0) { ?>
                <p>There isn't any Survey yet to display.</p>";
            <?php } else { ?>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Survey Name</th>
                            <th scope="col">Creator Email</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($surveys as $survey) : ?>
                            <tr>
                                <th scope="row"><?php echo $survey['Survey']['Id']; ?></th>
                                <td><?php echo $survey['Survey']['Name']; ?></td>
                                <td><?php echo $survey['Survey']['Email']; ?></td>
                                <td>
                                    <?php echo $this->Html->link(
                                        'View submissions',
                                        array('controller' => 'responses', 'action' => 'survey_submission', $survey['Survey']['Id']),
                                        array('class' => 'btn btn-primary')
                                    ); ?>
                                    <?php echo $this->Html->link(
                                        'Take survey',
                                        array('controller' => 'questions', 'action' => 'take_survey', $survey['Survey']['Id']),
                                        array('class' => 'btn btn-success')
                                    ); ?>
                                    <?php echo $this->Html->link(
                                        'Export',
                                        array('controller' => 'Responses', 'action' => 'export_submission', $survey['Survey']['Id'], $survey['Survey']['Name']),
                                        array('class' => 'btn btn-info')
                                    ); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($survey); ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
        <div class="col-3" id="chart_div">
        </div>
    </div>
</div>
<?php echo $this->Html->script('jquery-3.5.1.min.js', array('inline' => false)); ?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    // Load the Visualization API and the corechart package.
    google.charts.load('current', {
        'packages': ['corechart']
    });

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function drawChart() {

        $.ajax({
            url: "http://survey.test/surveys/getsurveys/",
            type: 'GET',
            cache: false,
            success: function(fdata) {
                var d = JSON.parse(fdata);
                // Create the data table.
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Survey');
                data.addColumn('number', 'Submissions');
                var arr = [];
                d.forEach(element => {
                    arr.push([element.survey, element.count])
                    //alert(element.survey);
                });
                data.addRows(arr);
                // Set chart options
                var options = {
                    'title': 'Number of submissions for each Survey',
                    'width': 400,
                    'height': 300
                };

                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(data, options);
            },
            error: function(error) {
                alert(error);
            }
        });
        return false;
    }
</script>