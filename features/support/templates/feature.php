Feature: <?= $name; ?>

    Use case

<?php foreach ($scenarios as $scenario) { ?>
    Scenario: <?= $scenario['name']; ?>

    <?php if (isset($scenario['steps']['given'])) { ?>
      Given <?= implode("\nAND ", $scenario['steps']['given']); ?>
    <?php } ?>

    <?php if (isset($scenario['steps']['when'])) { ?>
      When <?= implode("\nAND ", $scenario['steps']['when']); ?>

    <?php } ?>
      Then <?= implode("\nAND ", $scenario['steps']['then']); ?>

<?php } ?>
