<div class="container">
    <h1 style="text-align: center;">List of Responses</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Question</th>
                <th scope="col">Response</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($responses as $response) : ?>
                <tr>
                    <th scope="row"><?php echo $response['Response']['Id']; ?></th>
                    <td><?php echo $response['Question']['Question']; ?></td>
                    <td><?php if ($response['Response']['Response'] == true) echo 'Yes';
                        else echo 'No'; ?></td>
                </tr>
            <?php endforeach; ?>
            <?php unset($responses); ?>
        </tbody>
    </table>
    <h1 style="text-align: center;">Notes</h1>
    <?php if(count($notes)==0) {?>
    <p>There is no notes to display.</p>
    <?php } else {
        foreach ($notes as $note) : ?>
         <p><?php echo $note['Note']['Note'];?></p>
    <?php endforeach; }?>
    <?php unset($notes); ?>
</div>