<?php
  global $wpdb;
  $sql = "TRUNCATE TABLE " . jssor_sliders();
  $wpdb->query($sql);

  $sql = "TRUNCATE TABLE " . jssor_slides();
  $wpdb->query($sql);
?>
