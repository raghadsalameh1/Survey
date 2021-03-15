<div class="container">
    <h1 style="text-align: center;">List of Survey's Submissions </h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Submission date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($submitions as $submition) : ?>
                <tr>
                    <th scope="row"><?php echo $submition['Visitor']['Id']; ?></th>
                    <td><?php echo $submition['Visitor']['Vdate']; ?></td>
                    <td>
                        <?php echo $this->Html->link(
                            'View',
                            array('controller' => 'responses', 'action' => 'view_response', $submition['Visitor']['Id']),
                            array('class' => 'btn btn-primary')
                        ); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php unset($submition); ?>
        </tbody>
    </table>
</div>