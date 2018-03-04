<?php


$notifications = $v['notifications'];

?>
<script>
    jqueryComponent.ready(function () {

        <?php foreach($notifications as $item): ?>

        new PNotify({
            <?php if($item['title']): ?>
            title: "<?php echo $item['title']; ?>",
            <?php endif; ?>
            type: "<?php echo $item['type']; ?>",
            text: "<?php echo htmlspecialchars($item['message']); ?>",
            styling: 'bootstrap3'
        });
        <?php endforeach; ?>
    });
</script>