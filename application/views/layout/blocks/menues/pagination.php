<ul class="pagination">
    <?php for ($i = 1; $i <= ceil($total_results / APP_RESULTS_PER_PAGE); $i++) : ?>
    <li <?php echo $page == $i ? 'class="disabled"' : null ?> ><a href="<?php echo base_url() . $ref_url . "?p=$i" ?>"><?php echo "$i" ?></a></li>
    <?php endfor ?>
</ul>