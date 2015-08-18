Feature: Basic concurrency
  In order to speedup scenarios execution
  As a user
  I should be able to run behat in concurrent mode

  Background: scenarios exists
    Given I have feature "A" with:
    | noop scenario         | 10 |
    | long running scenario | 1  |
    And I have feature "B" with:
    | noop scenario         | 10 |
    | long running scenario | 1  |

  Scenario: Concurrent mode is enabled only if amount of workers is more than 1
    When I run behat in concurrent mode using 1 worker
    Then no workers should be created
